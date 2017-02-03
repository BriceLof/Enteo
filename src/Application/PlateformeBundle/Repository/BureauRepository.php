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
                ->orderBy("b.id", "DESC");
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }
}
