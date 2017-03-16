<?php

namespace Application\PlateformeBundle\Repository;

/**
 * BureauRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BureauRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll() {
        parent::findAll();
        $queryBuilder = $this->createQueryBuilder("b")
                ->leftJoin('b.ville', 'v')
                ->addSelect('v')
				->where('b.supprimer = 0')
                ->orderBy("v.nom", "ASC");
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }
    // Recuperer l'id du bureau par rapport à la ville
    public function retreiveBureau($ville){
        return 
                $this
                    ->createQueryBuilder('b')
                    ->where('b.ville = :ville')
                    ->setParameter('ville',$ville)
                    ->getQuery()
                    ->getResult();
    }
}
