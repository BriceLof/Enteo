<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    protected $mailer;
    protected $templating;
    private $from = "admin@enteo.fr";
    private $reply = "contact@enteo.fr";
    private $name = "Equipe Anteo";

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
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
}
?>