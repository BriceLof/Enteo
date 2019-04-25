<?php
namespace Application\PlateformeBundle\Services\Statut\Mail;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\SuiviAdministratif;

class MailForStatus extends \Application\PlateformeBundle\Services\Mailer
{
    public function alerteForStatus($detailStatut, $beneficiaire)
    {   
        $cc = array(array("email" => "support.informatique@entheor.com", "name" => "Support"));
        
        if($detailStatut->getDetail() == "Email suite No Contact"){
            $subject = "Pas de réponse suite aux appels";
            $to =  array(array("email" => $beneficiaire->getEmailConso(), "name" => $beneficiaire->getEmailConso()));
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

    /**
     * Ref 4a
     *
     * @param $suivis
     * @param $attachment
     */
    public function alerteAttenteAccord($suivis, $attachment){
        $ref = "4a";
        $from = array("email" => "christine.clementmolier@entheor.com", "name" => "christine.clementmolier@entheor.com");
        $subject = "Liste des ".count($suivis)." dossiers en Financement - Attente Accord à ce jour";
        $template = '@Apb/Alert/Mail/alerteAttenteAccord.html.twig';
        $to = array(array("email" => "contact@entheor.com", "name" => "contact@entheor.com"));
        $cc = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "virginie.hiairrassary@entheor.com", "name" => "Virginie Hiairrassary"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier"),
            array("email" => "ph.rouzaud@entheor.com", "name" => "Philippe Rouzaud"),
        );
        $bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
        $body = $this->templating->render($template, array(
            'suivis' => $suivis,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachment);
//        $this->sendMessage($from, 'f.azoulay@entheor.com',null, null, null, $subject, $body, $attachment);

    }

    /**
     * @param $suivis
     * @param $attachment
     * @throws \Exception
     */
    public function alerteAttenteTraitement($suivis, $attachment){
        $ref = "4b";
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "Liste des ".count($suivis)." dossiers en Attente Traitement depuis plus de 8 jours ( antérieur à ".(new \DateTime('now'))->modify('-8 day')->format('d/m/Y').")";
        $template = '@Apb/Alert/Mail/alerteAttenteTraitement.html.twig';
        $to = array(array("email" => "contact@entheor.com", "name" => "contact@entheor.com"));
        $cc = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "virginie.hiairrassary@entheor.com", "name" => "Virginie Hiairrassary"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier"),
            array("email" => "ph.rouzaud@entheor.com", "name" => "Philippe Rouzaud"),
        );
        $bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
        $body = $this->templating->render($template, array(
            'suivis' => $suivis,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachment);
//        $this->sendMessage($from, 'f.azoulay@entheor.com',null, null, null, $subject, $body, $attachment);
    }

    /**
     * @param $suivis
     * @param $attachment
     * @throws \Exception
     */
    public function alerteAttenteDocument($suivis, $attachment){
        $ref = "4c";
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "Liste des ".count($suivis)." dossiers en Attente Documents depuis plus de 8 jours ( antérieur à ".(new \DateTime('now'))->modify('-8 day')->format('d/m/Y').")";
        $template = '@Apb/Alert/Mail/alerteAttenteDocument.html.twig';
        $to = array(array("email" => "contact@entheor.com", "name" => "contact@entheor.com"));
        $cc = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "virginie.hiairrassary@entheor.com", "name" => "Virginie Hiairrassary"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier"),
            array("email" => "ph.rouzaud@entheor.com", "name" => "Philippe Rouzaud"),
        );
        $bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
        $body = $this->templating->render($template, array(
            'suivis' => $suivis,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachment);
//        $this->sendMessage($from, 'ranfidy@hotmail.com',null, null, null, $subject, $body, $attachment);
    }

    /**
     * Ref 5a
     *
     * @param $beneficiaire
     * @param $user
     * @param $motif
     */
    public function AbandonBeneficiaire($beneficiaire, $user, $motif){
        $ref = "5a";
        $from = array("email" => "christine.clementmolier@entheor.com", "name" => "christine.clementmolier@entheor.com");
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
        $from = array("email" => "audrey.azoulay@entheor.com", "name" => "audrey.azoulay@entheor.com");
        $subject = "Votre demande d'information pour une VAE";
        $template = '@Apb/Alert/Mail/emailSuiteNoContact.html.twig';
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'reference' => $ref,
        ));

        $this->sendMessageToAdminAndGestion($from, $subject, $body);
    }

    /**
     * Ref 4b
     *
     * @param $suivis
     * @param $attachment
     */
    public function planning($tab1, $tab2, $tab3){
        $ref = "7";
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "Liste des Bénéficiaires dont le planning est en retard";
        $template = '@Apb/Alert/Mail/planning.html.twig';
        $to = array(array("email" => "contact@entheor.com", "name" => "contact@entheor.com"));
        $cc = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier"),
            array("email" => "ph.rouzaud@entheor.com", "name" => "Philippe Rouzaud"),
        );
        $bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
        $body = $this->templating->render($template, array(
            'tab1' => $tab1,
            'tab2' => $tab2,
            'tab3' => $tab3,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
//        $this->sendMessage($from, array(array("email" => "ranfidy@hotmail.com", "name" => "Brice Lof")) ,null, null, null, $subject, $body);
    }
    
}