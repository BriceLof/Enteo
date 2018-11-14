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
    protected $fromSms = "Entheor";
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

    // Envoi avec SMTP SendinBlue
    public function sendMessage($from, $to, $replyTo = null, $cc = null, $bcc = null, $subject, $body, $attachement = null, Array $sms = null){

        if(is_null($replyTo)) $reply =  array("email" => $from['email']);
        else $reply = $replyTo;

        $data = array(
            "tags" => array("Entheor"),
            "sender" => $from,
            "to" => $to,
            "htmlContent" => $body,
            "textContent" => strip_tags($body),
            "replyTo" => $reply,
            "subject" => $subject
        );

        if(!is_null($attachement) && $attachement != ''){
            // Fichier non stocké sur le serveur mais créer juste pour etre dans l'email
            if (array_key_exists('name', $attachement) && array_key_exists('file', $attachement) && count($attachement) == 2) {
                $data["attachment"] = array(array("content" => base64_encode ( $attachement['file'] ), "name" => $attachement['name']));
            }else{
                // Fichier stocker sur le serveur
                $file_name_explode = explode("/web", $attachement);
                $file = explode("/",$file_name_explode[1]);
                $file_path = $this->hostname.$file_name_explode[1];
                $data["attachment"] = array(array("url" => $file_path, "name" => $file[count($file) - 1]));
            }
        }

        if($this->hostname == "https://appli-dev.entheor.com/web"){
            $data["to"] = array(array("email" => "brice.lof@gmail.com", "name" => "Brice Lof"));
//            if(!is_null($cc) && $cc != ''){
//                $data["cc"] = $data["to"];
//            }
//            if(!is_null($bcc) && $bcc != ''){
//                $data["bcc"] = $data["to"];
//            }
        }else{
            if(!is_null($cc) && $cc != ''){
                $data["cc"] = $cc;
            }
            if(!is_null($bcc) && $bcc != ''){
                $data["bcc"] = $bcc;
            }
        }


        if(is_null($sms))
            $this->apiCurlSmtpSendinblue($data);
        else
            $this->apiCurlSmtpSendinblue($data, $sms);
    }

    // Envoi par swfitmailer des mails pas important pour économiser notre quota sendinblue
    public function sendMessageSwiftmailer($from, $to, $replyTo, $cc = null, $bcc = null, $subject, $body, $attachement = null){
        $mail = \Swift_Message::newInstance();

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

        $to = array(
            array("email" => "virginie.hiairrassary@entheor.com", "name" => "virginie.hiairrassary@entheor.com"),
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "ph.rouzaud@entheor.com", "name" => "ph.rouzaud@entheor.com"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier"),
            array("email" => "contact@entheor.com", "name" => "contact@entheor.com"),
        );

        $data = array(
            "tags" => array("Entheor"),
            "sender" => $from,
            "subject" => $subject,
            "to" => $to,
            "bcc" => array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com")),
            "htmlContent" => $body,
            "textContent" => strip_tags($body),
            "replyTo" => $from,
        );

        if(!is_null($attachement) && $attachement != ''){
            // Fichier non stocké sur le serveur mais créer juste pour etre dans l'email
            if (array_key_exists('name', $attachement) && array_key_exists('file', $attachement) && count($attachement) == 2) {
                $data["attachment"] = array(array("content" => base64_encode ( $attachement['file'] ), "name" => $attachement['name']));
            }else{
                // Fichier stocker sur le serveur
                $file_name_explode = explode("/web", $attachement);
                $file = explode("/",$file_name_explode[1]);
                $file_path = $this->hostname.$file_name_explode[1];
                $data["attachment"] = array(array("url" => $file_path, "name" => $file[count($file) - 1]));
            }
        }

        if($this->hostname == "https://appli-dev.entheor.com/web"){
            $data["to"] = array(array("email" => "brice.lof@gmail.com", "name" => "Brice Lof"));
        }

        $this->apiCurlSmtpSendinblue($data);
    }

    public function sendFactureSoldeMessage(Beneficiaire $beneficiaire){
        $from = "admin@entheor.com";
        $subject = "Facture solde message";
        $template = '@Apb/SuiviAdministratif/mail/factureSoldeMail.html.twig';
        $to = "n.ranaivoson@iciformation.fr";
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
        ));
        $this->sendMessageSwiftmailer($from,$to,$cc = null, $bcc = null,$subject,$body);
    }


    /**
     * envoi de mail pour le rapport hebdomadaire de la suppression des documents
     * non-compressés
     */
    public function mailRecapCronDocument($compteur){
        $subject = "Rapport CRON suppression des documents Enteo";
        $template = '@Apb/Alert/Mail/rapportCronDocument.html.twig';
        $from = "admin@entheor.com";
        $to = "n.ranaivoson@iciformation.fr";
        $body = $this->templating->render($template, array(
            'compteur' => $compteur,
        ));
        $this->sendMessageSwiftmailer($from, $to,null,$cc = null, $bcc = null,$subject,$body);
    }
    
    public function mailFeedback(Feedback $feedback){
        
        $subject = "Feedback [".ucfirst($feedback->getType())."]";
        $from = "admin@entheor.com";
        $adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $listeAdministrateurs = array();
        foreach($adminitrateurs as $admin){
            $listeAdministrateurs[$admin->getEmail()] = $admin->getEmail();
        }
        $to = $listeAdministrateurs;
		$bcc = array("support.informatique@entheor.com" => "Support");
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
        $this->sendMessageSwiftmailer($from,$to,null,$cc = null, $bcc ,$subject,$body);
    }

    public function sendNewNotification($to, $subject, $message)
    {
        $from = "admin@entheor.com";
        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message,
            'reference' => 'Notification'
        ));
        $bcc = array("support.informatique@entheor.com"=> "Support", "f.azoulay@entheor.com"=> "Franck Azoulay");

        $this->sendMessageSwiftmailer($from,$to,null,$cc = null, $bcc ,$subject,$body);
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

    public function apiCurlSmtpSendinblue($data, $sms = null){

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

        // A décommenter pour voir le retour de l'api en cas d'erreur
//        if ($err) {
//            echo "cURL Error #:" . $err;
//        } else {
//            echo $response;
//        }




        // SMS transactionnel
        // a reactiver quand sms sera vu avec Franck
        if(!is_null($sms)){

            $sms['sender'] = $this->fromSms;
            $sms['type'] = "transactional";

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_URL => "https://api.sendinblue.com/v3/transactionalSMS/sms",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($sms)
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

//            if ($err) {
//                echo "cURL Error #:" . $err;
//            } else {
//                echo $response;
//            }
        }



    }
}

?>