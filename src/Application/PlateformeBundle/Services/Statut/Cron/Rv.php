<?php

namespace Application\PlateformeBundle\Services\Statut\Cron;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\DetailStatut;
use Application\UsersBundle\Entity\Users;

class Rv extends \Application\PlateformeBundle\Services\Mailer
{ 
    // Alerte à +1h de l'heure du démarrage du rdv dans l'agenda (entity historique)
    public function alerteSuiteRvAgenda()
    {
        $histoRepo = $this->em->getRepository("ApplicationPlateformeBundle:Historique");
        
        $today = new \DateTime();
        $eventAgenda = $histoRepo->findEventByDate($today->format('Y-m-d'));
		
		$Jour = array("Sunday" => "Dimanche", "Monday" => "Lundi", "Tuesday" => "Mardi" , "Wednesday" => "Mercredi" , "Thursday" => "Jeudi" , "Friday" => "Vendredi" ,"Saturday" => "Samedi");
        $Mois = array("January" => "Janvier", "February" => "Février", "March" => "Mars", "April" => "Avril", "May" => "Mai", "June" => "Juin", "July" => "Juillet", "August" => "Août", "September" => "Septembre", "October" => "Octobre", "November" => "Novembre", "December" => "Décembre");

		if(count($eventAgenda) > 0)
		{
			$arrayConsultant = array();
			// Récupération des ids des consultant concernés 
            foreach($eventAgenda as $event)
            {
                $consultant = $event->getConsultant();
				if(!is_null($consultant))
				{
					// Si on a deja des lignes pour ce consultant
					if(!in_array($consultant->getId(), $arrayConsultant))
						$arrayConsultant[] = (int) $consultant->getId();  
				}
            }
			
			for($i = 0; $i < count($arrayConsultant); $i++)
            {
				$rdvConsultant = $this->em->getRepository("ApplicationPlateformeBundle:Historique")->findEventByDateAndConsultant($today->format('Y-m-d'), $arrayConsultant[$i]);
                if(count($rdvConsultant) > 0){

                    if ($rdvConsultant[0]->getSummary() == 'RV1' || $rdvConsultant[0]->getSummary() == 'RV2') {

                        $consultant = $rdvConsultant[0]->getConsultant();

                        if ($consultant->getCivilite() == "mme")
                            $cher = "Chère";
                        else
                            $cher = "Cher";

                        $subject = "Comment se  sont passés vos rendez-vous du " . $Jour[$today->format('l')] . " " . $today->format('j') . " " . $Mois[$today->format('F')] . " ?";
                        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
                        $ref = "2";
                        $to = array(array("email" => $consultant->getEmail(), "name" => $consultant->getEmail()));
                        $cc = "";
                        $bcc = array(
                            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
                            array("email" => "support.informatique@entheor.com", "name" => "Support"),
                        );

                        $message = $cher . " " . $consultant->getPrenom() . ", <br><br> 
                            Vous avez reçu le <b>" . $Jour[$today->format('l')] . " " . $today->format('j') . " " . $Mois[$today->format('F')] . "</b> en RV de Positionnement :<br><br>";

                        $canBeSend = false;

                        foreach ($rdvConsultant as $rdv) {
                            if ($rdv->getMailPostRv() == 0 && $rdv->getEventId() != '0') {
                                $beneficiaire = $rdv->getBeneficiaire();
                                $message .= "&bull; " . $beneficiaire->getCiviliteConso() . " " . ucfirst($beneficiaire->getPrenomConso()) . " " . ucfirst($beneficiaire->getNomConso()) . "<br>";
                                $canBeSend = true;

                                // Pour que ce mail ne se relance pas lors du passage de la prochaine cron, je met un champs à jour pour dire que le mail a déjà été envoyé pour ce rdv
                                $rdv->setMailPostRv(true);
                                $this->em->persist($rdv);
                                $this->em->flush();
                            }


                        }

                        if ($canBeSend) {
                            $message .= "<br><b>Je vous remercie de bien vouloir mettre à jour les informations suivantes sur <a href='https://appli.entheor.com/web/'>ENTHEO</a> :</b><br>"
                                . "- Statut du bénéficiaire à l'issue du RV (positif, négatif, indécis, à reporter...)<br>
                                   - Compléter les informations clés du bénéficiaire : Coordonnées, Statut, type de Contrat, n° de sécu, date de naissance, informations employeur, OPCA... <br><br>
                                   
                                <u>Ces informations sont requises</u> pour monter le dossier de financement et vous permettre de démarrer au plus vite la prestation d'accompagnement.<br><br>
                                
                                Bien Cordialement,<br><br> 
                                
                                Christine Clément<br>
                                <a href='mailto:christine.clement@entheor.com'>christine.clement@entheor.com</a><br>
                                06 81 84 85 24";

                            $template = "@Apb/Alert/Mail/mailDefault.html.twig";
                            $body = $this->templating->render($template, array(
                                'sujet' => $subject,
                                'message' => $message,
                                'reference' => $ref
                            ));
                            $this->sendMessage($from, $to, null, $cc, $bcc, $subject, $body);
                        }
                    }
				}
			}	
		}
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
                    $to = array(array("email" => $emailConsultant, "name" => $emailConsultant));
                }
                elseif($dateMoreTwoDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 2 à J+3
                    $subject = "Rappel J+3 : Post RV1 ";
                    $to = array(array("email" => $emailConsultant, "name" => $emailConsultant));
                    $cc = array(
                        array("email" => "support.informatique@entheor.com", "name" => "Support")
                    );
                }
                elseif($dateMoreThreeDay == $dateCurrent->format('Y-m-d'))
                {
                    // Alerte 3 à J+7
                    $subject = "Rappel J+7 [URGENT] : Post RV1 ";
                    $to = array(array("email" => $emailConsultant, "name" => $emailConsultant));
                    $cc = array(
                        array("email" => "support.informatique@entheor.com", "name" => "Support")
                    );
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

    public function mailRvRealise(Beneficiaire $beneficiaire, Historique $lastRv){
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $replyTo = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "Nouveau dossier bénéficiaire ". $beneficiaire->getPrenomConso()." ". $beneficiaire->getNomConso() ." à établir";
        $template = '@Apb/Alert/Mail/mailRvRealise.html.twig';
        $to = array(array("email" => "virginie.hiairrassary@entheor.com", "name" => "virginie.hiairrassary@entheor.com"));
        $cci = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "ph.rouzaud@iciformation.fr", "name" => "Philippe ROUZAUD"),
            array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Clement"),
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'lastRv' => $lastRv,
        ));
        $this->sendMessage($from,$to,null,null,$cci,$subject,$body);

    }

    public function firstMailRvFicheNonMaj(Users $consultant , $beneficiaires){
        $dateHier = $this->date->dateFr(new \DateTime('now'));
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $replyTo = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "[URGENT] Mise à Jour de vos Rendez-vous du ".$dateHier;
        $template = '@Apb/Alert/Mail/firstMailRvFicheNonMaj.html.twig';
        $to = array(array("email" => $consultant->getEmail(), "name" => $consultant->getEmail()));
        $cci = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "resp.administratif@entheor.com", "name" => "Responsable Administratif"),
            array("email" => "support.informatique@entheor.com", "name" => "Support"),
        );
        $body = $this->templating->render($template, array(
            'consultant' => $consultant,
            'beneficiaires' => $beneficiaires,
        ));
        $this->sendMessage($from,$to,$replyTo,null,$cci,$subject,$body);
//        $this->sendMessage($from,"support.informatique@entheor.com", $replyTo, null,null,$subject,$body);
    }

    public function mailRvAReporter($news, $attachement){
        $ref = "rv-reporter";
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $replyTo = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "Suivi des Rendez-Vous à reporter et no show de S-1 ( du ".(new \DateTime('now'))->modify('-7 day')->format('d/m/Y')." au ". (new \DateTime('now'))->format('d/m/Y') ." )";
        $template = '@Apb/Alert/Mail/mailRvAReporter.html.twig';
        $to = array(array("email" => "audrey.azoulay@entheor.com", "name" => "Audrey AZOULAY"));
        $cci = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "contact@entheor.com", "name" => "Contact ENTHEOR"),
        );
        $body = $this->templating->render($template, array(
            'news' => $news,
            'reference' => $ref,
        ));
        $this->sendMessage($from,$to,$replyTo,null,$cci,$subject,$body,$attachement);
//        $this->sendMessage($from, 'support.informatique@entheor.com',null, null, null, $subject, $body, $attachement);

    }

    public function secondMailRvFicheNonMaj(Users $consultant , $beneficiaires){
        $dateAvantHier = $this->date->dateFr((new \DateTime('now'))->modify("-1 day"));
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $replyTo = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "[DERNIER RAPPEL] Mise à Jour de la fiche du ".$dateAvantHier;
        $template = '@Apb/Alert/Mail/secondMailRvFicheNonMaj.html.twig';
        $to = array(array("email" => $consultant->getEmail(), "name" => $consultant->getEmail()));
        $cci = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "resp.administratif@entheor.com", "name" => "Responsable Administratif"),
            array("email" => "support.informatique@entheor.com", "name" => "Support"),
        );
        $body = $this->templating->render($template, array(
            'consultant' => $consultant,
            'beneficiaires' => $beneficiaires,
        ));
        $this->sendMessage($from,$to,$replyTo,null,$cci,$subject,$body);
//        $this->sendMessage($from,"support.informatique@entheor.com", $replyTo,null,null,$subject,$body);
    }

    public function lastMailRvFicheNonMaj($rvs, $attachement){
        $from = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $replyTo = array("email" => "christine.clement@entheor.com", "name" => "christine.clement@entheor.com");
        $subject = "Liste des ".count($rvs)." Bénéficiaires dont le statut n'a pas été mis à jour après R1 ou R2";
        $template = '@Apb/Alert/Mail/lastMailRvFicheNonMaj.html.twig';
        $to = array(
            array("email" => "contact@entheor.com", "name" => "Contact Entheor"),
            array("email" => "virginie.hiairrassary@entheor.com", "name" => "Virginie HIAIRRASSARY"),
        );
        $cc = array(
            array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
            array("email" => "ph.rouzaud@iciformation.fr", "name" => "Philippe ROUZAUD"),
        );
        $cci = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
        $body = $this->templating->render($template, array(
            'rvs' => $rvs
        ));
        $this->sendMessage($from,$to,$replyTo,$cc,$cci,$subject,$body, $attachement);
//        $this->sendMessage($from,"support.informatique@entheor.com", $replyTo,null,null,$subject,$body);
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
                if ($rdv->getCanceled() === 1 or $rdv->getCanceled() === 2) {
                }else {
                    $from = array("email" => "contact@entheor.com", "name" => "contact@entheor.com");
                    $consultant = $rdv->getConsultant();
                    $tabConsultant[] = $rdv->getConsultant()->getId();
                    $dateRdv = $rdv->getDateDebut();
                    $typeRdv = $rdv->getTypeRdv();

                    $ref = "1-b";
                    $to = array(array("email" => $rdv->getBeneficiaire()->getEmailConso(), "name" => $rdv->getBeneficiaire()->getEmailConso()));
                    $cc = "";
                    $bcc = array(
                        array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
                        array("email" => "audrey.azoulay@entheor.com", "name" => "Audrey Azoulay"),
                        array("email" => "support.informatique@entheor.com", "name" => "Support"),
                    );

                    if ($typeRdv == "presenciel" || $typeRdv == "presentiel")
                        $subject = "[Rappel] Vous avez rendez-vous pour votre VAE demain à " . $dateRdv->format('H') . "h" . $dateRdv->format('i');
                    else
                        $subject = "[Rappel] Vous avez un rendez-vous téléphonique pour votre VAE demain à " . $dateRdv->format('H') . "h" . $dateRdv->format('i');

                    $acces = "";
                    $commentaire = "";
                    if (!is_null($rdv->getBureau())) {
                        if (!is_null($rdv->getBureau()->getAcces())) $acces = "Accès : " . $rdv->getBureau()->getAcces();
                        if (!is_null($rdv->getBureau()->getCommentaire())) $commentaire = "Commentaires : " . $rdv->getBureau()->getCommentaire();
                    }

                    if ($typeRdv == "presenciel" || $typeRdv == "presentiel") {
                        $message = ucfirst($rdv->getBeneficiaire()->getCiviliteConso()) . " " . ucfirst($rdv->getBeneficiaire()->getNomConso()) . ", <br><br>"
                            . "Nous vous rappelons que vous avez rendez-vous demain <b>" . $Jour[$dateRdv->format('l')] . " " . $dateRdv->format('j') . " " . $Mois[$dateRdv->format('F')] . " à " . $dateRdv->format('H') . "h" . $dateRdv->format('i') . "</b> : 
                                            <table style='margin-top:-50px;'>
                                                    <tr>
                                                            <td><u>Votre consultant : </u></td>
                                                            <td style='display:block;margin-top:64px;margin-left:27px;'>
                                                                    <b>" . ucfirst($consultant->getCivilite()) . " " . ucfirst($consultant->getPrenom()) . " " . strtoupper($consultant->getNom()) . "</b><br>
                                                                    Cabinet ENTHEOR <br>
                                                                    Tél: " . $consultant->getTel1() . "<br>
                                                                    Email : " . $consultant->getEmail() . "
                                                            </td>
                                                    </tr>
                                                    <tr>
                                                            <td><u>Lieu du rendez-vous : </u></td>
                                                            <td style='display:block;margin-top:63px;margin-left:26px;'>
                                                                    " . $rdv->getBureau()->getNombureau() . "<br>
                                                                    " . $rdv->getBureau()->getAdresse() . "<br>" .
                            $rdv->getBureau()->getVille()->getCp() . " " . $rdv->getBureau()->getVille()->getNom() . "<br>" .
                            $acces . "<br>" .
                            $commentaire . "    
                                                            </td>
                                                    </tr>
                                            </table>
                                            <br><br>";
                        if ($rdv->getSummary() == 'RV1' || $rdv->getSummary() == 'RV2') $message .= "Merci de vous munir <u>impérativement</u> de :<br>";
                    } else {
                        $message = ucfirst($rdv->getBeneficiaire()->getCiviliteConso()) . " " . ucfirst($rdv->getBeneficiaire()->getNomConso()) . ", <br><br>"
                            . "Nous vous rappelons que vous avez un rendez-vous téléphonique demain avec " . ucfirst($consultant->getCivilite()) . " " . ucfirst($consultant->getPrenom()) . " " . strtoupper($consultant->getNom()) . "<br><br><b>" .
                            ucfirst($consultant->getCivilite()) . " " . strtoupper($consultant->getNom()) . " <u>attendra votre appel</u> le " . $Jour[$dateRdv->format('l')] . " " . $dateRdv->format('j') . " " . $Mois[$dateRdv->format('F')] . " à <u>" . $dateRdv->format('H') . "h" . $dateRdv->format('i') . " précises</u></b>
                                au numéro suivant : <b>" . $consultant->getTel1() . "</b><br><br>";
                        if ($rdv->getSummary() == 'RV1' || $rdv->getSummary() == 'RV2')  $message .= "Pour ce rendez-vous téléphonique, merci de vous munir <u>impérativement</u> de :<br>";
                    }

                    if ($rdv->getSummary() == 'RV1' || $rdv->getSummary() == 'RV2') {
                        $message .= "
                            <div style='margin-left:20px;'>
                                    - <a href='https://www.entheor.com/files/maquette_cv.docx' alt='CV par compétences vide'><b>CV détaillé</b> (par compétences)</a><br>";

                        if ($rdv->getBeneficiaire()->getCsp() != "demandeur d'emploi" && $rdv->getBeneficiaire()->getCsp() != "chef d'entreprise/PL")
                            $message .= "
                                    - <b>Le nom de l'OPCA</b> (organisme financeur) à demander à votre employeur <br>
                                    - <b>Votre dernier bulletin de paie</b><br>
                                    - <b>Votre attestation d'heures DIF</b> (qui vous a été remise par votre employeur en Déc. 2014 ou Janv. 2015 ou qui figure sur vos bulletins de paie de ces mois)<br> ";
                    }

                    $message .= "
                                    </div>
                                    <br><br>
    
                       <div style='padding:15px;border:1px solid;text-align:center;'><b>En cas d'empêchement : nous prévenir au ".$consultant->getTel1()."</b></div>
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
                        'sujet' => $subject,
                        'message' => $message,
                        'reference' => $ref
                    ));
                    $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
                }
            }
            
            // Envoi un email recap au consultant avec la liste de ses beneficiaire a voir demain
            $tabConsultantDedoublonner = array_unique($tabConsultant);

            foreach($tabConsultantDedoublonner as $consultant_id)
            {
                $rdvConsultant = $this->em->getRepository("ApplicationPlateformeBundle:Historique")->findEventByDateAndConsultant($dateMoreOneDay, $consultant_id); 
                
                $ref = "1-c";
                $from = array("email" => "contact@entheor.com", "name" => "contact@entheor.com");
                $to = array(array("email" => $rdvConsultant[0]->getConsultant()->getEmail(), "name" => $rdvConsultant[0]->getConsultant()->getEmail()));
                $cc = "";
                $bcc = array(
                    array("email" => "f.azoulay@entheor.com", "name" => "Franck Azoulay"),
                    array("email" => "audrey.azoulay@entheor.com", "name" => "Audrey Azoulay"),
                    array("email" => "christine.clementmolier@entheor.com", "name" => "Christine Molier"),
                    array("email" => "support.informatique@entheor.com", "name" => "Support"),
                );
                $dateRv = new \DateTime("NOW");
                $dateRvOneMore = $dateRv->add(new \DateInterval('P1D'));
                
                $subject = "[Rappel] Vos rendez-vous pour demain ". $dateRvOneMore->format('d/m/Y');
                
                $message = "Bonjour ".ucfirst($rdvConsultant[0]->getConsultant()->getPrenom())." ".strtoupper($rdvConsultant[0]->getConsultant()->getNom()).", <br><br>
                        Veuillez trouver ci-dessous la liste de vos rendez-vous prévus pour demain ".$Jour[$dateRvOneMore->format('l')]." ".$dateRvOneMore->format('j')." ".$Mois[$dateRvOneMore->format('F')]." : 
                        <ul>";
                // Chaque rdv du consultant
                foreach($rdvConsultant as $rdv) {
                    if ($rdv->getCanceled() === 1 || $rdv->getCanceled() === 2) {
                    }
                    else{
                        $beneficiaire = $rdv->getBeneficiaire();
                        $message .= "<li>" . ucfirst($beneficiaire->getCiviliteConso()) . " " . ucfirst($beneficiaire->getNomConso()) . " " . ucfirst($beneficiaire->getPrenomConso()) . ", " . $rdv->getDateDebut()->format('H') . "h" . $rdv->getDateDebut()->format('i') . ", " . ucfirst($rdv->getSummary()) . ", " . $beneficiaire->getTelConso() . "</li>";
                    }
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
    
    
    /* Alerte automatique à destination des gestionnaires admn (et copie les administrateurs)
	*  3 semaines après passage au statut adm = Financement / Attente Accord si le statut n'a pas bouger depuis 
	*/
	public function rappelFinancement()
	{
		//  Récupération des suivis adminitratif antérieur à la date jour et ayant comme detail statut "attente accord" 		
		$date = new \DateTime("NOW");	
		$detailStatut =  $this->em->getRepository("ApplicationPlateformeBundle:DetailStatut")->findOneByDetail('Attente accord');
		$repoSuiviAdm = $this->em->getRepository("ApplicationPlateformeBundle:SuiviAdministratif");
		$suivis = $repoSuiviAdm->findBeforeDateAndDetailStatut($date, $detailStatut);
		
		$adminitrateurs = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_ADMIN");
        $gestionnaires = $this->em->getRepository("ApplicationUsersBundle:Users")->findByTypeUser("ROLE_GESTION");
        $listeAdministrateurs = array();
        $listeGestionnaires = array();
        foreach($adminitrateurs as $admin){ array_push($listeAdministrateurs, array("email" => $admin->getEmail(), "name" => $admin->getEmail()));}
        foreach($gestionnaires as $gestionnaire){ array_push($listeGestionnaires, array("email" => $gestionnaire->getEmail(), "name" => $gestionnaire->getEmail()));}
		
		$envoiMail = array("no");

        $i = 0;
        $mess ="";

		foreach($suivis as $suivi)
		{
			$beneficiaire = $suivi->getBeneficiaire();	
			$dateSuivi = $suivi->getDate();
			
			$interval = $date->diff($dateSuivi);
			// nombre de jour entre les 2 dates 
			$nbJour = $interval->format('%R%a');
			// Si il y a 3 semaines entre les deux dates (21 jours)
			var_dump($nbJour);
			if($nbJour == "-21"){
				//var_dump($nbJour);
				//var_dump($beneficiaire->getId());
				// Il y a t-il un suivi administratif ajouté après le financement attente accord, uniquement si ce n'est pas le cas, on envoi le mail 
				$suiviBeneficiaire = $repoSuiviAdm->findByBeneficiaireAndDate($beneficiaire, $dateSuivi);
				//var_dump(count($suiviBeneficiaire));
				if(count($suiviBeneficiaire) == 0 ){
				    $i++;
					$envoiMail[] = "yes";
					$mess .= "<p style='margin-left:10px;'>&bull; ".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso())." - ".strtoupper($beneficiaire->getTypeFinanceur())." : ".strtoupper($beneficiaire->getOrganisme())."</p>";
				}
			}
		}

		if ($i == 1){
            $message = "Bonjour Virgine,<br><br>
                                Cela fait 3 semaines que ce dossier est en statut : 'Financement - Attente accord'<br> 
                                Il faut secouer les Financeurs du dossier suivant :<br>";
        }else{
            $message = "Bonjour Virgine,<br><br>
                                Cela fait 3 semaines que ces dossiers sont en statut : 'Financement - Attente accord'<br> 
                                Il faut secouer les Financeurs des dossiers suivants :<br>";
        }
        $message .= $mess;
		
		// On peut envoyer le mail 
		if(in_array("yes", $envoiMail)){		
			$ref = "4";
			$subject = "Financement - Attente accord ";
	        $from = array("email" => "christine.molier@entheor.com", "name" => "christine.molier@entheor.com");
	        $to = $listeGestionnaires;
	        $cc = $listeAdministrateurs;
	        $bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
			
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
?>