<?php
namespace Application\PlateformeBundle\Services\Statut\Mail;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\SuiviAdministratif;

class MailForStatus extends \Application\PlateformeBundle\Services\Mailer
{
    public function alerteForStatus($detailStatut, $beneficiaire)
    {   
        $cc = array(
                "support@iciformation.fr" => "Support",
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

    public function alerteAttenteAccord(Beneficiaire $beneficiaire, SuiviAdministratif $lastSuiviAdministratif){
        $from = "christine.clement@entheor.com";
        $replyTo = "christine.clement@entheor.com";
        $subject = "Financement / Attente Accord de ". $beneficiaire->getCiviliteConso() ." ". $beneficiaire->getPrenomConso()." ". $beneficiaire->getNomConso() ." ";
        $template = '@Apb/Alert/Mail/alerteAttenteAccord.html.twig';
        $to =  "resp.administratif@entheor.com";
        $cci = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "virginie.hiairrassary@entheor.com" => "Virginie HIAIRRASSARY",
            "n.ranaivoson@iciformation.fr" => "Ndremifidy Ranaivoson",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clement@entheor.com" => "Christine Clement"
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'lastSuiviAdministratif' => $lastSuiviAdministratif,
        ));

        $this->sendMessage($from,$to,$replyTo,null,$cci,$subject,$body);
//        $this->sendMessage($from,"f.azoulay@entheor.com", $replyTo, null,null,$subject,$body);
    }
    
}