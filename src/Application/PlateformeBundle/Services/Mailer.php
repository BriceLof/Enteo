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
    protected $from = array("email" => "admin@entheor.com", "name" => "admin@entheor.com");
    protected $reply = "";
    protected $name = "Equipe Entheo";
    protected $webDirectory;
    protected $hostname;
    
    public function __construct(\Swift_Mailer $mailer , EngineInterface $templating, EntityManager $em, Date $date, $webDirectory, $hostname)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->em = $em;
        $this->date = $date;
        $this->webDirectory = $webDirectory;
        $this->hostname = $hostname;
    }

    public function sendMessage($from, $to, $replyTo = null, $cc = null, $bcc = null, $subject, $body, $attachement = null){

        if(is_null($replyTo)) $reply =  array("email" => $from['email']);

        $data = array(
            "tags" => array("Entheor"),
            "sender" => $from,
            "to" => $to,
            "htmlContent" => $body,
            "textContent" => strip_tags($body),
            "replyTo" => $reply,
            "subject" => $subject
        );

        if(!is_null($cc) && $cc != ''){
            $data["cc"] = $cc;
        }
        if(!is_null($bcc) && $bcc != ''){
            $data["bcc"] = $bcc;
        }
        if(!is_null($attachement)){
            $file_name_explode = explode("/web", $attachement);
            $file = explode("/",$file_name_explode[1]);
            $file_path = $this->hostname.$file_name_explode[1];
            $data["attachment"] = array(array("url" => $file_path, "name" => $file[count($file) - 1]));
        }

        if($this->hostname == "https://appli-dev.entheor.com/web"){
            $data["to"] = array(array("email" => "brice.lof@gmail.com", "name" => "Brice Lof"));
            if(!is_null($cc) && $cc != ''){
                $data["cc"] = $data["to"];
            }
            if(!is_null($bcc) && $bcc != ''){
                $data["bcc"] = $data["to"];
            }
        }

        // Mail envoye par smtp de sentingblue
        $header = array('Accept: application/json', 'Content-Type: application/json', 'api-key: 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');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_URL => "https://api.sendinblue.com/v3/smtp/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data)
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
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
        $to = array(array("email" => "n.ranaivoson@iciformation.fr", "name" => "n.ranaivoson@iciformation.fr"));
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
        $to = array(array("email" => "n.ranaivoson@iciformation.fr", "name" => "n.ranaivoson@iciformation.fr"));
        $body = $this->templating->render($template, array(
            'compteur' => $compteur,
        ));
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc = null,$subject,$body);
    }
    
    public function mailFeedback(Feedback $feedback){
        
        $subject = "Feedback [".ucfirst($feedback->getType())."]";
        
        $adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $listeAdministrateurs = array();
        foreach($adminitrateurs as $admin){
           // $listeAdministrateurs[$admin->getEmail()] = $admin->getEmail();
            array_push($listeAdministrateurs, array("email" => $admin->getEmail(), "name" => $admin->getEmail()));
        }
        $to = $listeAdministrateurs;
		$bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
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
        $bcc = array(array("email" => "support.informatique@entheor.com", "name" => "Support"), array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"));

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
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "audrey.azoulay@entheor.com", "name" => "Audrey Azoulay"),
            array("email" => "ph.rouzauf@entheor.com", "name" => "Philippe Rouzaud"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier")
        );
        $bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
        $this->sendMessage($this->from,$to,null,$cc = null, $bcc = null ,$subject,$body, $attachement);
    }
}
?>