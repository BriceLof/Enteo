<?php

namespace Application\StatsBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

class StatsBeneficiaire
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getBeneficiaireOnPeriod($dateFrom = null, $dateTo = null)
    {
        return $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);
    }

    public function getBeneficiaireNonAbouti($dateFrom = null, $dateTo = null)
    {
        $beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);
        $tabBenefNonAbouti = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();
            // derniere news
            $lastNews = $news[count($news) - 1]->getStatut()->getSlug();

            if($lastNews == 'menr' || $lastNews == 'telephone' || $lastNews == 'reporte')
                $tabBenefNonAbouti[] = $benef;

        }
        return $tabBenefNonAbouti;
    }

    public function getBeneficiaireRvCommerciaux($dateFrom = null, $dateTo = null)
    {
        $beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);

        $tabRvFaire = array();
        $tabRvRealisePositif = array();
        $tabRvRealiseNegatif = array();
        $tabRvRealiseAutre = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();

            // derniere news
            $lastNews = $news[count($news) - 1];
            $lastNewsStatut = $lastNews->getStatut()->getSlug();
            $lastNewsDetailStatut = $lastNews->getDetailStatut()->getDetail();

            if($lastNewsStatut == 'rv1-a-faire' || $lastNewsStatut == 'rv2-a-faire')
            {
                $tabRvFaire[] = $benef;
            }
            elseif ($lastNewsStatut == 'rv1-realise' || $lastNewsStatut == 'rv2-realise'){
                if($lastNewsDetailStatut == 'RV1 Positif' || $lastNewsDetailStatut == 'RV2 Positif')
                {
                    $tabRvRealisePositif[] = $benef;
                }
                else if($lastNewsDetailStatut == 'RV1 Négatif' || $lastNewsDetailStatut == 'RV2 Négatif'){
                    $tabRvRealiseNegatif[] = $benef;
                }
                else{
                    $tabRvRealiseAutre[] = $benef;
                }
            }
        }

        $tabRvCommerciaux = array(
            "rvFaire" => $tabRvFaire,
            "rvRealisePositif" => $tabRvRealisePositif,
            "rvRealiseNegatif" => $tabRvRealiseNegatif,
            "rvRealiseAutre" => $tabRvRealiseAutre,
        );

        return $tabRvCommerciaux;
    }

    public function getBeneficiaireStatutFinancement($dateFrom = null, $dateTo = null)
    {
        $beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);

        $tabFinancementEnAttente = array();
        $tabFinancementOkFinanceur = array();
        $tabFinancementOkFinancementPartiel = array();
        $tabFinancementRefus = array();

        foreach($beneficiaires as $benef) {
            $totalSuiviAdOfBenef = count($benef->getSuiviAdministratif());
            $lastSuiviAdOfBenef = $benef->getSuiviAdministratif()[$totalSuiviAdOfBenef - 1];

            if(!is_null($lastSuiviAdOfBenef->getStatut()) && $lastSuiviAdOfBenef->getStatut()->getSlug() == "financement"){
                if($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Attente accord"){
                    $tabFinancementEnAttente[] = $benef;
                }
                elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "OK accord financeur"){
                    $tabFinancementOkFinanceur[] = $benef;
                }
                elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "OK financement partiel"){
                    $tabFinancementOkFinancementPartiel[] = $benef;
                }
                elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Refus financement"){
                    $tabFinancementRefus[] = $benef;
                }
            }
        }

        $tabStatutFinancement = array(
            "attenteAccord" => $tabFinancementEnAttente,
            "okFinanceur" => $tabFinancementOkFinanceur,
            "okFinancementPartiel" => $tabFinancementOkFinancementPartiel,
            "refusFinancement" => $tabFinancementRefus,
        );

        return $tabStatutFinancement;

    }

    public function getBeneficiaireStatutFacturation($dateFrom = null, $dateTo = null)
    {
        $beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);

        $tabFacturationAcompte = array();
        $tabFacturationTotale = array();

        foreach($beneficiaires as $benef) {
            $totalSuiviAdOfBenef = count($benef->getSuiviAdministratif());
            $lastSuiviAdOfBenef = $benef->getSuiviAdministratif()[$totalSuiviAdOfBenef - 1];

            if(!is_null($lastSuiviAdOfBenef->getStatut()) && $lastSuiviAdOfBenef->getStatut()->getSlug() == "facturation"){
                if($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Facture acompte"){
                    $tabFacturationAcompte[] = $benef;
                }
                elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Facture totale"){
                    $tabFacturationTotale[] = $benef;
                }
            }
        }

        $tabStatutFacturation = array(
            "acompte" => $tabFacturationAcompte,
            "totale" => $tabFacturationTotale,
        );

        return $tabStatutFacturation;
    }

    public function getBeneficiaireStatutReglement($dateFrom = null, $dateTo = null)
    {
        $beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);

        $tabReglementPartiel = array();
        $tabReglementTotal = array();
        $tabReglementAnnuler = array();

        foreach($beneficiaires as $benef) {
            $totalSuiviAdOfBenef = count($benef->getSuiviAdministratif());
            $lastSuiviAdOfBenef = $benef->getSuiviAdministratif()[$totalSuiviAdOfBenef - 1];

            if(!is_null($lastSuiviAdOfBenef->getStatut()) && $lastSuiviAdOfBenef->getStatut()->getSlug() == "reglement"){
                if($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Réglement partiel"){
                    $tabReglementPartiel[] = $benef;
                }
                elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Prestation soldée"){
                    $tabReglementTotal[] = $benef;
                }
                elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Erreur"){
                    $tabReglementAnnuler[] = $benef;
                }
            }
        }

        $tabStatutReglement = array(
            "partiel" => $tabReglementPartiel,
            "total" => $tabReglementTotal,
            "annuler" => $tabReglementAnnuler,
        );

        return $tabStatutReglement;

    }

    public function getBeneficiaireTerminer($dateFrom = null, $dateTo = null)
    {
        $beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);
        $tabBenefTerminer = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();
            // derniere news
            $lastNews = $news[count($news) - 1]->getStatut()->getSlug();

            if($lastNews == 'termine')
                $tabBenefTerminer[] = $benef;

        }
        return $tabBenefTerminer;
    }

    public function getBeneficiaireAbandon($dateFrom = null, $dateTo = null)
    {
        $beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);
        $tabBenefAbandon = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();
            // derniere news
            $lastNews = $news[count($news) - 1]->getStatut()->getSlug();

            if($lastNews == 'abandon')
                $tabBenefAbandon[] = $benef;

        }
        return $tabBenefAbandon;
    }
}