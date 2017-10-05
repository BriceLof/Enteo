<?php
namespace Application\UsersBundle\Services\Mailer;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Services\Mailer;
use Application\UsersBundle\Entity\Users;

class MissionMailer extends Mailer
{
    public function newMission(Beneficiaire $beneficiaire, Users $consultant, $attachement){

        $ref = 'a';
        $from = "christine.clement@entheor.com";
        $replyTo = "christine.clement@entheor.com";
        $subject = "New Bénéficiaire";
        $template = '@Aub/Mission/mail/newMission.html.twig';
        $to = $consultant->getEmail();
        $cc = null;
        $bcc = "support.informatique@entheor.com";
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachement);
    }
}