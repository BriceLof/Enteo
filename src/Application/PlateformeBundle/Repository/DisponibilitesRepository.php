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
                   
                ->where('d.dateDebuts >= :date')
                ->setParameter('date', $date)
                ->orderBy("d.id", "DESC")
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
                ->orderBy("d.id", "DESC")
                ;
        
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }
}
