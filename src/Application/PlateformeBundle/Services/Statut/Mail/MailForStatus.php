<?php
namespace Application\PlateformeBundle\Services\Statut\Mail;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\SuiviAdministratif;

class MailForStatus extends \Application\PlateformeBundle\Services\Mailer
{
    public function alerteForStatus($detailStatut, $beneficiaire)
    {   
        $cc = array(
                "support.informatique@entheor.com" => "Support",
                "b.lof@iciformation.fr" => "Brice");
        
        if($detailStatut->getDetail() == "Email suite No Contact"){
            $subject = "Pas de réponse suite aux appels";
            $to =  $beneficiaire->getEmailConso();
            $message = "Bonjour <b>".$beneficiaire->getCiviliteConso()." ".$beneficiaire->getNomConso()." ".$beneficiaire->getPrenomConso()."</b>, <br><br>"
                . "Nous avons essayé de vous joindre à plusieurs reprises, mais sans succès.<br>
                Ci-dessous les coordonnées téléphonique d'un commercial pour que vous puissiez prendre contact : <br>
                <b>01 42 78 44 25</b>";
            $template = '@Apb/Alert/Mail/mailByStatut.html.twig';
        }
        
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message
        ));

        return $this->sendMessage($this->from, $to,null, $cc, $subject, $body);
    }

    public function alerteAttenteAccord($suivis, $attachment){
        $ref = "4a";
        $from = "christine.clementmolier@entheor.com";
        $subject = "Liste des ".count($suivis)." dossiers en Financement - Attente Accord depuis plus de 15 jours ( antérieur à ".(new \DateTime('now'))->modify('-15 day')->format('d/m/Y').")";
        $template = '@Apb/Alert/Mail/alerteAttenteAccord.html.twig';
        $to = array(
            "contact@entheor.com" => "Contact"
        );
        $cc = array(
            "virginie.hiairrassary@entheor.com" => "Virginie Hiairrassary",
            "christine.clementmolier@entheor.com" => "Christine Clement Molier",
            "f.azoulay@entheor.com" => "Franck Azoulay",
            "ph.rouzaud@entheor.com" => "Philippe Rouzaud"
        );
        $bcc = "support.informatique@entheor.com";
        $body = $this->templating->render($template, array(
            'suivis' => $suivis,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachment);
//        $this->sendMessage($from, 'f.azoulay@entheor.com',null, null, null, $subject, $body, $attachment);

    }

    public function alerteAttenteTraitement($suivis, $attachment){
        $ref = "4b";
        $from = "christine.clement@entheor.com";
        $subject = "Liste des ".count($suivis)." dossiers en Attente Traitement depuis plus de 15 jours ( antérieur à ".(new \DateTime('now'))->modify('-15 day')->format('d/m/Y').")";
        $template = '@Apb/Alert/Mail/alerteAttenteTraitement.html.twig';
        $to = array(
            "contact@entheor.com" => "Contact"
        );
        $cc = array(
            "virginie.hiairrassary@entheor.com" => "Virginie Hiairrassary",
            "christine.clementmolier@entheor.com" => "Christine Clement Molier",
            "f.azoulay@entheor.com" => "Franck Azoulay",
            "ph.rouzaud@entheor.com" => "Philippe Rouzaud"
        );
        $bcc = "support.informatique@entheor.com";
        $body = $this->templating->render($template, array(
            'suivis' => $suivis,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachment);
//        $this->sendMessage($from, 'f.azoulay@entheor.com',null, null, null, $subject, $body, $attachment);
    }

    public function AbandonBeneficiaire($beneficiaire, $user, $motif){
        $ref = "5a";
        $from = "christine.clementmolier@entheor.com";
        $subject = "Abandon d'un Bénéficiaire";
        $template = '@Apb/Alert/Mail/alerteAbandon.html.twig';
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'user' => $user,
            'reference' => $ref,
            'motif' => $motif
        ));

        $this->sendMessageToAdminAndGestion($from, $subject, $body);
    }

    /**
     * ref 6
     * email suite a la cron
     * Détail Statut = Tentative 1 depuis >= 5 jours
     *
     * @param $beneficiaire
     */
    public function EmailSuiteNoContactBeneficiaire($beneficiaire){
        $ref = "6";
        $from = "audrey.azoulay@entheor.com";
        $subject = "Votre demande d'information pour une VAE";
        $template = '@Apb/Alert/Mail/emailSuiteNoContact.html.twig';
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'reference' => $ref,
        ));

        $this->sendMessageToAdminAndGestion($from, $subject, $body);
    }
    
}