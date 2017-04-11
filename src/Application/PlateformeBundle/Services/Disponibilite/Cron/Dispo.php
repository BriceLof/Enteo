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
            $commerciaux = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_CONSULTANT");
            $listeAdministrateurs = array();
            $listeCommerciaux = array();

            foreach($adminitrateurs as $admin){ $listeAdministrateurs[] = $admin->getEmail(); }
            foreach($commerciaux as $commercial){ $listeCommerciaux[] = $commercial->getEmail(); }
            
            $subject = "Récapitulatif des disponibilités des consultants";
            $from = $this->from;
            $ref = "5";
            $to = $listeCommerciaux ;
            $to = array("b.lof@iciformation.fr" => "Brice",
                /*"f.azoulay@iciformation.fr" => "Franck",*/) ;
            $cc = $listeAdministrateurs;
            $cc = "";
            $bcc = array(
                "support@iciformation.fr" => "Support",
                );
            
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
                        $ville = $dispo->getBureau()->getVille();
                        
                        $heureDebut = $dateDebut->format('H:i');
                        $heureFin = $dateFin->format('H:i');
                        $nombreRdvConsultant = $this->em->getRepository("ApplicationPlateformeBundle:Historique")->findEventByDateAndConsultant($dateDebut->format('Y-m-d'), $arrayConsultant[$i]);
                       
                        $creneau =  ($heureFin - $heureDebut) -  count($nombreRdvConsultant) ; 
                       
                        $message .= "<tr><td style=padding:3px'>- ".$dateDispo.",</td>"
                                . "<td style='padding:3px' >".$ville->getNom().",</td>"
                                . "<td style='padding:3px' >".$creneau." créneaux dispo.</td></tr>";   
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
}
?>