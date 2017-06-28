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

    /**
     * retourne les bénéficiaires correspondant aux conditions qui sont mis en parametre
     *
     * @param Beneficiaire $beneficiaire
     * @param $debut
     * @param $fin
     * @param null $idUtilisateur
     * @param bool $bool
     * @param $tri
     * @param $ville
     * @return \Doctrine\ORM\NativeQuery
     */
    public function search(Beneficiaire $beneficiaire, $debut, $fin, $idUtilisateur = null, $bool = false, $tri = 0, $ville = null)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('Application\PlateformeBundle\Entity\Beneficiaire','b');
        if(!is_null($ville)) {
            $rsm->addJoinedEntityFromClassMetadata('Application\PlateformeBundle\Entity\Ville', 'v', 'b', 'ville', array(
                'id' => 'v_id',
                'ville' => 'ville_nom'
            ));
        }

        $query = 'SELECT b.* FROM beneficiaire b';
        $params = array();

        if(!is_null($ville)) {
            $query .= ' INNER JOIN ville v ON b.ville_mer_id = v.id';
            $params['villeMerId'] = $ville;
        }

        $query .= ' WHERE 1';

        if(!is_null($ville)) {
            $query .= ' AND v.ville LIKE :villeNom';
            $params['villeNom'] = '%'.$ville.'%';
        }

        if(!is_null($beneficiaire->getNomConso())) {
            $query .= ' AND b.nom_conso LIKE :nomConso';
            $params['nomConso'] = "%".$beneficiaire->getNomConso()."%";
        }

        if(!is_null($beneficiaire->getPrenomConso())) {
            $query .= ' AND b.prenom_conso LIKE :prenomConso';
            $params['prenomConso'] = "%".$beneficiaire->getPrenomConso()."%";
        }

        if(!is_null($beneficiaire->getEmailConso())){
            $query .= ' AND b.email_conso LIKE :emailConso';
            $params['emailConso'] = "%".$beneficiaire->getEmailConso()."%";
        }
        
        if(!is_null($beneficiaire->getConsultant())){
            $query .= ' AND b.consultant_id = :consultant';
            $params['consultant'] = $beneficiaire->getConsultant()->getId();
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

        if(!is_null($beneficiaire->getRefFinanceur())){
            $query .= ' AND b.ref_financeur LIKE :refFinanceur';
            $params['refFinanceur'] = '%'.$beneficiaire->getRefFinanceur().'%';
        }

        if ($tri === 0) {
            $query .= ' ORDER BY b.id DESC';
        }elseif ($tri === 1){
            $query .= ' ORDER BY b.nom_conso ASC';
        }elseif ($tri === 2){
            $query .= ' ORDER BY b.nom_conso DESC';
        }elseif ($tri === 3){
            $query .= ' ORDER BY b.date_heure_mer ASC';
        }elseif ($tri === 4){
            $query .= ' ORDER BY b.date_heure_mer DESC';
        }

        if ($bool == true){
            $query .= ' LIMIT 10';
        }

        $request = $this->getEntityManager()->createNativeQuery($query,$rsm);
        $request->setParameters($params);

        return $request;
    }
}
