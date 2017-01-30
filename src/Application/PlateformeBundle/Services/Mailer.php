<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;

class Mailer
{
    private $em;
    protected $mailer;
    protected $templating;
    private $from = "admin@enteo.fr";
    private $reply = "contact@enteo.fr";
    private $name = "Equipe Anteo";
    
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EntityManager $em)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;   
    }

    protected function sendMessage($from, $to, $subject, $body){
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($mail);
    }

    public function sendFactureSoldeMessage(Beneficiaire $beneficiaire){
        $subject = "Facture solde message";
        $template = '@Apb/SuiviAdministratif/mail/factureSoldeMail.html.twig';
        $to = "n.ranaivoson@iciformation.fr";
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
        ));
        $this->sendMessage($this->from,$to,$subject,$body);
    }
    
    public function alerteSuiteRv1()
    {
        $newsRepo = $this->em->getRepository("ApplicationPlateformeBundle:News");
        $listeRv = $newsRepo->getNewsByStatut();
        $sucess = "";
        foreach($listeRv as $rv)
        {   
            $beneficiaireID = $rv->getBeneficiaire()->getId();
            // beneficiaire ayant fini le RV1
            $nextStep = $newsRepo->findOneBy(array('beneficiaire' => $beneficiaireID, 'statut' => '4'));
            var_dump("beneficiaire ID : ".$beneficiaireID." | RV2 : ".count($nextStep));
			var_dump($nextStep);
            // envoi email en fonction du temps passé entre la RV1 et la date du jour 
            if(is_null($nextStep))
            {
				var_dump($rv->getBeneficiaire()->getConsultant());exit;
                $subject = "Post RV1";
                $template = "@Apb/Alert/Mail/postRv1.html.twig";
                $body = $this->templating->render($template, array(
                    'consultant' => $rv->getBeneficiaire()->getConsultant()
                ));
                
                $this->sendMessage($this->from, "brice.lof@gmail.com", $subject, $body);
                
                return "mail envoyé";
            }
            
            
        }
        
        
    }
}
?>