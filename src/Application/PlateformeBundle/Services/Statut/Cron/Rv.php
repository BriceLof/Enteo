<?php

namespace Application\PlateformeBundle\Services\Statut\Cron;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;

class Rv extends \Application\PlateformeBundle\Services\Mailer
{ 
    // Alerte à +1h de l'heure du démarrage du rdv dans l'agenda (entity historique)
    public function alerteSuiteRvAgenda()
    {
        $histoRepo = $this->em->getRepository("ApplicationPlateformeBundle:Historique");
        
        $today = new \DateTime();
        $eventAgenda = $histoRepo->findEventByDate($today->format('Y-m-d'));
        //var_dump($today);
        foreach($eventAgenda as $event)
        {
            $dateHeureDebut = $event->getDateDebut();
            $heureDebut = $event->getDateDebut()->format('H:i:s');
            //var_dump($dateHeureDebut);
            // si l'heure actuel est au moins à 1h de + de l'heure de démarrage du rdv et que la cron n'a pas déjà envoyé un email, envoi de mail 
            if($today >= $dateHeureDebut->add(new \DateInterval('PT1H')) && $dateHeureDebut->add(new \DateInterval('PT1H15M')) > $today)
            {
                $beneficiaire = $event->getBeneficiaire();
                $consultant = $event->getConsultant();
                
                $subject = "Comment s'est passé votre rendez-vous avec ".ucfirst($beneficiaire->getPrenomConso())." ".ucfirst($beneficiaire->getNomConso())." ?";
                $from = "christine.clement@entheor.com";
                $to = $consultant->getEmail();
                $cc = array();
                $bcc = array(
                    "support@iciformation.fr" => "Support",
                    "b.lof@iciformation.fr" => "Brice Lof",
                    "f.azoulay@entheor.com" => "Franck Azoulay", 
                    "ph.rouzaud@iciformation.fr" => "Philippe Rouzaud",
                    "n.ranaivoson@iciformation.fr" => "Ndremifidy Ranaivoson",
                    "christine.clement@entheor.com" => "Christine Clement",
                    "virginie.hiairrassary@entheor.com" => "Virginie Hiairrassary");
						
                if($beneficiaire->getCiviliteConso() == "mme")
                    $cher = "Chère";
                else
                    $cher = "Cher";
					
                $message = $cher." ".$consultant->getPrenom().", <br><br> 
                    Vous venez de recevoir en rendez-vous de positionnement <b>".$beneficiaire->getCiviliteConso()." ".ucfirst($beneficiaire->getPrenomConso())." ".ucfirst($beneficiaire->getNomConso())."</b>.<br><br>"
                    . "<b>Je vous remercie de bien vouloir mettre à jour les informations suivantes sur <a href='http://dev.application.entheor.com/web/beneficiaire/show/".$beneficiaire->getId()."'>ENTHEO</a> :</b><br>"
                    . "- Statut du bénéficiaire à l'issue du RV (positif, négatif, indécis, à reporter...)<br>
                       - Compléter les informations clés du bénéficiaire : Coordonnées, CSP, type de Contrat, n° de sécu, date de naissance, informations employeur, OPCA... <br><br>
                       
                    <u>Ces informations sont requises</u> pour monter le dossier de financement et vous permettre de démarrer au plus vite la prestation d'accompagnement.<br><br>
                    
                    Bien Cordialement,<br><br> 
                    
                    Christine Clément<br>
                    <a href='mailto:christine.clement@entheor.com'>christine.clement@entheor.com</a><br>
                    06 81 84 85 24";
                
                $template = "@Apb/Alert/Mail/mailDefault.html.twig";
                $body = $this->templating->render($template, array(
                    'sujet' => $subject ,
                    'message' => $message
                ));
                
                $this->sendMessage($from, $to, $cc, $bcc, $subject, $body);
                //var_dump("mail envoye");
            }
        }
		//exit;
    }
    
    public function alerteSuiteRv1()
    {
        $newsRepo = $this->em->getRepository("ApplicationPlateformeBundle:News");
        // Récupération des news qui sont au statut RV1 (id 3) 
        $listeRv = $newsRepo->getNewsByStatut();

        foreach($listeRv as $rv)
        {   
            $beneficiaireID = $rv->getBeneficiaire()->getId();
            // beneficiaire ayant fini le RV1 
            $nextStep = $newsRepo->findOneBy(array('beneficiaire' => $beneficiaireID, 'statut' => '4'));
            
            var_dump("beneficiaire ID : ".$beneficiaireID." | RV2 : ".count($nextStep));

            // envoi email en fonction du temps passé entre la RV1 et la date du jour 
            if(is_null($nextStep))
            {
                $dateRv = $rv->getDateHeure();
                $dateCurrent = new \DateTime();
                
                $dateMoreOneDay = $dateRv->add(new \DateInterval('P1D'))->format('Y-m-d');
                $dateMoreTwoDay = $dateRv->add(new \DateInterval('P2D'))->format('Y-m-d');
                $dateMoreThreeDay = $dateRv->add(new \DateInterval('P3D'))->format('Y-m-d');
                
                $template = "@Apb/Alert/Mail/mailDefault.html.twig";
                
                $emailConsultant = $rv->getBeneficiaire()->getConsultant()->getEmail();
                
                if($dateMoreOneDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 1 à J+1
                    var_dump("j+1");
                    $subject = "Rappel J+1 : Post RV1 ";
                    $to = $emailConsultant; 
                }
                elseif($dateMoreTwoDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 2 à J+3
                    $subject = "Rappel J+3 : Post RV1 ";
                    $to = $emailConsultant;
                    $cc = array(
                            "support@iciformation.fr" => "Support",
                            "b.lof@iciformation.fr" => "Brice");
                }
                elseif($dateMoreThreeDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 3 à J+7
                    $subject = "Rappel J+7 [URGENT] : Post RV1 ";
                    $to = $emailConsultant;
                    $cc = array(
                            "support@iciformation.fr" => "Support",
                            "b.lof@iciformation.fr" => "Brice");
                }
                
                $message = "Votre bénéficiaire <b>".$rv->getBeneficiaire()->getCiviliteConso()." ".$rv->getBeneficiaire()->getNomConso()." ".$rv->getBeneficiaire()->getPrenomConso()." "
                        . "</b> a fait son RV1 le <b>".$rv->getDateHeure()->format('d/m/Y')."</b> .<br>
                          Il est en attente d'une mise à jour de son statut.";
                
                $body = $this->templating->render($template, array(
                    'consultant' => $rv->getBeneficiaire()->getConsultant(), 
                    'beneficiaire' => $rv->getBeneficiaire(),
                    'sujet' => $subject ,
                    'message' => $message
                ));
                
                $this->sendMessage($this->from, $to, $cc,null, $subject, $body);

                return "mail envoyé";
            }  
        }  
    }

    public function mailRvRealise(Beneficiaire $beneficiaire, News $lastNews){
        $from = "christine.clement@entheor.com";
        $subject = "Nouveau dossier bénéficiaire ". $beneficiaire->getPrenomConso()." ". $beneficiaire->getNomConso() ." à établir";
        $template = '@Apb/Alert/Mail/mailRvRealise.html.twig';
        $to =  "resp.administratif@entheor.com";
        $cc = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "virginie.hiairrassary@entheor.com" => "Virginie HIAIRRASSARY",
            "n.ranaivoson@iciformation.fr" => "Ndremifidy Ranaivoson",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clement@entheor.com" => "Christine Clement"
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'lastNews' => $lastNews,
        ));
//        $this->sendMessage($from,$to,$cc,null,$subject,$body);
        $this->sendMessage($from,"n.ranaivoson@iciformation.fr",null,null,$subject,$body);
    }

    public function firstMailRvFicheNonMaj(Beneficiaire $beneficiaire){
        $from = "christine.clement@entheor.com";
        $subject = "[URGENT] Mise à Jour de la fiche de ". ucfirst(strtolower($beneficiaire->getPrenomConso()))." ". ucfirst(strtolower($beneficiaire->getNomConso())) ." à établir";
        $template = '@Apb/Alert/Mail/firstMailRvFicheNonMaj.html.twig';
        $to = $beneficiaire->getConsultant()->getEmail();
        $cc = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "resp.administratif@entheor.com" => "Responsable Administratif",
            "n.ranaivoson@iciformation.fr" => "Ndremifidy Ranaivoson",
            "virginie.hiairrassary@entheor.com" => "Virginie HIAIRRASSARY",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clement@entheor.com" => "Christine Clement"
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
        ));
//        $this->sendMessage($from,$to,$cc,null,$subject,$body);
        $this->sendMessage($from,"n.ranaivoson@iciformation.fr",null,null,$subject,$body);
    }

    public function secondMailRvFicheNonMaj(Beneficiaire $beneficiaire){
        $from = "christine.clement@entheor.com";
        $subject = "[DERNIER RAPPEL] Mise à Jour de la fiche de ". ucfirst(strtolower($beneficiaire->getPrenomConso()))." ". ucfirst(strtolower($beneficiaire->getNomConso())) ." à établir";
        $template = '@Apb/Alert/Mail/secondMailRvFicheNonMaj.html.twig';
        $to = $beneficiaire->getConsultant()->getEmail();
        $cc = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "resp.administratif@entheor.com" => "Responsable Administratif",
            "n.ranaivoson@iciformation.fr" => "Ndremifidy Ranaivoson",
            "virginie.hiairrassary@entheor.com" => "Virginie HIAIRRASSARY",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clement@entheor.com" => "Christine Clement"
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
        ));
//        $this->sendMessage($from,$to,$cc,null,$subject,$body);
        $this->sendMessage($from,"n.ranaivoson@iciformation.fr",null,null,$subject,$body);
    }
}
?>