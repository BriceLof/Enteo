<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use FOS\UserBundle\EventListener\EmailConfirmationListener;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;
use Application\PlateformeBundle\Entity\Feedback;
use Application\PlateformeBundle\Services\Date;
use Application\PlateformeBundle\Services\ExternalLibraries\SendinblueSmtp;

class Mailer
{
    protected $em;
    protected $mailer;
    protected $templating;
    protected $date;
    protected $from = array("email_adress" => "admin@entheor.com", "alias" => "admin@entheor.com");
    protected $reply = "";
    protected $name = "Equipe Entheo";
    protected $webDirectory;
    
    public function __construct(\Swift_Mailer $mailer , EngineInterface $templating, EntityManager $em, Date $date, $webDirectory)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->em = $em;
        $this->date = $date;
        $this->webDirectory = $webDirectory;
    }

    public function sendMessage($from, $to, $replyTo, $cc = null, $bcc = null, $subject, $body, $attachement = null){


        $mail = new SendinblueSmtp('admin@entheor.com', 'pmKx329kNw6BaFct');


        if (array_key_exists('email_adress', $from)) $mail->setFrom($from['email_adress'], $from['alias']);
        else $mail->setFrom($from);

        if (array_key_exists('email_adress', $to)) $mail->addTo($to['email_adress'], $to['alias']);
        else $mail->addTo($to);


        if(!empty($replyTo)){
            if (array_key_exists('email_adress', $replyTo)) $mail->setReplyTo($replyTo['email_adress'], $replyTo['alias']);
        }

        if(!empty($cc)){
            if (array_key_exists('email_adress', $cc)) $mail->addCc($cc['email_adress'], $cc['alias']);
            else $mail->addCc($cc);
        }

        if(!empty($bcc)){
            if (array_key_exists('email_adress', $bcc)) $mail->addBcc($bcc['email_adress'], $bcc['alias']);
            else $mail->addBcc($bcc);
        }

        if(!empty($subject)){
            $mail->setSubject($subject);
        }


        $mail->
            setHtml($body)->
            setText(strip_tags($body));

        $res = $mail->send();
        var_dump($res);

    }

    public function sendMessageToAdminAndGestion($from, $subject, $body, $attachement = null){

        $mail = new SendinblueSmtp('admin@entheor.com', 'pmKx329kNw6BaFct');
        $to = array(
            "virginie.hiairrassary@entheor.com" => "virginie.hiairrassary@entheor.com",
            "f.azoulay@entheor.com" => "f.azoulay@entheor.com",
            "ph.rouzaud@entheor.com" => "ph.rouzaud@entheor.com",
            "christine.clementmolier@entheor.com" => "christine.clementmolier@entheor.com",
            "contact@entheor.com" => "contact@entheor.com",
        );

        $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");


        if (array_key_exists('email_adress', $from)) $mail->setFrom($from['email_adress'], $from['alias']);
        else $mail->setFrom($from);

        if(!empty($bcc)){
            if (array_key_exists('email_adress', $bcc)) $mail->addBcc($bcc['email_adress'], $bcc['alias']);
            else $mail->addBcc($bcc);
        }

        if(!empty($subject)){
            $mail->setSubject($subject);
        }


        if (!empty($attachement)){
            // a faire fonctionner avec sendinblue et puis decommenter
            //$mail->attach($attachement);
        }

        $mail
            ->setTo($to)
            ->setBody($body, 'text/html')
            ->addPart(strip_tags($body), 'text/plain');

        $this->mailer->send($mail);
    }

    public function sendFactureSoldeMessage(Beneficiaire $beneficiaire){
        $subject = "Facture solde message";
        $template = '@Apb/SuiviAdministratif/mail/factureSoldeMail.html.twig';
        $to = array("email_adress" => "n.ranaivoson@iciformation.fr", "alias" => "n.ranaivoson@iciformation.fr");
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
        $to = array("email_adress" => "n.ranaivoson@iciformation.fr", "alias" => "n.ranaivoson@iciformation.fr");
        $body = $this->templating->render($template, array(
            'compteur' => $compteur,
        ));
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc = null,$subject,$body);
    }
    
    public function mailFeedback(Feedback $feedback){
        
        $subject = "Feedback [".ucfirst($feedback->getType())."]";
        
        $adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $listeAdministrateurs = array();
        foreach($adminitrateurs as $admin){ $listeAdministrateurs[$admin->getEmail()] = $admin->getEmail(); }
        $to = $listeAdministrateurs;
		$bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");
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
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc ,$subject,$body);
    }

    public function sendNewNotification($to, $subject, $message)
    {
        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message,
            'reference' => 'Notification'
        ));
        $bcc = array("support.informatique@entheor.com" => "Support", "f.azoulay@entheor.com" => "Franck Azoulay");

        $this->sendMessage($this->from,$to,null,$cc = null, $bcc ,$subject,$body);
    }

    public function listeBeneficiairesPreviousWeekNoContact($message, $attachement)
    {
        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        $subject = "Liste des bénéficiaires S-1 non contactés";
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message,
            'reference' => '10-a'
        ));
        $to = array(
            //'b.lof@iciformation.fr' => "Brice",
            'f.azoulay@entheor.com' => 'Franck Azoulay',
            "audrey.azoulay@entheor.com" => "Audrey Azoulay",
            "ph.rouzauf@entheor.com" => "Philippe Rouzaud",
            "christine.clementmolier@entheor.com" => "Christine Molier"
        );

        $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc ,$subject,$body, $attachement);
    }
}
?>