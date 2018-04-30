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
            $statut = $benef->getLastDetailStatut()->getStatut()->getSlug();
            dump($statut);
        }

    }
}