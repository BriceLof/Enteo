<?php

namespace Application\StatsBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

class StatsBeneficiaire
{
    private $em;
    private $dateFrom;
    private $dateTo;
    private $beneficiaires;

    public function __construct(EntityManagerInterface $em, $dateFrom = null, $dateTo = null)
    {
        $this->em = $em;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->beneficiaires = $this->em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->getBeneficiaireWithDate($dateFrom, $dateTo);
    }

    public function getBeneficiaireOnPeriod()
    {
        return $this->beneficiaires;
    }

    public function getBeneficiaireNonAbouti()
    {
        $beneficiaires = $this->beneficiaires;
        $tabBenefNonAbouti = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();
            // derniere news
            if( !is_null($news[count($news) - 1]) && !is_null($news[count($news) - 1]->getStatut()) )
            {
                $lastNews = $news[count($news) - 1]->getStatut()->getSlug();

                if($lastNews == 'menr' || $lastNews == 'telephone' || $lastNews == 'reporte')
                    $tabBenefNonAbouti[] = $benef;
            }


        }
        return $tabBenefNonAbouti;
    }

    public function getBeneficiaireRvCommerciaux()
    {
        $beneficiaires = $this->beneficiaires;

        $tabRvFaire = array();
        $tabRvRealisePositif = array();
        $tabRvRealiseNegatif = array();
        $tabRvRealiseAutre = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();
            if( !is_null($news[count($news) - 1]) && !is_null($news[count($news) - 1]->getStatut()) )
            {
                // derniere news
                $lastNews = $news[count($news) - 1];
                $lastNewsStatut = $lastNews->getStatut()->getSlug();

                if($lastNewsStatut == 'rv1-a-faire' || $lastNewsStatut == 'rv2-a-faire')
                {
                    $tabRvFaire[] = $benef;
                }
                elseif ($lastNewsStatut == 'rv1-realise' || $lastNewsStatut == 'rv2-realise'){
                    if( !is_null($lastNews->getDetailStatut()) )
                    {
                        $lastNewsDetailStatut = $lastNews->getDetailStatut()->getDetail();
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

    public function getBeneficiaireStatutFinancement()
    {
        $beneficiaires = $this->beneficiaires;

        $tabFinancementEnAttente = array();
        $tabFinancementOkFinanceur = array();
        $tabFinancementOkFinancementPartiel = array();
        $tabFinancementRefus = array();

        foreach($beneficiaires as $benef) {
            $totalSuiviAdOfBenef = count($benef->getSuiviAdministratif());
            $lastSuiviAdOfBenef = $benef->getSuiviAdministratif()[$totalSuiviAdOfBenef - 1];

            if( !is_null($lastSuiviAdOfBenef) && !is_null($lastSuiviAdOfBenef->getDetailStatut()) && !is_null($lastSuiviAdOfBenef->getStatut()) && $lastSuiviAdOfBenef->getStatut()->getSlug() == "financement"){
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

    public function getBeneficiaireStatutFacturation()
    {
        $beneficiaires = $this->beneficiaires;

        $tabFacturationAcompte = array();
        $tabFacturationTotale = array();
        $tabFacturationSolde = array();
        $tabFacturationAvoir = array();

        foreach($beneficiaires as $benef) {
            $totalSuiviAdOfBenef = count($benef->getSuiviAdministratif());

            if($totalSuiviAdOfBenef > 0){
                $indexMinus = 1;

                $arraySuivi = $benef->getSuiviAdministratif()->toArray();
                foreach(array_reverse($arraySuivi) as $suivi)
                {
                    if($suivi->getDetailStatut() == null){
                        $indexMinus++;
                    }
                    else
                        break;
                }

                $lastSuiviAdOfBenef = $benef->getSuiviAdministratif()[$totalSuiviAdOfBenef - $indexMinus];

                if(!is_null($lastSuiviAdOfBenef) && !is_null($lastSuiviAdOfBenef->getDetailStatut()) && $lastSuiviAdOfBenef->getStatut()->getSlug() == "facturation"){
                    if($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Facture acompte"){
                        $tabFacturationAcompte[] = $benef;
                    }
                    elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Facture totale"){
                        $tabFacturationTotale[] = $benef;
                    }
                    elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Facture solde"){
                        $tabFacturationSolde[] = $benef;
                    }
                    elseif($lastSuiviAdOfBenef->getDetailStatut()->getDetail() == "Avoir"){
                        $tabFacturationAvoir[] = $benef;
                    }
                }
            }
        }

        $tabStatutFacturation = array(
            "acompte" => $tabFacturationAcompte,
            "totale" => $tabFacturationTotale,
            "solde" => $tabFacturationSolde,
            "avoir" => $tabFacturationAvoir,
        );

        return $tabStatutFacturation;
    }

    public function getBeneficiaireStatutReglement()
    {
        $beneficiaires = $this->beneficiaires;

        $tabReglementPartiel = array();
        $tabReglementTotal = array();
        $tabReglementAnnuler = array();

        foreach($beneficiaires as $benef) {
            $totalSuiviAdOfBenef = count($benef->getSuiviAdministratif());

            if($totalSuiviAdOfBenef > 0){
                $indexMinus = 1;

                $arraySuivi = $benef->getSuiviAdministratif()->toArray();
                foreach(array_reverse($arraySuivi) as $suivi)
                {
                    if($suivi->getDetailStatut() == null){
                        $indexMinus++;
                    }
                    else
                        break;
                }

                $lastSuiviAdOfBenef = $benef->getSuiviAdministratif()[$totalSuiviAdOfBenef - $indexMinus];

                if(!is_null($lastSuiviAdOfBenef) && !is_null($lastSuiviAdOfBenef->getDetailStatut()) && $lastSuiviAdOfBenef->getStatut()->getSlug() == "reglement"){
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
        }

        $tabStatutReglement = array(
            "partiel" => $tabReglementPartiel,
            "total" => $tabReglementTotal,
            "annuler" => $tabReglementAnnuler,
        );

        return $tabStatutReglement;

    }

    public function getBeneficiaireTerminer()
    {
        $beneficiaires = $this->beneficiaires;
        $tabBenefTerminer = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();

            if( !is_null($news[count($news) - 1]) )
            {
                // derniere news
                $lastNews = $news[count($news) - 1]->getStatut()->getSlug();

                if($lastNews == 'termine')
                    $tabBenefTerminer[] = $benef;
            }


        }
        return $tabBenefTerminer;
    }

    public function getBeneficiaireAbandon()
    {
        $beneficiaires = $this->beneficiaires;
        $tabBenefAbandon = array();

        foreach($beneficiaires as $benef){
            $news = $benef->getNews();

            if( !is_null($news[count($news) - 1]) && !is_null($news[count($news) - 1]->getStatut()) )
            {
                // derniere news
                $lastNews = $news[count($news) - 1]->getStatut()->getSlug();

                if($lastNews == 'abandon')
                    $tabBenefAbandon[] = $benef;
            }
        }
        return $tabBenefAbandon;
    }
}