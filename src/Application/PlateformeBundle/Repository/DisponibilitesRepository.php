<?php

namespace Application\PlateformeBundle\Repository;

/**
 * Description of DisponibilitesRepository
 * @author iciformation
 */
class DisponibilitesRepository extends \Doctrine\ORM\EntityRepository{
    
    public function findDispoFromDate($date)
    {
        $queryBuilder = $this->createQueryBuilder('d')
                ->leftJoin('d.consultant', 'u')
                ->addSelect('u')
                ->where('d.dateDebuts >= :date')
                ->setParameter('date', $date)
                ->orderBy("u.nom", "ASC")
                ;
        
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }
    
    public function findDispoFromDateDuConsultant($date, $consultant)
    {
        $queryBuilder = $this->createQueryBuilder('d') 
                ->where('d.dateDebuts >= :date')
                ->setParameter('date', $date)
                ->andWhere('d.consultant = :consultant')
                ->setParameter('consultant', $consultant)
                ->orderBy("d.id", "ASC")
                ;
        
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }
}
