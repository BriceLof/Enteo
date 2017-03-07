<?php

namespace Application\PlateformeBundle\Services\Statut\Cron;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;

class Rv extends \Application\PlateformeBundle\Services\Mailer
{ 
    // Alerte à +1h de l'heure du démarrage du rdv dans l'agenda (entity historique)
    public function alerteSuiteRv1Rv2Agenda()
    {
        $histoRepo = $this->em->getRepository("ApplicationPlateformeBundle:Historique");
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
                
                $template = "@Apb/Alert/Mail/postRv1.html.twig";
                
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
                
                $this->sendMessage($this->from, $to, $cc, $subject, $body);

                return "mail envoyé";
            }  
        }  
    }

    public function mailRvRealise(Beneficiaire $beneficiaire, News $lastNews){
        $subject = "Nouveau dossier bénéficiaire ". $beneficiaire->getPrenomConso()." ". $beneficiaire->getNomConso() ." à établir";
        $template = '@Apb/Alert/Mail/mailRvRealise.html.twig';
        $to = "ranfidy@hotmail.com";
        $cc = array(
            "support@iciformation.fr" => "Support",
            "resp.administratif@entheor.com" => "Responsable Administratif",
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'lastNews' => $lastNews,
        ));
        $this->sendMessage($this->from,$to,$cc,$subject,$body);
    }

    public function firstMailRvFicheNonMaj(Beneficiaire $beneficiaire){
        $subject = "Nouveau dossier bénéficiaire ". ucfirst(strtolower($beneficiaire->getPrenomConso()))." ". ucfirst(strtolower($beneficiaire->getNomConso())) ." à établir";
        $template = '@Apb/Alert/Mail/firstMailRvFicheNonMaj.html.twig';
        $to = "n.ranaivoson@iciformation.fr";
        $cc = array(
            "resp.administratif@entheor.com" => "Responsable Administratif",
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
        ));
        $this->sendMessage($this->from,$to,$cc,$subject,$body);
    }

    public function secondMailRvFicheNonMaj(Beneficiaire $beneficiaire){
        $subject = "[DERNIER RAPPEL] Mise à Jour de la fiche de ". ucfirst(strtolower($beneficiaire->getPrenomConso()))." ". ucfirst(strtolower($beneficiaire->getNomConso())) ." à établir";
        $template = '@Apb/Alert/Mail/secondMailRvFicheNonMaj.html.twig';
        $to = "n.ranaivoson@iciformation.fr";
        $cc = array(
            "resp.administratif@entheor.com" => "Responsable Administratif",
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
        ));
        $this->sendMessage($this->from,$to,$cc,$subject,$body);
    }
}
?>