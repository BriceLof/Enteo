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
                
                $this->sendMessage($from, $to,null , $cc, $bcc, $subject, $body);
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
                
                $this->sendMessage($this->from, $to, null, $cc,null, $subject, $body);

                return "mail envoyé";
            }  
        }  
    }

    public function mailRvRealise(Beneficiaire $beneficiaire, News $lastNews){
        $from = "christine.clement@entheor.com";
        $replyTo = "christine.clement@entheor.com";
        $subject = "Nouveau dossier bénéficiaire ". $beneficiaire->getPrenomConso()." ". $beneficiaire->getNomConso() ." à établir";
        $template = '@Apb/Alert/Mail/mailRvRealise.html.twig';
        $to =  "resp.administratif@entheor.com";
        $cci = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "virginie.hiairrassary@entheor.com" => "Virginie HIAIRRASSARY",
            "n.ranaivoson@iciformation.fr" => "Ndremifidy Ranaivoson",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clement@entheor.com" => "Christine Clement"
        );
        $date = $lastNews->getDateHeure()->format('l')." ".$lastNews->getDateHeure()->format('j')." ".$lastNews->getDateHeure()->format('F')." ".$lastNews->getDateHeure()->format('Y');
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'lastNews' => $lastNews,
            'date' => $date,
        ));
//        $this->sendMessage($from,$to,$cc,null,$subject,$body);
        $this->sendMessage($from,"f.azoulay@entheor.com", $replyTo, null,null,$subject,$body);
    }

    public function firstMailRvFicheNonMaj(Beneficiaire $beneficiaire){
        $from = "christine.clement@entheor.com";
        $replyTo = "christine.clement@entheor.com";
        $subject = "[URGENT] Mise à Jour de la fiche de ". ucfirst(strtolower($beneficiaire->getPrenomConso()))." ". ucfirst(strtolower($beneficiaire->getNomConso())) ."";
        $template = '@Apb/Alert/Mail/firstMailRvFicheNonMaj.html.twig';
        $to = $beneficiaire->getConsultant()->getEmail();
        $cci = array(
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
        $this->sendMessage($from,"f.azoulay@entheor.com", $replyTo, null,null,$subject,$body);
    }

    public function secondMailRvFicheNonMaj(Beneficiaire $beneficiaire){
        $from = "christine.clement@entheor.com";
        $replyTo = "christine.clement@entheor.com";
        $subject = "[DERNIER RAPPEL] Mise à Jour de la fiche de ". ucfirst(strtolower($beneficiaire->getPrenomConso()))." ". ucfirst(strtolower($beneficiaire->getNomConso())) ."";
        $template = '@Apb/Alert/Mail/secondMailRvFicheNonMaj.html.twig';
        $to = $beneficiaire->getConsultant()->getEmail();
        $cci = array(
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
        $this->sendMessage($from,"f.azoulay@entheor.com", $replyTo,null,null,$subject,$body);
    }
    
    // Envoi un mail rappel au beneficiaire et lui signalant son rdv pour demain + un recap pour le consultant 
    public function alerteRappelRdvAgenda()
    {   
        $dateCurrent = new \DateTime("NOW");
        $dateMoreOneDay = $dateCurrent->add(new \DateInterval('P1D'))->format('Y-m-d');
        // cherche les rdv de demain
        $histoRepo = $this->em->getRepository("ApplicationPlateformeBundle:Historique")->findEventByDate($dateMoreOneDay);
        
        $Jour = array("Sunday" => "Dimanche", "Monday" => "Lundi", "Tuesday" => "Mardi" , "Wednesday" => "Mercredi" , "Thursday" => "Jeudi" , "Friday" => "Vendredi" ,"Saturday" => "Samedi");
        $Mois = array("January" => "Janvier", "February" => "Février", "March" => "Mars", "April" => "Avril", "May" => "Mai", "June" => "Juin", "July" => "Juillet", "August" => "Août", "September" => "Septembre", "October" => "Octobre", "November" => "Novembre", "December" => "Décembre");

        $tabConsultant = array();
        
        if(count($histoRepo) > 0){
            // Envoi d'un mail à chaque bénéficiaire
            foreach($histoRepo as $rdv)
            {
                $consultant = $rdv->getConsultant();
                $tabConsultant[] = $rdv->getConsultant()->getId();
                $dateRdv = $rdv->getDateDebut();
                $typeRdv = $rdv->getTypeRdv();

                $ref = "1-b";
                $from = "audrey.azoulay@entheor.com";
                $to =  $rdv->getBeneficiaire()->getEmailConso();
                $cc = "";
                $bcc = "support@iciformation.fr";
                
                if($typeRdv == "presenciel")
                    $subject = "[Rappel] Vous avez rendez-vous pour votre VAE demain à ".$dateRdv->format('H')."h".$dateRdv->format('i');
                else
                    $subject = "[Rappel] Vous avez un rendez-vous téléphonique pour votre VAE demain à ".$dateRdv->format('H')."h".$dateRdv->format('i');

                $acces = "";
                $commentaire = "";
                if(!is_null($rdv->getBureau()))
                {
                    if(!is_null($rdv->getBureau()->getAcces())) 		$acces = "Accès : ".$rdv->getBureau()->getAcces();
                    if(!is_null($rdv->getBureau()->getCommentaire())) 	$commentaire = "Commentaires : ".$rdv->getBureau()->getCommentaire();
                }

                if($typeRdv == "presenciel"){
                    $message = ucfirst($rdv->getBeneficiaire()->getCiviliteConso())." ".ucfirst($rdv->getBeneficiaire()->getNomConso()).", <br><br>"
                        . "Nous vous rappelons que vous avez rendez-vous demain <b>".$Jour[$dateRdv->format('l')]." ".$dateRdv->format('j')." ".$Mois[$dateRdv->format('F')]." à ".$dateRdv->format('H')."h".$dateRdv->format('i')."</b> : 
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
                                                                $acces."<br>".
                                                                $commentaire."    
                                                        </td>
                                                </tr>
                                        </table>
                                        <br><br>
                        Merci de vous munir <u>impérativement</u> de :<br>";
                }else{
                    $message = ucfirst($rdv->getBeneficiaire()->getCiviliteConso())." ".ucfirst($rdv->getBeneficiaire()->getNomConso()).", <br><br>"
                        . "Nous vous rappelons que vous avez un rendez-vous téléphonique demain avec ".ucfirst($consultant->getCivilite())." ".ucfirst($consultant->getPrenom())." ".strtoupper($consultant->getNom())."<br><br><b>".
                        ucfirst($consultant->getCivilite())." ".strtoupper($consultant->getNom())." <u>attendra votre appel</u> le ".$Jour[$dateRdv->format('l')]." ".$dateRdv->format('j')." ".$Mois[$dateRdv->format('F')]." à <u>".$dateRdv->format('H')."h".$dateRdv->format('i')." précises</u></b>
                            au numéro suivant : <b>06 91 85 84 28</b><br><br>
                            Pour ce rendez-vous téléphonique, merci de vous munir <u>impérativement</u> de :<br>";
                }

                $message.=  "
                        <div style='margin-left:20px;'>
                                - <a href='http://www.entheor.com/files/maquette_cv.docx' alt='CV par compétences vide'><b>CV détaillé</b> (par compétences)</a><br>
                                - <b>Votre attestation du compte DIF/CPF</b> (à demander à votre employeur)<br>";

                if($rdv->getBeneficiaire()->getCsp() != "demandeur d'emploi" && $rdv->getBeneficiaire()->getCsp() != "chef d'entreprise/PL")
                        $message.=  "
                                - <b>Le nom de l'OPCA</b> ( organisme financeur) à demander à votre employeur <br>
                                - <b>Votre dernier bulletin de paie</b> ";	
                        
                $message.=  "
                                </div>
                                <br><br>

                   <div style='padding:15px;border:1px solid;text-align:center;'><b>En cas d'empêchement : nous prévenir au 06.89.33.87.83</b></div>
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
                    'message' => $message,
                    'reference' => $ref
                ));

                $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);  
            }
            
            // Envoi un email recap au consultant avec la liste de ses beneficiaire a voir demain
            $tabConsultantDedoublonner = array_unique($tabConsultant);

            foreach($tabConsultantDedoublonner as $consultant_id)
            {
                $rdvConsultant = $this->em->getRepository("ApplicationPlateformeBundle:Historique")->findEventByDateAndConsultant($dateMoreOneDay, $consultant_id); 
                
                $ref = "1-c";
                $from = "audrey.azoulay@entheor.com";
                $to = $rdvConsultant[0]->getConsultant()->getEmail();
                $cc = "";
                $bcc = array(
                    "support@iciformation.fr" => "Support",
                    "f.azoulay@entheor.com" => "Franck Azoulay", 
                    "ph.rouzaud@iciformation.fr" => "Philippe Rouzaud",
                    "christine.clement@entheor.com" => "Christine Clement",
                    "audrey.azoulay@entheor.com" => "Audrey Azoulay");

                $dateRv = new \DateTime("NOW");
                $dateRvOneMore = $dateRv->add(new \DateInterval('P1D'));
                
                $subject = "[Rappel] Vos rendez-vous pour demain ". $dateRvOneMore->format('d/m/Y');
                
                $message = "Bonjour ".ucfirst($rdvConsultant[0]->getConsultant()->getPrenom())." ".strtoupper($rdvConsultant[0]->getConsultant()->getNom()).", <br><br>
                        Veuillez trouver ci-dessous la liste de vos rendez-vous prévus pour demain ".$Jour[$dateRvOneMore->format('l')]." ".$dateRvOneMore->format('j')." ".$Mois[$dateRvOneMore->format('F')]." : 
                        <ul>";
                // Chaque rdv du consultant
                foreach($rdvConsultant as $rdv)
                {
                    $beneficiaire = $rdv->getBeneficiaire();
                    $message .= "<li>".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getNomConso())." ".ucfirst($beneficiaire->getPrenomConso()).", ".$rdv->getDateDebut()->format('H')."h".$rdv->getDateDebut()->format('i').", ".ucfirst($rdv->getSummary()).", ".$beneficiaire->getTelConso()."</li>"; 
                }
                
                $message .= "</ul>";
                
                $template = "@Apb/Alert/Mail/mailDefault.html.twig";

                $body = $this->templating->render($template, array(
                    'sujet' => $subject ,
                    'message' => $message,
                    'reference' => $ref
                ));

                $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
            }   
        }
    }
}
?>