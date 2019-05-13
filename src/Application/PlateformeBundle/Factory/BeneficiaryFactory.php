<?php
namespace Application\PlateformeBundle\Factory;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Doctrine\ORM\EntityManager;

class BeneficiaryFactory
{

    public static function createBeneficiary(EntityManager $em){
        $beneficiary = new Beneficiaire();
        $beneficiary->setNomConso("");
        $beneficiary->setPrenomConso("");
        $beneficiary->setDateConfMer(new \DateTime('now'));
        $beneficiary->setDateHeureMer(new \DateTime('now'));
        $beneficiary->setIndicatifTel("");
        $beneficiary->setBureau(null);
        $beneficiary->setPays("FR");
        $beneficiary->setHeureRappel("Indifférent");

        $detailStatus = $em->getRepository("ApplicationPlateformeBundle:DetailStatut")->find(1);
        $news = new News();
        $news->setDetailStatut($detailStatus);
        $news->setStatut($detailStatus->getStatut());
        $beneficiary->addNews($news);

        return $beneficiary;
    }
}