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
    public function getBeneficiaire($page, $nbPerPage, $idConsultant = null, $cacher = false, $ids = null)
    {
        $query = $this->createQueryBuilder('b')
            ->leftJoin('b.news', 'n')
            ->addSelect('n')
            ->leftJoin('b.ville', 'v')
            ->addSelect('v')
            ->orderBy('b.id', 'DESC');

        if ($idConsultant != null) {
            $query->where('b.consultant = :id')
                ->setParameter('id', $idConsultant);
        } else if (!is_null($ids)) {
            $query->add('where', $query->expr()->in('b.consultant', $ids));
        }

        if ($cacher == true) {
            $query->andWhere('b.deleted = :deleted')
                ->setParameter('deleted', false);
        }

        $query->setFirstResult(($page - 1) * $nbPerPage)
            ->setMaxResults($nbPerPage);

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($query);
    }

    public function getBeneficiaireWithDetailStatut($detailStatut = null)
    {
        $queryBuilder = $this->createQueryBuilder("b")
            ->leftJoin('b.news', 'n')
            ->addSelect('n')
            ->leftJoin('n.detailStatut', 'ds')
            ->addSelect('ds')
            ->where('ds.detail NOT IN (:detailStatut)')
            ->setParameter('detailStatut', $detailStatut);

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }

    public function getBeneficiaireWithIdsAndDate($ids, $dateDebut, $dateFin)
    {
        $queryBuilder = $this->createQueryBuilder("b")
            ->where('b.id NOT IN (:ids)')
            ->andWhere('b.dateConfMer BETWEEN :dateDebut AND :dateFin')
            ->setParameters(array(
                "ids" => $ids,
                "dateDebut" => $dateDebut,
                "dateFin" => $dateFin
            ))
            ->orderBy('b.id', 'ASC');

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;
    }

    public function getBeneficiaireWithDate($dateDebut = null, $dateFin = null)
    {
        $queryBuilder = $this->createQueryBuilder("b")
            ->leftJoin('b.suiviAdministratif', 'sa')
            ->addSelect('sa')
            ->leftJoin('b.news', 'n')
            ->addSelect('n')
            ->leftJoin('n.detailStatut', 'ds')
            ->addSelect('ds')
            ->leftJoin('n.statut', 's')
            ->addSelect('s');


        if (!is_null($dateDebut) && !is_null($dateFin)) {
            $queryBuilder->where('b.dateConfMer BETWEEN :dateDebut AND :dateFin')
                ->setParameters(array("dateDebut" => $dateDebut, "dateFin" => $dateFin));
        } else {
            $dateCurrent = date('Y-m-d');
            $queryBuilder->where('b.dateConfMer LIKE :dateCurrent')
                ->setParameter("dateCurrent", $dateCurrent . '%');
        }

        $queryBuilder->orderBy('b.id', 'DESC');

        $query = $queryBuilder->getQuery();
        //var_dump($query->getDql());
        $results = $query->getResult();
        return $results;
    }


    /**
     * @param Beneficiaire $beneficiaire
     * @param $debut
     * @param $fin
     * @param null $idUtilisateur
     * @param bool $bool
     * @param int $tri
     * @param null $ville
     * @param null $detailStatut
     * @param null $complementStatut
     * @return mixed
     */
    public function search(Beneficiaire $beneficiaire, $debut, $fin, $idUtilisateur = null, $bool = false, $tri = 0, $ville = null, $statut = null, $detailStatut = null, $complementStatut = null, $cacher = false, $complementDetailStatut = null, $ids = null)
    {
        $type = 'suiviAdministratif';
        if (!is_null($statut) && $statut->getType() == 'commercial') {
            $type = 'news';
        }


        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('Application\PlateformeBundle\Entity\Beneficiaire', 'b');
        if (!is_null($ville)) {
            $rsm->addJoinedEntityFromClassMetadata('Application\PlateformeBundle\Entity\Ville', 'v', 'b', 'ville', array(
                'id' => 'v_id',
                'ville' => 'ville_nom'
            ));
        }

        $query = 'SELECT b.* FROM beneficiaire b';
        $params = array();

        if (!is_null($detailStatut) || !is_null($statut)) {
            if ($type == 'news') {
                $query .= ' INNER JOIN news n ON b.id = n.beneficiaire_id';
                $query .= ' INNER JOIN statut s ON s.id = n.statut_id';
            } elseif ($type == 'suiviAdministratif') {
                $query .= ' INNER JOIN suivi_administratif sa ON b.id = sa.beneficiaire_id';
                $query .= ' INNER JOIN statut s ON s.id = sa.statut_id';
            }
        }

        if (!is_null($ville)) {
            $query .= ' INNER JOIN ville v ON b.ville_mer_id = v.id';
            $params['villeMerId'] = $ville;
        }

        $query .= ' WHERE 1';

        if (!is_null($detailStatut) || !is_null($statut)) {
            if ($type == 'news') {
                $query .= ' AND n.id = (SELECT MAX(id)
                                         FROM news 
                                         WHERE beneficiaire_id = b.id
                                         AND detail_statut_id IS NOT NULL)';
            } elseif ($type == 'suiviAdministratif') {
                $query .= ' AND sa.id = (SELECT MAX(id)
                                         FROM suivi_administratif 
                                         WHERE beneficiaire_id = b.id
                                         AND detail_statut_id IS NOT NULL)';
            }
        }

        if (!is_null($ville)) {
            $query .= ' AND v.ville LIKE :villeNom';
            $params['villeNom'] = '%' . $ville . '%';
        }

        if (!is_null($detailStatut)) {
            if ($type == 'news') {
                $query .= ' AND n.detail_statut_id ' . $complementDetailStatut . ' :detailStatut';
            } else {
                $query .= ' AND sa.detail_statut_id ' . $complementDetailStatut . ' :detailStatut';
            }
            $params['detailStatut'] = $detailStatut->getId();
        }

        if (!is_null($statut)) {
            $query .= ' AND s.ordre ' . $complementStatut . ' :ordre';
            $params['ordre'] = $statut->getOrdre();
            if ($type == 'news') {
                $query .= ' AND n.statut_id ' . $complementStatut . ' :statut';
            } else {
                $query .= ' AND sa.statut_id ' . $complementStatut . ' :statut';
            }
            $params['statut'] = $statut->getId();
        }

        if (!is_null($beneficiaire->getNomConso())) {
            $query .= ' AND b.nom_conso LIKE :nomConso';
            $params['nomConso'] = "%" . $beneficiaire->getNomConso() . "%";
        }

        if ($cacher == true) {
            $query .= ' AND b.deleted  <> 1';
        }

        if (!is_null($beneficiaire->getPrenomConso())) {
            $query .= ' AND b.prenom_conso LIKE :prenomConso';
            $params['prenomConso'] = "%" . $beneficiaire->getPrenomConso() . "%";
        }

        if (!is_null($beneficiaire->getEmailConso())) {
            $query .= ' AND b.email_conso LIKE :emailConso';
            $params['emailConso'] = "%" . $beneficiaire->getEmailConso() . "%";
        }

        if (!is_null($beneficiaire->getConsultant())) {
            $query .= ' AND b.consultant_id = :consultant';
            $params['consultant'] = $beneficiaire->getConsultant()->getId();
        } else if (!is_null($idUtilisateur)) {
            if (!is_null($ids)) {
                $query .= ' AND b.consultant_id IN ('. implode(',', $ids). ')';
            } else {
                $query .= ' AND b.consultant_id = :consultantId';
                $params['consultantId'] = $idUtilisateur;
            }
        }

        if (!is_null($beneficiaire->getTelConso())) {
            $query .= ' AND (b.tel_conso LIKE :tel OR b.tel_2 LIKE :tel)';
            $params['tel'] = '%' . $beneficiaire->getTelConso() . '%';
        }

        if (!is_null($debut)) {
            $query .= ' AND b.date_conf_mer >= :dateDebut';
            $params['dateDebut'] = $debut;
        }

        if (!is_null($fin)) {
            $query .= ' AND b.date_conf_mer <= :dateFin';
            $params['dateFin'] = $fin;
        }

        if (!is_null($beneficiaire->getRefFinanceur())) {
            $query .= ' AND b.ref_financeur LIKE :refFinanceur';
            $params['refFinanceur'] = '%' . $beneficiaire->getRefFinanceur() . '%';
        }

        if ($tri === 0) {
            $query .= ' ORDER BY b.id DESC';
        } elseif ($tri === 1) {
            $query .= ' ORDER BY b.nom_conso ASC';
        } elseif ($tri === 2) {
            $query .= ' ORDER BY b.nom_conso DESC';
        } elseif ($tri === 3) {
            $query .= ' ORDER BY b.date_heure_mer ASC';
        } elseif ($tri === 4) {
            $query .= ' ORDER BY b.date_heure_mer DESC';
        }

        if ($bool == true) {
            $query .= ' LIMIT 10';
        }

        $request = $this->getEntityManager()->createNativeQuery($query, $rsm);
        $request->setParameters($params);

        return $request;
    }

    public function searchBeneficiaireByNom($string)
    {
        $queryBuilder = $this->createQueryBuilder("b")
            ->where("b.nomConso LIKE :nom")
            ->setParameter("nom", '%' . $string . '%')
            ->orderBy("b.id", "DESC");

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }

    public function searchByEcoleAndDate($debut = null, $fin = null)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('Application\PlateformeBundle\Entity\Beneficiaire', 'b');

        $query = 'SELECT b.* FROM beneficiaire b';
        $params = array();


        $query .= ' INNER JOIN accompagnement a ON a.id = b.accompagnement_id';

        $query .= ' WHERE b.ecole_universite IS NOT NULL';

        if (!is_null($debut)) {
            $query .= ' AND a.date_debut >= :debut';
            $params['debut'] = $debut;
        }

        if ((!is_null($fin))) {
            $query .= ' AND a.date_debut <= :fin';
            $params['fin'] = $fin;
        }

        $request = $this->getEntityManager()->createNativeQuery($query, $rsm);
        $request->setParameters($params);

        return $request;
    }
}