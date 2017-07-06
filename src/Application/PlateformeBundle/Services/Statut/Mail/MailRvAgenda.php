<?php
namespace Application\PlateformeBundle\Services\Statut\Mail;

class MailRvAgenda extends \Application\PlateformeBundle\Services\Mailer
{
    // Envoi un mail au beneficiaire après l'ajout/modification/suppression d'un rdv dans l'agenda par le consultant
    // alerteRdvAgenda(), le 3e param sert à savoir si c'est un mail après une modification de rdv 
    public function alerteRdvAgenda($beneficiaire, $rdv, $old_rdv = null )
    {   
		$consultant = $beneficiaire->getConsultant();
        $dateRdv = $rdv->getDateDebut();
        $typeRdv = $rdv->getTypeRdv();
		
        $from = "audrey.azoulay@entheor.com";
        $to =  $beneficiaire->getEmailConso();
        $cc = array($consultant->getEmail());
        $bcc = array(
            "support.informatique@entheor.com" => "Support",
            "f.azoulay@entheor.com" => "Franck Azoulay", 
            "ph.rouzaud@iciformation.fr" => "Philippe Rouzaud",
            "christine.clement@entheor.com" => "Christine Clement",
            "audrey.azoulay@entheor.com" => "Audrey Azoulay");

        if($typeRdv == "presenciel" || $typeRdv == "presentiel")
        {
            if($rdv->getSummary() == "RV1")
                $ref = "1-a";
            else
                $ref = "1-d";
            
            if(!is_null($old_rdv)) $ref = "1-e";
                
            $subject = "[IMPORTANT] Votre rendez-vous VAE avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())." le ".$dateRdv->format('d/m/Y')." ".$dateRdv->format('H')."h".$dateRdv->format('i');
        }
        else{
            if($rdv->getSummary() == "RV1")
                $ref = "1-b";
            else
                $ref = "1-c";
            
            if(!is_null($old_rdv)) $ref = "1-f";
            
            $subject = "[IMPORTANT] Votre dossier VAE : Confirmation de RDV téléphonique le ".$dateRdv->format('d/m/Y')." avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom());
        }
		
        $Jour = array("Sunday" => "Dimanche", "Monday" => "Lundi", "Tuesday" => "Mardi" , "Wednesday" => "Mercredi" , "Thursday" => "Jeudi" , "Friday" => "Vendredi" ,"Saturday" => "Samedi");
        $Mois = array("January" => "Janvier", "February" => "Février", "March" => "Mars", "April" => "Avril", "May" => "Mai", "June" => "Juin", "July" => "Juillet", "August" => "Août", "September" => "Septembre", "October" => "Octobre", "November" => "Novembre", "December" => "Décembre");
		
        $acces = "";
        if(!is_null($rdv->getBureau()))
        {
            if(!is_null($rdv->getBureau()->getAcces())) 		$acces = "Accès : ".$rdv->getBureau()->getAcces();
        }			
	
        $rv = $rdv->getSummary();
        switch($rv)
        {
            case 'RV2': 
                $sentenceByRv =  "pour un rendez-vous de pré-positionnement";
            break;
            case 'RV Livret1': 
                $sentenceByRv =  "pour travailler sur votre Livret 1";
            break;
            case 'RV Livret2': 
                $sentenceByRv =  "pour travailler sur votre Livret 2";
            break;
            case 'RV Preparation jury': 
                $sentenceByRv =  "pour préparer votre passage devant le Jury VAE";
            break;
        
            default:
                $sentenceByRv =  "pour ".$rv;
        }
        
        $message="";
        if($typeRdv == "presenciel" || $typeRdv == "presentiel"){ 
            $message .= ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getNomConso()).", <br><br>";
            if(!is_null($old_rdv))
                $message .= "Votre rendez-vous initialement prévu le ".$old_rdv->format('d/m/Y à H:i')." a été reporté au ";
            else
                $message .= "Je vous confirme votre rendez-vous le ";
            
            $message .= "<b>".$Jour[$dateRdv->format('l')]." ".$dateRdv->format('j')." ".$Mois[$dateRdv->format('F')]." à ".$dateRdv->format('H')."h".$dateRdv->format('i')."</b> ".$sentenceByRv.". 
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
							$acces."  
						</td>
					</tr>
				</table>";
	    	if($rdv->getSummary() == "RV1" || $rdv->getSummary() == "RV2"){ 		
                $message .= "<br><br>Merci de vous munir <u>impérativement</u> de :<br>";
            }
        }else{
        	$message .= ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getNomConso()).", <br><br>";
            if(!is_null($old_rdv))
                $message .= "Votre rendez-vous initialement prévu le ".$old_rdv->format('d/m/Y à H:i')." a été modifié.<br><br>";
                 
            $message .= "Je vous confirme votre rendez-vous téléphonique avec ".ucfirst($consultant->getCivilite())." ".ucfirst($consultant->getPrenom())." ".strtoupper($consultant->getNom())." ".$sentenceByRv.".<br><br><b>".
                ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())." <u>attendra votre appel</u> le ".$Jour[$dateRdv->format('l')]." ".$dateRdv->format('j')." ".$Mois[$dateRdv->format('F')]." à <u>".$dateRdv->format('H')."h".$dateRdv->format('i')." précise</u></b>
                    au numéro suivant : <b>".$consultant->getTel1()."</b>";
            if($rdv->getSummary() == "RV1" || $rdv->getSummary() == "RV2"){   
                $message .= "<br><br>Pour ce rendez-vous téléphonique, merci de vous munir <u>impérativement</u> de :<br>";
            }
        }
		
	if($rdv->getSummary() == "RV1" || $rdv->getSummary() == "RV2"){  	
            $message.=  "
                    <div style='margin-left:20px;'>
                            - <a href='https://www.entheor.com/files/maquette_cv.docx' alt='CV par compétences vide'><b>CV détaillé</b> (par compétences)</a><br>
                            - <b>Votre attestation du compte DIF/CPF</b> (à demander à votre employeur)<br>";

            if($beneficiaire->getCsp() != "demandeur d'emploi" && $beneficiaire->getCsp() != "chef d'entreprise/PL"){
                    $message.=  "
                            - <b>Le nom de l'OPCA</b> ( organisme financeur) à demander à votre employeur <br>
                            - <b>Votre dernier bulletin de paie</b> ";	
            }
            $message.=  "</div>";
        }
        
        $message.=  "
           <br><br><div style='padding:15px;border:1px solid;text-align:center;'><b>En cas d'empêchement : nous prévenir au moins 24 heures avant votre rendez-vous.</b></div>
			<br><br>
            Bien Cordialement. <br>";

        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message,
            'reference' => $ref
        ));

        return $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

    /**
     * l'email qui sera envoyé lors de la suppression d'un rendez vous
     *
     * @param $beneficiaire
     * @param $old_rdv (date de début du RDV)
     * @param $type (type de RDV)
     * @return null
     */
    public function alerteRdvAgendaSupprime($beneficiaire, $old_rdv, $type)
    {
    	$consultant = $beneficiaire->getConsultant();
		$subject = "Annulation de votre rendez-vous VAE du ".$old_rdv->format('d/m/Y');

        //false si le type RDV n'est ni RV1 ni RV2
        $bool = false;

        //expediteur RV1 RV2
        if ($type == 'RV1' || $type == 'RV2'){
            $from = "audrey.azoulay@entheor.com";
            $cc = array($consultant->getEmail());
            $bool = true;
            $reference = '7a' ;
        }else{
            $from = $consultant->getEmail();
            $cc = null;
            $reference = '7b';
            if ($old_rdv > new \DateTime() && $old_rdv < (new \DateTime())->modify('+1 day')){
                $reference = '7c';
            }
        }

        $to =  $beneficiaire->getEmailConso();
		
		$adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $listeAdministrateurs = array();
        foreach($adminitrateurs as $admin){ $listeAdministrateurs[] = $admin->getEmail(); }
		array_push($listeAdministrateurs, "audrey.azoulay@entheor.com", "support.informatique@entheor.com");
        $bcc = $listeAdministrateurs;
            
        $template = "@Apb/Alert/Mail/supprimerRdv.html.twig";
            
        $body = $this->templating->render($template, array(
            'bool' => $bool,
            'sujet' => $subject ,
            'consultant' => $consultant,
            'reference' => $reference,
            'old_rdv' => $old_rdv
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);

		return null;
	} 
}