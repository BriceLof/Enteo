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
        // on recherche sur une période de 2 mois 
		$dateLimit = new \DateTime($date);
		$dateLimit->add(new \DateInterval('P2M'));

		$queryBuilder = $this->createQueryBuilder('d') 
                ->where('d.dateDebuts >= :date')
                ->setParameter('date', $date)
				->andWhere('d.dateDebuts <= :datelimit')
                ->setParameter('datelimit', $dateLimit)
                ->andWhere('d.consultant = :consultant')
                ->setParameter('consultant', $consultant)
                ->orderBy("d.dateDebuts", "ASC")
				
                ;
        
        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }
}
