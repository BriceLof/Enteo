<?php

namespace Application\PlateformeBundle\Services\Disponibilite\Cron;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;

class Dispo extends \Application\PlateformeBundle\Services\Mailer
{ 
    // Envoyer aux Commerciaux téléphone, les listes des prochaines dispo de consultants
    public function recapDispo()
    {
        $date = date("Y-m-d");
        $disponibilites = $this->em->getRepository("ApplicationPlateformeBundle:Disponibilites")->findDispoFromDate($date);
        
        $Jour = array("Sunday" => "Dimanche", "Monday" => "Lundi", "Tuesday" => "Mardi" , "Wednesday" => "Mercredi" , "Thursday" => "Jeudi" , "Friday" => "Vendredi" ,"Saturday" => "Samedi");
        $Mois = array("January" => "Janvier", "February" => "Février", "March" => "Mars", "April" => "Avril", "May" => "Mai", "June" => "Juin", "July" => "Juillet", "August" => "Août", "September" => "Septembre", "October" => "Octobre", "November" => "Novembre", "December" => "Décembre");

        if(count($disponibilites) > 0){
            $adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
            $commerciaux = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_COMMERCIAL");
            $listeAdministrateurs = array();
            $listeCommerciaux = array();

            foreach($adminitrateurs as $admin){ $listeAdministrateurs[$admin->getEmail()] = $admin->getEmail(); }
            foreach($commerciaux as $commercial){ $listeCommerciaux[$commercial->getEmail()] = $commercial->getEmail(); }
            
            $subject = "Récapitulatif des disponibilités des consultants";
            $from = $this->from;
            $ref = "5";
            $to = $listeCommerciaux ;

            $cc = $listeAdministrateurs;

            $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");


            $arrayConsultant = array();
            
            // Récupération des ids des consultant concernés 
            foreach($disponibilites as $disponibilite)
            {
                $consultant = $disponibilite->getConsultant();
                // Si on a deja des lignes pour ce consultant
                if(!in_array($consultant->getId(), $arrayConsultant))
                    $arrayConsultant[] = (int) $consultant->getId();  
            }
            
            $message = "Bonjour, <br><br> 1) Liste des prochaines disponibilités à la journée inscrites par les Consultants VAE : <br>";
            // Disponibilité par consultant
            for($i = 0; $i < count($arrayConsultant); $i++)
            {
                $dispoConsultant = $this->em->getRepository("ApplicationPlateformeBundle:Disponibilites")->findDispoFromDateDuConsultant($date, $arrayConsultant[$i]);
                if(count($dispoConsultant) > 0){
                    $message .= "<p style='margin-left:10px;'><b>&bull; ".ucfirst($dispoConsultant[0]->getConsultant()->getPrenom())." ".strtoupper($dispoConsultant[0]->getConsultant()->getNom())."</b></p>";
                    $message .= "<table style='margin-left:15px;margin-top:-10px'>";
                    foreach($dispoConsultant as $dispo)
                    {
                        $dateDebut = $dispo->getDateDebuts();
                        $dateFin = $dispo->getDateFins();
                        $dateDispo = $Jour[$dateDebut->format('l')]." ".$dateDebut->format('j')." ".$Mois[$dateDebut->format('F')];
                        $consultant = $dispo->getConsultant();
                        $ville = $dispo->getVille();
                        $heureDebut = $dateDebut->format('H:i');
                        $heureFin = $dateFin->format('H:i');
                        $nombreRdvConsultant = $this->em->getRepository("ApplicationPlateformeBundle:Historique")->findEventByDateAndConsultant($dateDebut->format('Y-m-d'), $arrayConsultant[$i]);
                       
                        $creneau =  ($heureFin - $heureDebut) -  count($nombreRdvConsultant) ; 
						if($creneau < 0) $creneau = 0;
						
                        $message .= "<tr><td style='padding:3px'>- ".$dateDispo.",</td>";
						
                        if(!is_null($ville))
							$message .= "<td style='padding:3px' >".$ville->getNom().",</td>";
						
                        $message .= "<td style='padding:3px' >".$creneau." créneaux dispo.</td></tr>";   
                    }
                    $message .= "</table>";  
                }  
            }
            
            $message .= "<br><br>2) Consultants n'ayant pas inscrit de journées de disponibilités : <br>";

            $nombreRdvConsultant = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeAndExclude($arrayConsultant, "ROLE_CONSULTANT");

            if(count($nombreRdvConsultant) > 0)
            {
                foreach($nombreRdvConsultant as $rdvConsultant)
                {	
                    $message .= "<p style='margin-left:10px;'><b>&bull; ".ucfirst($rdvConsultant->getPrenom())." ".strtoupper($rdvConsultant->getNom())."</b></p>";  
                }
            }

            $template = "@Apb/Alert/Mail/mailDefault.html.twig";
            $body = $this->templating->render($template, array(
                'sujet' => $subject ,
                'message' => $message,
                'reference' => $ref
            ));

            $this->sendMessage($from, $to,null , $cc, $bcc, $subject, $body);
        }   
    }

	public function recapConsultantSansDispo()
    {
    	$date = date("Y-m-d");
        $disponibilites = $this->em->getRepository("ApplicationPlateformeBundle:Disponibilites")->findDispoFromDate($date);
		
		$arrayConsultant = array();
		// Récupération des ids des consultant qui ont mis des dispo 
        foreach($disponibilites as $disponibilite)
        {
            $consultant = $disponibilite->getConsultant();
            // Si on a deja des lignes pour ce consultant
            if(!in_array($consultant->getId(), $arrayConsultant))
                $arrayConsultant[] = (int) $consultant->getId();  
        }
		$consultantSansDispo = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeAndExclude($arrayConsultant, "ROLE_CONSULTANT");

		$subject = "Aucune disponibilté enregistrée";
        $from = $this->from;
        $ref = "5-b";
		
		foreach($consultantSansDispo as $consultantSsDispo){	
			$to = array("email_adress" => $consultantSsDispo->getEmail(), "alias" => $consultantSsDispo->getEmail());
			$message = ucfirst($consultantSsDispo->getPrenom()).", <br><br>
			
						Vous n'avez actuellement aucune disponibilité enregistrée dans votre Agenda sur la plateforme ENTHEO.<br><br>
						Ces disponibilités facilitent la prise de rendez-vous avec les Bénéficiaires de votre région.<br><br>
						Pour enregistrer, un créneau des disponibilités :
						<div style='margin-left:15px;'>
							1) Rendez-vous sur la plateforme ENTHEO, à la rubrique <b>'Agenda'</b><br>
							2) En haut à droite, cliquez sur le bouton :<br>
							<button style='
								color:white;background-color:#428bca;    
							    padding: 6px 12px;
							    font-size: 14px;
							    font-weight: 400;
							    text-align: center;
							    user-select: none;
							    border: 1px solid transparent;
							    border-radius: 4px;'>
							    	Vos disponibilités
							</button><br><br>
							3) Renseignez le lieu et votre créneau de disponibilités :<br>
							<img src='https://appli.entheor.com/web/images/mail/img_mail_tuto_dispo.JPG' /> 
						</div>";
						
			$cc = array("email_adress" => "f.azoulay@entheor.com", "alias" => "f.azoulay@entheor.com");

            $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");
			$template = "@Apb/Alert/Mail/mailDefault.html.twig";
	        $body = $this->templating->render($template, array(
	            'sujet' => $subject ,
	            'message' => $message,
	            'reference' => $ref
	        ));
	
	        $this->sendMessage($from, $to,null , $cc, $bcc, $subject, $body);
	
		}

        
	}
}
?>