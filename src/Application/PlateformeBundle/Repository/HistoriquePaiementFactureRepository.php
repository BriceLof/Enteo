<?php

namespace Application\PlateformeBundle\Repository;

/**
 * HistoriquePaiementFactureRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HistoriquePaiementFactureRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLastOrderByDesc()
    {
//        $query = $this->getEntityManager()->createQuery('
//            SELECT MAX(h.id)
//            FROM ApplicationPlateformeBundle:HistoriquePaiementFacture h
//            WHERE h.facture = :id
//            GROUP BY h.facture'
//        );
//        $query->setParameter('id', '349');
//
//        // Utilisation de getSingleResult car la requête ne doit retourner qu'un seul résultat
//        return $query->getResult();


        $queryBuilder = $this->createQueryBuilder('h')
            ->orderBy('h.id', 'DESC');
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;

    }
}
