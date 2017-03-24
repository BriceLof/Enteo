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
        
        $from = "audrey.azoulay@entheor.com";
        $to =  $beneficiaire->getEmailConso();
        $cc = array($consultant->getEmail());
        $bcc = array(
            "support@iciformation.fr" => "Support",
            "b.lof@iciformation.fr" => "Brice Lof",
            "f.azoulay@entheor.com" => "Franck Azoulay", 
            "ph.rouzaud@iciformation.fr" => "Philippe Rouzaud",
            "christine.clement@entheor.com" => "Christine Clement",
            "audrey.azoulay@entheor.com" => "Audrey Azoulay");

        if($typeRdv == "presenciel")
            $subject = "[IMPORTANT] Votre rendez-vous VAE avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())." le ".$dateRdv->format('d/m/Y')." ".$dateRdv->format('H')."h".$dateRdv->format('i');
        else
            $subject = "[IMPORTANT] Votre dossier VAE : Confirmation de RDV téléphonique le ".$dateRdv->format('d/m/Y')." avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom());
        
		
		$Jour = array("Sunday" => "Dimanche", "Monday" => "Lundi", "Tuesday" => "Mardi" , "Wednesday" => "Mercredi" , "Thursday" => "Jeudi" , "Friday" => "Vendredi" ,"Saturday" => "Samedi");
        $Mois = array("January" => "Janvier", "February" => "Février", "March" => "Mars", "April" => "Avril", "May" => "Mai", "June" => "Juin", "July" => "Juillet", "August" => "Août", "September" => "Septembre", "October" => "Octobre", "November" => "Novembre", "December" => "Décembre");
		
		$observation = "";
		if(!is_null($rdv->getBureau()->getObservation())) 
			$observation = "Observations : ".$rdv->getBureau()->getObservation();
		
        if($typeRdv == "presenciel"){
            $message = ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getNomConso()).", <br><br>"
                . "Suite à notre conversation téléphonique ce jour, je vous confirme votre rendez-vous le <b>".$Jour[$dateRdv->format('l')]." ".$dateRdv->format('j')." ".$Mois[$dateRdv->format('F')]." à ".$dateRdv->format('H')."h".$dateRdv->format('i')."</b>
                dans nos locaux : 
				<table style='margin-top:-50px;'>
					<tr>
						<td><u>Votre consultant : </u></td>
						<td style='display:block;margin-top:64px;margin-left:27px;'>
							<b>".ucfirst($consultant->getCivilite())." ".ucfirst($consultant->getPrenom())." ".strtoupper($consultant->getNom())."</b><br>
							Cabinet ENTHEOR <br>
							Tél: ".$consultant->getTel1()."<br>
							Email : ".$consultant->getEmail()."
						</td>
					</tr>
					<tr>
						<td><u>Lieu du rendez-vous : </u></td>
						<td style='display:block;margin-top:63px;margin-left:26px;'>
							".$rdv->getBureau()->getNombureau()."<br>
							".$rdv->getBureau()->getAdresse()."<br>".
							$rdv->getBureau()->getVille()->getCp()." ".$rdv->getBureau()->getVille()->getNom()."<br>".
							$observation."
						</td>
					</tr>
				</table>
				<br><br>
                Merci de vous munir <u>impérativement</u> de :<br>";
        }else{
            $message = $beneficiaire->getCiviliteConso()." ".$beneficiaire->getNomConso().", <br><br>"
                . "Suite à notre échange, je vous confirme votre rendez-vous téléphonique avec ".$consultant->getCivilite()." ".$consultant->getPrenom()." ".$consultant->getNom()."<br><br><b>".
                $consultant->getCivilite()." ".$consultant->getNom()." <u>attendra votre appel</u> le ".$dateRdv->format('l')." ".$dateRdv->format('j')." ".$dateRdv->format('F')." à <u>".$dateRdv->format('H')."h".$dateRdv->format('i')." précises<u></b>
                    au numéro suivant : <b>06 91 85 84 28</b><br><br>
                    Pour ce rendez-vous téléphonique, merci de vous munir <u>impérativement</u> de :<br>";
        }
		
		
		$message.=  "
			<div style='margin-left:20px;'>
				- <b>CV détaillé</b> (par compétences)<br>
				- <b>Votre attestation du compte DIF/CPF</b> (à demander à votre employeur)<br>";
				
		if($beneficiaire->getCsp() != "demandeur d'emploi" && $beneficiaire->getCsp() != "chef d'entreprise/PL")
			$message.=  "
				- <b>Le nom de l'OPCA</b> ( organisme financeur) à demander à votre employeur <br>
				- <b>Votre dernier bulletin de paie</b> ";	

			
        $message.=  "
			</div>
			<br><br>

           <div style='padding:15px;border:1px solid;text-align:center;'><b>En cas d'empêchement : nous prévenir au moins 24 heures avant votre rendez-vous.</b></div>
			<br><br>

            Au plaisir de vous accompagner dans votre projet.<br><br>
            Bien Cordialement. <br><br>
            
            --<br>
            Audrey AZOULAY<br>
            ENTHEOR<br>
            <a href='mailto:audrey.azoulay@entheor.com'>audrey.azoulay@entheor.com</a><br>
            06.89.33.87.83";

        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message
        ));

        return $this->sendMessage($from, $to,null, null, $bcc, $subject, $body);
    }
    
    
}