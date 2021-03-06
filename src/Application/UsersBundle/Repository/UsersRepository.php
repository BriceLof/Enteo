<?php
namespace Application\UsersBundle\Repository;
use Application\UsersBundle\Entity\Users;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * UsersRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsersRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByTypeUser($slugType, $actif = NULL )
    {    
        $qb = $this->createQueryBuilder('u');
        
        $qb ->where('u.roles LIKE :type')
            ->setParameter('type', '%'.$slugType.'%');

        if($actif !== NULL){
            $qb ->andWhere('u.enabled = :actif')
            ->setParameter('actif', $actif);
        }
        
        $qb->orderBy('u.nom', "ASC");
            
        return $qb
          ->getQuery()
          ->getResult()
        ; 
    }
    
    /**
     * récupère les consultants
     *
     * @param String $role
     * @return \Doctrine\ORM\NativeQuery
     */
    public function search($role = "ROLE_CONSULTANT")
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('Application\UsersBundle\Entity\Users','u');

        $query = 'SELECT u.* FROM users u WHERE 1';
        $params = array();


        $query .= ' AND u.roles LIKE :roles';
        $params['roles'] = "%".$role."%";

        $request = $this->getEntityManager()->createNativeQuery($query,$rsm);
        $request->setParameters($params);

        return $request;
    }
    
    public function findByTypeAndExclude($arrayId, $type)
    {    
		$qb = $this->createQueryBuilder('u')
                ->where('u.id NOT IN (:array)')
                ->setParameter('array', $arrayId)
                ->andWhere('u.roles LIKE :type')
                ->setParameter('type', '%'.$type.'%')
				->orderBy('u.nom', 'ASC')
                ;
       
        return $qb->getQuery()->getResult();
    }
}
