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

    protected function sendMessage($from, $to, $cc = null, $subject, $body){
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setCc($cc)
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
        // Récupération des news quin sont au statut RV1 (id 3) 
        $listeRv = $newsRepo->getNewsByStatut();

        $sucess = "";
        foreach($listeRv as $rv)
        {   
            $beneficiaireID = $rv->getBeneficiaire()->getId();
            // beneficiaire ayant fini le RV1 
            $nextStep = $newsRepo->findOneBy(array('beneficiaire' => $beneficiaireID, 'statut' => '4'));

            // envoi email en fonction du temps passé entre la RV1 et la date du jour 
            if(is_null($nextStep))
            {
                $dateRv = $rv->getDateHeure();
                $dateCurrent = new \DateTime();
                
                $dateMoreOneDay = $dateRv->add(new \DateInterval('P1D'))->format('Y-m-d');
                $dateMoreTwoDay = $dateRv->add(new \DateInterval('P2D'))->format('Y-m-d');
                $dateMoreThreeDay = $dateRv->add(new \DateInterval('P3D'))->format('Y-m-d');
                
                $template = "@Apb/Alert/Mail/postRv1.html.twig";
                
                $emailConsultant = $rv->getBeneficiaire()->getConsultant()->getEmail();
                
                if($dateMoreOneDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 1 à J+1
                    var_dump("j+1");
                    $subject = "Rappel J+1 : Post RV1 ";
                    $to = $emailConsultant; 
                }
                elseif($dateMoreTwoDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 2 à J+3
                    $subject = "Rappel J+3 : Post RV1 ";
                    $to = $emailConsultant;
                    $cc = array(
                            "support@iciformation.fr" => "Support",
                            "b.lof@iciformation.fr" => "Brice");
                }
                elseif($dateMoreThreeDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 3 à J+7
                    $subject = "Rappel J+7 [URGENT] : Post RV1 ";
                    $to = $emailConsultant;
                    $cc = array(
                            "support@iciformation.fr" => "Support",
                            "b.lof@iciformation.fr" => "Brice");
                }

                $body = $this->templating->render($template, array(
                    'consultant' => $rv->getBeneficiaire()->getConsultant(), 
                    'beneficiaire' => $rv->getBeneficiaire(),
                    'sujet' => $subject
                ));
                
                $this->sendMessage($this->from, $to, $cc, $subject, $body);
                
                return "mail envoyé";
            }
            
            
        }
        
        
    }
}
?>