<?php
namespace Application\UsersBundle\Services\Mailer;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Services\Mailer;
use Application\UsersBundle\Entity\Mission;
use Application\UsersBundle\Entity\Users;

class UsersMailer extends Mailer
{

    /**
     * ref c-1
     *
     *
     * @param Users $consultant
     * @param Beneficiaire $beneficiaire
     */
    public function alerteExpirationVigilance($consultants, $attachement){
        $ref = 'c-1';
        $from = array("email" => "admin@entheor.com", "name" => "admin@entheor.com");
        $subject = "Liste des ".count($consultants)." Consultants dont l'attestation de vigilance n'est plus valable";
        $template = '@Aub/Users/mail/expirationVigilance.html.twig';
        $to = array(
            array("email" => "f.azoulay@entheor.com", "name" => "f.azoulay@entheor.com"),
            array("email" => "ph.rouzaud@entheor.com", "name" => "ph.rouzaud@entheor.com"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "christine.clementmolier@entheor.com"),
            array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"),
            array("email" => "contact@entheor.com", "name" => "Contact"),
        );
        $to = array(
            array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com")
        );
        $cc = null;
        $bcc = null;
        $body = $this->templating->render($template, array(
            'consultants' => $consultants,
            'reference' => $ref,
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachement);
    }

    /**
     * ref c-1
     *
     *
     * @param Users $consultant
     * @param Beneficiaire $beneficiaire
     */
    public function alerteNumDeclarationActivite($consultants, $attachement){
        $ref = 'c-2';
        $from = array("email" => "admin@entheor.com", "name" => "admin@entheor.com");
        $subject = "Liste des ".count($consultants)." Consultants sans numéro de déclaration d'activité";
        $template = '@Aub/Users/mail/numDeclarationActivite.html.twig';
        $to = array(
            array("email" => "f.azoulay@entheor.com", "name" => "f.azoulay@entheor.com"),
            array("email" => "ph.rouzaud@entheor.com", "name" => "ph.rouzaud@entheor.com"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "christine.clementmolier@entheor.com"),
            array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"),
            array("email" => "contact@entheor.com", "name" => "Contact"),
        );
        $to = array(
            array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com")
        );
        $cc = null;
        $bcc = null;
        $body = $this->templating->render($template, array(
            'consultants' => $consultants,
            'reference' => $ref,
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachement);
    }

}