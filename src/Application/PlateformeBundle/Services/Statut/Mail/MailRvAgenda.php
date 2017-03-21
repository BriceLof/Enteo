<?php
namespace Application\PlateformeBundle\Services\Statut\Mail;

class MailRvAgenda extends \Application\PlateformeBundle\Services\Mailer
{
    // Envoi un mail au beneficiaire après l'ajout/modification/suppression d'un rdv dans l'agenda par le consultant
    public function alerteRdvAgenda($beneficiaire, $rdv)
    {   
        $consultant = $beneficiaire->getConsultant();
        $dateRdv = $rdv->getDateDebut();
        $typeRdv = $rdv->getTypeRdv();
        
        $from = "a.galois@entheor.com";
        // $to =  $beneficiaire->getEmailConso();
		$to =  'f.azoulay@entheor.com';
        // $cc = array($consultant->getEmail());
        /*$bcc = array(
            "support@iciformation.fr" => "Support",
            "b.lof@iciformation.fr" => "Brice Lof",
            "f.azoulay@entheor.com" => "Franck Azoulay", 
            "ph.rouzaud@iciformation.fr" => "Philippe Rouzaud",
            "christine.clement@entheor.com" => "Christine Clement",
            "a.galois@entheor.com" => "Audrey Galois");*/
		$bcc = array(
            "support@iciformation.fr" => "Support",
            );
        
        if($typeRdv == "presenciel")
            $subject = "[IMPORTANT] Votre rendez-vous VAE avec ".$consultant->getCivilite()." ".$consultant->getNom()." le ".$dateRdv->format('d/m/Y')." ".$dateRdv->format('H')."h".$dateRdv->format('i');
        else
            $subject = "[IMPORTANT] Votre dossier VAE : Confirmation de RDV téléphonique le ".$dateRdv->format('d/m/Y')." avec ".$consultant->getCivilite()." ".$consultant->getNom();
        
        if($typeRdv == "presenciel"){
            $message = $beneficiaire->getCiviliteConso()." ".$beneficiaire->getNomConso().", <br><br>"
                . "Suite à notre conversation téléphonique ce jour, je vous confirme votre rendez-vous le <b>".$dateRdv->format('l')." ".$dateRdv->format('j')." ".$dateRdv->format('F')." à ".$dateRdv->format('H')."h".$dateRdv->format('i')."</b>
                dans nos locaux : <br><br><b>".
                $consultant->getCivilite()." ".$consultant->getPrenom()." ".$consultant->getNom()."</b><br>
                Cabinet ENTHEOR <br>
                Tél: 06 81 85 84 28 <br>
                ".$consultant->getEmail()."<br><br>
                <b>Adresse :</b><br>".
                $rdv->getBureau()->getAdresse()."<br>".
                $rdv->getBureau()->getVille()->getCp()." ".$rdv->getBureau()->getNombureau()."<br><br>
                Je vous remercie de venir <u>impérativement</u> avec :<br>";
        }else{
            $message = $beneficiaire->getCiviliteConso()." ".$beneficiaire->getNomConso().", <br><br>"
                . "Suite à notre échange, je vous confirme votre rendez-vous téléphonique avec ".$consultant->getCivilite()." ".$consultant->getPrenom()." ".$consultant->getNom()."<br><br><b>".
                $consultant->getCivilite()." ".$consultant->getNom()." <u>attendra votre appel</u> le ".$dateRdv->format('l')." ".$dateRdv->format('j')." ".$dateRdv->format('F')." à <u>".$dateRdv->format('H')."h".$dateRdv->format('i')." précises<u></b>
                    au numéro suivant : <b>06 91 85 84 28</b><br><br>
                    Pour ce rendez-vous téléphonique, je vous remercie de vous munir <u>impérativement</u>:<br>";
        }
        $message.=  "
            - <b>CV détaillé</b> (par compétences)<br>
            - <b>Votre attestation du compte DIF/CPF</b> (à demander à votre employeur)<br>
            - <b>Le nom de l'OPCA</b> ( organisme financeur) à demander à votre employeur [[ NUL si CSP bénéficiaire =/ de Demandeur Emploi et =/ de Chef Entreprise]]<br>
            - <b>Votre dernier bulletin de paie</b> [[ NUL si CSP bénéficiaire =/ de Demandeur Emploi et =/ de Chef Entreprise]]<br><br>

            <b>En cas d'empêchement, merci de bien vouloir nous prévenir au moins 24 heures avant votre rendez-vous.</b><br><br>

            Au plaisir de vous accompagner dans votre projet.<br><br>
            Bien Cordialement. <br><br>
            
            --<br>
            Audrey GALOIS<br>
            ENTHEOR<br>
            <a href='mailto:a.galois@entheor.com'>a.galois@entheor.com</a><br>
            06.89.33.87.83";

        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message
        ));

        return $this->sendMessage($from, $to,null, null, $bcc, $subject, $body);
    }
    
    
}