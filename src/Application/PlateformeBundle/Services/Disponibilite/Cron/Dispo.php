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
          
            $subject = "Comment s'est passé votre rendez-vous avec";
            $from = "christine.clement@entheor.com";
            $ref = "5";
            //$to = "b.lof@iciformation.fr";
            $cc = "";
            $bcc = array(
                "support@iciformation.fr" => "Support",
                "f.azoulay@entheor.com" => "Franck Azoulay", 
                "ph.rouzaud@iciformation.fr" => "Philippe Rouzaud",
                "christine.clement@entheor.com" => "Christine Clement",
                "virginie.hiairrassary@entheor.com" => "Virginie Hiairrassary");
            $arrayConsultant = array();
            
            // Récupération des ids des consultant concernés 
            foreach($disponibilites as $disponibilite)
            {
                $consultant = $disponibilite->getConsultant();
                // Si on a deja des lignes pour ce consultant
                if(!in_array($consultant->getId(), $arrayConsultant))
                    $arrayConsultant[] = $consultant->getId();  
            }
            
            $message = "Bonjour, <br><br> 1) Liste des prochaine disponibilités à la journée inscrites par les Consultants VAE : <br>";
            // Disponibilité par consultant
            for($i = 0; $i < count($arrayConsultant); $i++)
            {
                $dispoConsultant = $this->em->getRepository("ApplicationPlateformeBundle:Disponibilites")->findDispoFromDateDuConsultant($date, $arrayConsultant[$i]);
                if(count($dispoConsultant) > 0){
                    $message .= "<p style='margin-left:10px;'><b>&bull; ".ucfirst($dispoConsultant[0]->getConsultant()->getPrenom())." ".strtoupper($dispoConsultant[0]->getConsultant()->getNom())."</b></p>";
                    $message .= "<table style='border:1px solid;border-collapse: collapse;'>";
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
                       
                        $message .= "<tr><td style='border:1px solid;padding:5px'>".$dateDispo."</td>"
                                . "<td style='border:1px solid;padding:5px' >".$ville->getNom()."</td>"
                                . "<td style='border:1px solid;padding:5px' >".$creneau." créneaux dispo.</td></tr>";
                        
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
            //var_dump($arrayConsultant);   
            //var_dump($nombreRdvConsultant);     
            echo ($message);
            
            exit;
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