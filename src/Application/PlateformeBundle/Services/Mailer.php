<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;
use Application\PlateformeBundle\Services\Date;
use Application\PlateformeBundle\Entity\Feedback;

class Mailer
{
    protected $em;
    protected $mailer;
    protected $templating;
    protected $date;
    protected $from = "admin@entheor.com";
    protected $reply = "";
    protected $name = "Equipe Entheo";
    
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EntityManager $em, Date $date)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->date = $date;
    }

    protected function sendMessage($from, $to, $replyTo, $cc = null, $bcc = null, $subject, $body){
        $mail = \Swift_Message::newInstance();

        if(!empty($replyTo))
            $mail->setReplyTo($replyTo);
        if(!empty($cc))
            $mail->setCc($cc);
        if(!empty($bcc))
            $mail->setBcc($bcc);
        if(!empty($subject))
            $mail->setSubject($subject);
        $mail
            ->setFrom($from)
            ->setTo($to)
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
        $this->sendMessage($this->from,$to,$cc = null, $bcc = null,$subject,$body);
    }


    /**
     * envoi de mail pour le rapport hebdomadaire de la suppression des documents
     * non-compressés
     */
    public function mailRecapCronDocument($compteur){
        $subject = "Rapport CRON suppression des documents Enteo";
        $template = '@Apb/Alert/Mail/rapportCronDocument.html.twig';
        $to = "n.ranaivoson@iciformation.fr";
        $body = $this->templating->render($template, array(
            'compteur' => $compteur,
        ));
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc = null,$subject,$body);
    }
    
    public function mailFeedback(Feedback $feedback){
        
        $subject = "Feedback [".ucfirst($feedback->getType())."]";
        
        $adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $listeAdministrateurs = array();
        foreach($adminitrateurs as $admin){ $listeAdministrateurs[] = $admin->getEmail(); }
        $to = $listeAdministrateurs;
        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        $message = "Bonjour, <br><br> Un feedback vous a été envoyé par <b><a href='https://appli.entheor.com/web/user/".$feedback->getUser()->getId()."/show'>".ucfirst($feedback->getUser()->getCivilite())."".ucfirst($feedback->getUser()->getPrenom())." ".ucfirst($feedback->getUser()->getNom())."</a></b> : <br>
                <ul>
                    <li><b>Type : </b>".ucfirst($feedback->getType())."</li>
                    <li><b>Description : </b>".ucfirst($feedback->getDescription())."</li>";

        if($feedback->getType() == "bug"){
            $message .= "<li><b>Url : </b>".$feedback->getUrl()."</li><li><b>Détail : </b>".$feedback->getDetail()."</li>
                    <li><img src='https://appli.entheor.com/web/uploads/feedback/img/".$feedback->getImage()."'></li>";
        }
        
		$message .= "</ul>";
		
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message,
            'reference' => '400'
        ));
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc = null,$subject,$body);
    }
}
?>