<?php
namespace Application\PlateformeBundle\Services\Statut\Mail;

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

        return $this->sendMessage($this->from, $to, $cc, $subject, $body);
    }
    
    
}