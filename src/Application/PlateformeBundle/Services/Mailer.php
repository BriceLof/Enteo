<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;
use Application\PlateformeBundle\Entity\Feedback;
use Application\PlateformeBundle\Services\Date;

class Mailer
{
    protected $em;
    protected $mailer;
    protected $templating;
    protected $date;
    protected $from = "admin@entheor.com";
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

        $mail = \Swift_Message::newInstance();

        //---- DKIM
        //var_dump($_SERVER['HTTP_HOST']);exit;
        $privateKey = file_get_contents($this->webDirectory."private/dkim.private.key");
        $signer = new \Swift_Signers_DKIMSigner($privateKey, 'appli.entheor.com', 'default' ); // param 1 : private key, 2 : domaine, 3 : selecteur DNS
        $mail->attachSigner($signer);

        if(!empty($replyTo))
            $mail->setReplyTo($replyTo);
        if(!empty($cc))
            $mail->setCc($cc);
        if(!empty($bcc))
            $mail->setBcc($bcc);
        if(!empty($subject))
            $mail->setSubject($subject);
        if (!empty($attachement)){
            $mail->attach($attachement);
        }

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html')
            ->addPart(strip_tags($body), 'text/plain');

        $this->mailer->send($mail);
    }

    public function sendMessageToAdminAndGestion($from, $subject, $body, $attachement = null){
        $mail = \Swift_Message::newInstance();

        //---- DKIM
        $privateKey = file_get_contents($this->webDirectory."private/dkim.private.key");
        $signer = new \Swift_Signers_DKIMSigner($privateKey, $_SERVER['HTTP_HOST'], 'default' ); // param 1 : private key, 2 : domaine, 3 : selecteur DNS
        $mail->attachSigner($signer);

        $query = $this->em->getRepository('ApplicationUsersBundle:Users')->search('ROLE_GESTION');
        $gestionnaires = $query->getResult();
        foreach ($gestionnaires as $gestionnaire){
            $emails [] = $gestionnaire->getEmail();
        }
        $emails [] = 'f.azoulay@entheor.com';
        $emails [] = 'ph.rouzaud@entheor.com';
        $emails [] = 'christine.clementmolier@entheor.com';
        $to = $emails;
        $bcc = "support.informatique@entheor.com";

        $mail->setBcc($bcc);
        $mail->setSubject($subject);
        if (!empty($attachement)){
            $mail->attach($attachement);
        }

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, 'text/html')
            ->addPart(strip_tags($body), 'text/plain');

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
		$bcc = array(
				"support.informatique@entheor.com" => "Support",
			);
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

        $bcc = array("support.informatique@entheor.com" => "Support");
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc ,$subject,$body, $attachement);
    }
}
?>