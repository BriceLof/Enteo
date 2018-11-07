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

        $sms = array('recipient' => null);

        if($rdv->getSummary() == "RV1" || $rdv->getSummary() == "RV2") {
            $from = array("email" => "audrey.azoulay@entheor.com", "name" => "Audrey Azoulay");
        }else{
            $from = array("email" => $consultant->getEmail(), "name" => $consultant->getEmail());
        }
		
		$dateJ = new \DateTime('now');  
        if($dateRdv >= $dateJ){
            $to = array(array("email" => $beneficiaire->getEmailConso(), "name" => $beneficiaire->getEmailConso()));

            $telephone = $beneficiaire->getTelConso();
            // Si c'est un telephone mobile francais
            if(substr($telephone, 0, 2) == '06' || substr($telephone, 0, 2) == '07'){
                $telephone = "33".substr($telephone, 1);
                $sms['recipient'] = $telephone;
            }

        }else{
            $to = array(array("email" => 'support.informatique@entheor.com', "name" => 'Support'));
        }

        $cc = array(array("email" => $consultant->getEmail(), "name" => $consultant->getEmail()));

        $bcc = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "audrey.azoulay@entheor.com", "name" => "Audrey Azoulay"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier"),
            array("email" => "support.informatique@entheor.com", "name" => "Support"),
        );


        if($typeRdv == "presenciel" || $typeRdv == "presentiel")
        {
            if($rdv->getSummary() == "RV1")
                $ref = "1-a";
            else
                $ref = "1-d";
            
            if(!is_null($old_rdv)) $ref = "1-e";
                
            $subject = "[IMPORTANT] Votre rendez-vous VAE avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())." le ".$dateRdv->format('d/m/Y')." à ".$dateRdv->format('H')."h".$dateRdv->format('i');
            $sms['subject'] = "[IMPORTANT] RDV VAE confirmé avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())."\nLe ".$dateRdv->format('d/m/Y')." à ".$dateRdv->format('H')."h".$dateRdv->format('i')."\n";
        }
        else{
            if($rdv->getSummary() == "RV1")
                $ref = "1-b";
            else
                $ref = "1-c";
            
            if(!is_null($old_rdv)) $ref = "1-f";
            
            $subject = "[IMPORTANT] Votre dossier VAE : Confirmation de RDV téléphonique le ".$dateRdv->format('d/m/Y')." avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom());
            $sms['subject'] = "[IMPORTANT] RDV VAE téléphonique confirmé avec ".ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())."\n";
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
                $message .= "<br><br>Merci de vous munir <u>impérativement</u> des copies de :<br>";
            }
        }else{
        	$message .= ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getNomConso()).", <br><br>";
            if(!is_null($old_rdv))
                $message .= "Votre rendez-vous initialement prévu le ".$old_rdv->format('d/m/Y à H:i')." a été modifié.<br><br>";
                 
            $message .= "Je vous confirme votre rendez-vous téléphonique avec ".ucfirst($consultant->getCivilite())." ".ucfirst($consultant->getPrenom())." ".strtoupper($consultant->getNom())." ".$sentenceByRv.".<br><br><b>".
                ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())." <u>attendra votre appel</u> le ".$Jour[$dateRdv->format('l')]." ".$dateRdv->format('j')." ".$Mois[$dateRdv->format('F')]." à <u>".$dateRdv->format('H')."h".$dateRdv->format('i')." précise</u></b>
                    au numéro suivant : <b>".$consultant->getTel1()."</b>";
            if($rdv->getSummary() == "RV1" || $rdv->getSummary() == "RV2"){   
                $message .= "<br><br>Pour ce rendez-vous téléphonique, merci de vous munir <u>impérativement</u> des copies de :<br>";
            }
        }
		
	if($rdv->getSummary() == "RV1" || $rdv->getSummary() == "RV2"){  	
            $message.=  "
                    <div style='margin-left:20px;'>
                            - <a href='https://www.entheor.com/files/maquette_cv.docx' alt='CV par compétences vide'><b>CV détaillé</b> (par compétences)</a><br>";

            if($beneficiaire->getCsp() != "demandeur d'emploi" && $beneficiaire->getCsp() != "chef d'entreprise/PL"){
                    $message.=  "
                            - <b>Le nom de l'OPCA</b> (organisme financeur) à demander à votre employeur <br>
                            - <b>Votre dernier bulletin de paie</b><br> ";
            }
            $message.=  "- <b>Votre attestation d'heures DIF</b> (qui vous a été remise par votre employeur en Déc. 2014 ou Janv. 2015 ou qui figure sur vos bulletins de paie de ces mois)</div>";
        }
        
        $message.=  "
           <br><br><div style='padding:15px;border:1px solid;text-align:center;'><b>En cas d'empêchement : nous prévenir au moins 24 heures avant votre rendez-vous.</b></div>
			<br><br>
            Bien Cordialement. <br>";

        $template = "@Apb/Alert/Mail/mailDefault.html.twig";


        $sms['content'] = $sms['subject']."Un email vous a été envoyé avec les détails\nInfo et annulation : 01 02 03 04 05";
        $sms['tag'] = "Prise de rdv";
        if(is_null($sms['recipient'])) $sms = null;


        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message,
            'reference' => $ref
        ));

        return $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, null, $sms);
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
            $from = array("email" => "contact@entheor.com", "name" => "contact@entheor.com");
            $cc = array(array("email" => $consultant->getEmail(), "name" => $consultant->getEmail()));
            $bool = true;
            $reference = '7a' ;
        }else{
            $from = array("email" => $consultant->getEmail(), "name" => $consultant->getEmail());
            $cc = null;
            $reference = '7b';
            if ($old_rdv > new \DateTime() && $old_rdv < (new \DateTime())->modify('+1 day')){
                $reference = '7c';
            }
        }

        $to = array(array("email" => $beneficiaire->getEmailConso(), "name" => $beneficiaire->getEmailConso()));
		
		$adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $listeAdministrateurs = array();
        foreach($adminitrateurs as $admin){
        	if($admin->getEmail() != "ph.rouzaud@iciformation.fr" && $admin->getEmail() != "christine.clement@entheor.com")
                array_push($listeAdministrateurs, array("email" => $admin->getEmail(), "name" => $admin->getEmail()));

		}
        array_push($listeAdministrateurs, array("email" => "audrey.azoulay@entheor.com", "name" => "audrey.azoulay@entheor.com"));
		
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