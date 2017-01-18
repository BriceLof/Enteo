<?php

namespace Application\PlateformeBundle\Repository;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * BeneficiaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BeneficiaireRepository extends \Doctrine\ORM\EntityRepository
{   
    public function getBeneficiaire($page, $nbPerPage, $idConsultant = null)
    {
       $query = $this->createQueryBuilder('b')
            ->leftJoin('b.news', 'n')
            ->addSelect('n')
            ->leftJoin('b.ville', 'v')
            ->addSelect('v')
            ->orderBy('b.id', 'DESC')  
        ;
       
       if($idConsultant != null)
       {
            $query->where('b.consultant = :id')
                  ->setParameter('id', $idConsultant);   
       }
       
        $query->setFirstResult(($page-1) * $nbPerPage)
               ->setMaxResults($nbPerPage);
        
        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($query); 
    }
    
    public function search(Beneficiaire $beneficiaire, $debut, $fin, $idUtilisateur)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('Application\PlateformeBundle\Entity\Beneficiaire','b');
        //$rsm->addJoinedEntityFromClassMetadata('Application\PlateformeBundle\Entity\Ville', 'v', 'b', $beneficiaire, array( 'id' => 'vid' ));

        $query = 'SELECT b.* FROM beneficiaire b WHERE 1';
        $params = array();

        if(!is_null($beneficiaire->getNomConso())) {
            $query .= ' AND b.nom_conso LIKE :nomConso';
            $params['nomConso'] = "%".$beneficiaire->getNomConso()."%";
        }

        if(!is_null($beneficiaire->getPrenomConso())) {
            $query .= ' AND b.prenom_conso LIKE :prenomConso';
            $params['prenomConso'] = "%".$beneficiaire->getPrenomConso()."%";
        }

        if(!is_null($beneficiaire->getVille())) {
            $query .= ' AND b.ville_id = :villeId';
            $params['villeId'] = $beneficiaire->getVille()->getId();
        }

        if(!is_null($beneficiaire->getEmailConso())){
            $query .= ' AND b.emailConso LIKE :emailConso';
            $params['emailConso'] = '%'.$beneficiaire->getEmailConso().'%';
        }

        if(!is_null($idUtilisateur)){
            $query .= ' AND b.consultant_id = :consultantId';
            $params['consultantId'] = $idUtilisateur;
        }

        if(!is_null($debut)){
            $query .= ' AND b.date_conf_mer >= :dateDebut';
            $params['dateDebut'] = $debut;
        }

        if(!is_null($fin)){
            $query .= ' AND b.date_conf_mer <= :dateFin';
            $params['dateFin'] = $fin;
        }

        //$query .= " INNER JOIN ville v ON v.id = b.id WHERE v.cp LIKE '75%'";

        $query .= ' ORDER BY b.id DESC';

        $request = $this->getEntityManager()->createNativeQuery($query,$rsm);
        $request->setParameters($params);

        return $request;
    }
}
