<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Symfony\Component\Templating\EngineInterface;
use Doctrine\ORM\EntityManager;

class Mailer
{
    protected $em;
    protected $mailer;
    protected $templating;
    protected $from = "admin@entheo.com";
    protected $reply = "admin@entheo.com";
    protected $name = "Equipe Entheo";
    
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EntityManager $em)
    {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->templating = $templating;   
    }

    protected function sendMessage($from, $to, $replyTo, $cc = null, $bcc = null, $subject, $body){
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($from)
            ->setTo($to)
            ->setCc($cc)
			->setBcc($bcc)
            ->setSubject($subject)
            ->setBody($body)
            ->setContentType('text/html')
            ->setReplyTo($replyTo);

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
        $this->sendMessage($this->from,$to,$cc = null, $bcc = null,$subject,$body);
    }
}
?>