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

    public function alerteAttenteAccord($beneficiaires){

        $adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $gestionnaires = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_GESTION");
        $listeAdministrateurs = array();
        $listeGestionnaires = array();
        foreach($adminitrateurs as $admin){ $listeAdministrateurs[] = $admin->getEmail(); }
        foreach($gestionnaires as $gestionnaire){ $listeGestionnaires[] = $gestionnaire->getEmail(); }

        $ref = 4;
        $from = "christine.clement@entheor.com";
        $replyTo = "christine.clement@entheor.com";
        $subject = "Financement - Attente accord ";
        $template = '@Apb/Alert/Mail/alerteAttenteAccord.html.twig';
        $to = $listeGestionnaires;
        $cc = $listeAdministrateurs;
        $bcc = "support.informatique@entheor.com";
        $body = $this->templating->render($template, array(
            'beneficiaires' => $beneficiaires,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }
    
}