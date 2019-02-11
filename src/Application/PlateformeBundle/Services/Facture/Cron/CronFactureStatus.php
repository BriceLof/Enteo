<?php

namespace Application\PlateformeBundle\Services\Facture\Cron;

class CronFactureStatus extends \Application\PlateformeBundle\Services\Mailer
{
    public function listBeneficiareWithInvoice()
    {
        // Get toutes les factures

        $repoFacture = $this->em->getRepository("ApplicationPlateformeBundle:Facture");
        $allFactureWithoutFilter = $repoFacture->findAll();

        $allFacture = array();
        foreach($allFactureWithoutFilter as $invoice)
        {
            $allFacture[$invoice->getId()] = $invoice;
        }

        // Get facture avec un historique de paiement, donc au delà du statut 'sent'

        $factureWithHisto = $repoFacture->getFactureAvecHistorique();
        $allFactureSentPartiel = array();
        $allFactureWithHisto = array();
        foreach ($factureWithHisto as $factureHisto)
        {

            $lastHisto = $factureHisto->getHistoriquesPaiement()[count($factureHisto->getHistoriquesPaiement()) - 1];
            if($lastHisto->getStatut() == 'sent' || $lastHisto->getStatut() == 'partiel')
            {
                $dateHisto = $lastHisto->getDateHeure();
                $currentDate = new \DateTime();
                $interval = $dateHisto->diff($currentDate);
                // histo facture qui n'a pas bouger depuis plus de 15 jours
                if($interval->days > 15){
                    $allFactureSentPartiel[$lastHisto->getFacture()->getId()] = $lastHisto;
                }
            }
            $allFactureWithHisto[$lastHisto->getFacture()->getId()] = $lastHisto;
        }

        // Je merge le tableau de toute les factures avec celui où les factures ont un historique.
        // Cela me servira à savoir quel facture n'a pas d'historique

        $arrayFactureAndHistoMerge = array_merge($allFacture, $allFactureWithHisto);
        $arrayFactureSansHisto = array();

        $nameClassFacture = 'Application\PlateformeBundle\Entity\Facture';
        $nameClassHistoFacture = 'Application\PlateformeBundle\Entity\HistoriquePaiementFacture';

        // Je garde que les factures sans histo, d'où la suppresion dès que je vois un historique
        foreach($arrayFactureAndHistoMerge as $factureAndHisto)
        {
            if(get_class($factureAndHisto) == $nameClassFacture)
            {
                $dateEntity = $factureAndHisto->getDate();
                $currentDate = new \DateTime();
                $interval = $dateEntity->diff($currentDate);
                // facture qui n'a pas bouger depuis plus de 15 jours
                if($interval->days > 15){
                    $arrayFactureSansHisto[$factureAndHisto->getId()] = $factureAndHisto;
                }
            }
            elseif (get_class($factureAndHisto) == $nameClassHistoFacture){
                if (array_key_exists($factureAndHisto->getFacture()->getId(), $arrayFactureSansHisto)) {
                    unset($arrayFactureSansHisto[$factureAndHisto->getFacture()->getId()]);
                }
            }
        }

        // Envoyer le mail

        //dump(($allFactureSentPartiel));
        //dump(($arrayFactureSansHisto));

        $subject = "Dossier bénéficiaire avec facture en Sent ou Partiel";
        $from = $this->from;
        $to = array(array("email" => 'brice.lof@gmail.com', "name" => 'Brice lof'));
        $message = "
            Bonjour,<br><br>
            Cela fait  plus de 15 jours que ces factures sont en statut : 'Sent' ou 'Paid partiel' <br><br>
        ";

        foreach ($arrayFactureSansHisto as $facture){
            $message .= $facture->getBeneficiaire()->getNomConso()."<br>";
        }
        foreach ($allFactureSentPartiel as $histo){
            $message .= $histo->getFacture()->getBeneficiaire()->getNomConso()."<br>";
        }

        //$cc = array(array("email" => "f.azoulay@entheor.com", "name" => "f.azoulay@entheor.com"));

        //$bcc = array(array("email" => "support.informatique@entheor.com", "name" => "support.informatique@entheor.com"));
        $template = "@Apb/Alert/Mail/mailDefault.html.twig";
        $body = $this->templating->render($template, array(
            'sujet' => $subject ,
            'message' => $message,
            'reference' => '8'
        ));

        $this->sendMessage($from, $to,null , null, null, $subject, $body);

    }
}