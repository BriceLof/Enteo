<?php

namespace Application\PlateformeBundle\Repository;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * FactureRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FactureRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllFacture($page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC')
        ;

        $query->setFirstResult(($page-1) * $nbPerPage)
            ->setMaxResults($nbPerPage);

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($query);
    }

    public function search($statut = null,
           $dateDebutAccompagnement = null, $dateFinAccompagnement = null,
           $dateDebutAccompagnementStart = null, $dateDebutAccompagnementEnd = null,
           $dateFinAccompagnementStart = null, $dateFinAccompagnementEnd = null,
           $dateFactureStart = null, $dateFactureEnd = null,
           $consultant = null, Beneficiaire $beneficiaire = null,
           $numeroFacture = null, $financeur = null, $ville = null
    )
    {

        $queryBuilder = $this->createQueryBuilder("f")
            ->innerJoin('f.beneficiaire', 'b')
            ->addSelect('b')
            ->innerJoin('b.ville', 'v')
            ->addSelect('v');
        $arrayParameters = array();

        if(!is_null($consultant)){
            // Il me faut une liste de beneficiaire
            $queryBuilder->leftJoin('f.beneficiaire', 'b')
                ->addSelect('b');
        }

        if(!is_null($beneficiaire)){
            $queryBuilder->andWhere("f.beneficiaire = :beneficiaire");
            $arrayParameters['beneficiaire'] = $beneficiaire;
        }

        if(!is_null($statut)){
            $queryBuilder->andWhere("f.statut = :statut");
            $arrayParameters['statut'] = $statut;
        }

        // Date a gérer en égalité ou en interval

        if(!is_null($dateDebutAccompagnement) && !is_null($dateFinAccompagnement)){
            $queryBuilder->andWhere("f.dateDebutAccompagnement >= :dateDebutAccompagnement AND f.dateFinAccompagnement <= :dateFinAccompagnement");
            $arrayParameters['dateDebutAccompagnement'] = $dateDebutAccompagnement->format('Y-m-d');
            $arrayParameters['dateFinAccompagnement'] = $dateFinAccompagnement->format('Y-m-d');
        }

        if(!is_null($dateDebutAccompagnementStart) && !is_null($dateDebutAccompagnementEnd)){
            $queryBuilder->andWhere("f.dateDebutAccompagnement >= :dateDebutAccompagnementStart AND f.dateDebutAccompagnement <= :dateDebutAccompagnementEnd");
            $arrayParameters['dateDebutAccompagnementStart'] = $dateDebutAccompagnementStart->format('Y-m-d');
            $arrayParameters['dateDebutAccompagnementEnd'] = $dateDebutAccompagnementEnd->format('Y-m-d');
        }
        if(!is_null($dateFinAccompagnementStart) && !is_null($dateFinAccompagnementEnd)){
            $queryBuilder->andWhere("f.dateFinAccompagnement >= :dateFinAccompagnementStart AND f.dateFinAccompagnement <= :dateFinAccompagnementEnd");
            $arrayParameters['dateFinAccompagnementStart'] = $dateFinAccompagnementStart->format('Y-m-d');
            $arrayParameters['dateFinAccompagnementEnd'] = $dateFinAccompagnementEnd->format('Y-m-d');
        }

        if(!is_null($dateFactureStart) && !is_null($dateFactureEnd)){
            $queryBuilder->andWhere("f.date >= :dateFactureStart AND f.date <= :dateFactureEnd");
            $arrayParameters['dateFactureStart'] = $dateFactureStart->format('Y-m-d');
            $arrayParameters['dateFactureEnd'] = $dateFactureEnd->format('Y-m-d');
        }

        if(!is_null($consultant)){
            $queryBuilder->andWhere("b.consultant = :consultant");
            $arrayParameters['consultant'] = $consultant;
        }

        if(!is_null($numeroFacture)){
            $queryBuilder->andWhere("f.numero LIKE :numFactu");
            $arrayParameters['numFactu'] = '%'.$numeroFacture.'%';
        }

        if(!is_null($financeur)){
            $queryBuilder->andWhere("f.financeur LIKE :financeur");
            $arrayParameters['financeur'] = '%'.$financeur.'%';
        }

        if(!is_null($ville)){
            $queryBuilder->andWhere("v.nom LIKE :ville");
            $arrayParameters['ville'] = '%'.$ville.'%';
        }
        $queryBuilder->setParameters($arrayParameters);

        $queryBuilder->orderBy("f.id", "DESC");

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();
        return $results;

    }
}
