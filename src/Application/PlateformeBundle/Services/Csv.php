<?php

namespace Application\PlateformeBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections;

class Csv
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getCsvFromListBeneficiaire($beneficiaires){

        $response = new StreamedResponse();
        $response->setCallback(function() use ($beneficiaires) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, array(
                'id',
                'Nom',
                'Prenom',
                utf8_decode('Téléphone'),
                'dateConfMer',
                'date RV1',
                'financeur 1',
                'montant',
                'financeur 2',
                'montant',
                'dateDebut',
                'dateFin',
                utf8_decode('durée (heure)'),
                'diplome vise',
                'consultant',
                'facture'
                ), ';');

            foreach ($beneficiaires as $beneficiaire) {

                $dateRv1 = null;
                $financeur1 = null;
                $financeur2 = null;
                $montant1 = null;
                $montant2 = null;
                $dateDebut = null;
                $dateFin = null;
                $duree = null;
                $consultant = null;
                $facture = null;

                $query = $this->em->getRepository('ApplicationPlateformeBundle:Historique')->getLastRv1($beneficiaire, 'RV1', 1, 1);
                $result = $query->getArrayResult();

                foreach ($result as $lastRv1){
                    $dateRv1 =  $lastRv1['dateDebut']->format('d/m/Y');
                }


                if (!is_null($beneficiaire->getAccompagnement())){
                    if (!is_null($beneficiaire->getAccompagnement()->getFinanceur()[0])){
                        $financeur1 = $beneficiaire->getAccompagnement()->getFinanceur()[0]->getNom();
                        $montant1 = $beneficiaire->getAccompagnement()->getFinanceur()[0]->getMontant();
                    }
                    if (!is_null($beneficiaire->getAccompagnement()->getFinanceur()[1])) {
                        $financeur2 = $beneficiaire->getAccompagnement()->getFinanceur()[1]->getNom();
                        $montant2 = $beneficiaire->getAccompagnement()->getFinanceur()[1]->getMontant();
                    }
                    if ( !is_null($beneficiaire->getAccompagnement()->getDateDebut())) {
                        $dateDebut = $beneficiaire->getAccompagnement()->getDateDebut()->format('d/m/Y');
                    }
                    if ( !is_null($beneficiaire->getAccompagnement()->getDateFin())) {
                        $dateFin = $beneficiaire->getAccompagnement()->getDateFin()->format('d/m/Y');
                    }
                    $duree = $beneficiaire->getAccompagnement()->getHeure();
                }

                if (!is_null($beneficiaire->getConsultant())){
                    $consultant = ucfirst($beneficiaire->getConsultant()->getPrenom()[0]).". ".strtoupper($beneficiaire->getConsultant()->getNom());
                }

                if (!is_null($beneficiaire->getFactures())){
                    foreach ($beneficiaire->getFactures() as $factureBene){
                        $facture .= ' '.$factureBene->getNumero().' ';
                    }
                }


                fputcsv(
                    $handle,
                    array(
                        $beneficiaire->getId(),
                        utf8_decode($beneficiaire->getNomConso()),
                        utf8_decode($beneficiaire->getPrenomConso()),
                        $beneficiaire->getTelConso(),
                        $beneficiaire->getDateConfMer()->format('d-m-Y'),
                        $dateRv1,
                        $financeur1,
                        $montant1,
                        $financeur2,
                        $montant2,
                        $dateDebut,
                        $dateFin,
                        $duree,
                        utf8_decode($beneficiaire->getDiplomeVise()),
                        $consultant,
                        $facture
                        ),
                    ';'
                );
            }
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
        return $response;
    }

    public function getCvsForMailSuivi($suivis, $statut){
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array(
            'date = '.(new \DateTime('now'))->modify('-15 day')->format('d-m-Y')
        ));
        fputcsv($handle, array(
            'statut = '.$statut
        ));
        fputcsv($handle, array());
        fputcsv($handle, array(
            'id',
            'Nom',
            'Prenom',
            'consultant',
            'financeur 1',
            'financeur 2',
            'date',
        ), ';');

        foreach ($suivis as $suivi) {

            $beneficiaire = $suivi->getBeneficiaire();

            $financeur1 = null;
            $financeur2 = null;
            $date = null;
            $consultant = null;


            if (!is_null($beneficiaire->getAccompagnement())){
                if (!is_null($beneficiaire->getAccompagnement()->getFinanceur()[0])){
                    $financeur1 = $beneficiaire->getAccompagnement()->getFinanceur()[0]->getNom();
                }
                if (!is_null($beneficiaire->getAccompagnement()->getFinanceur()[1])) {
                    $financeur2 = $beneficiaire->getAccompagnement()->getFinanceur()[1]->getNom();
                }
            }

            if (!is_null($beneficiaire->getConsultant())){
                $consultant = ucfirst($beneficiaire->getConsultant()->getPrenom()[0]).". ".strtoupper($beneficiaire->getConsultant()->getNom());
            }


            fputcsv(
                $handle,
                array(
                    $beneficiaire->getId(),
                    utf8_decode($beneficiaire->getNomConso()),
                    utf8_decode($beneficiaire->getPrenomConso()),
                    $consultant,
                    $financeur1,
                    $financeur2,
                    $suivi->getDate()->format('d-m-Y'),
                ),
                ';'
            );
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return $csv;
    }

    public function getCvsForMailNews($news){
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array(
            'id',
            'beneficiaire',
            'telephone',
            'date_conf_mer',
            'ville_mer',
            'consultant',
            'state',
            'derniere news',
        ), ';');

        foreach ($news as $new) {

            $beneficiaire = $new->getBeneficiaire();

            $consultant = null;
            $nouvelle = null;

            if (!is_null($beneficiaire->getConsultant())){
                $consultant = ucfirst($beneficiaire->getConsultant()->getPrenom()[0]).". ".strtoupper($beneficiaire->getConsultant()->getNom());
            }

            $nomBeneficiaire = ucfirst($beneficiaire->getCiviliteConso()).' '.ucfirst($beneficiaire->getPrenomConso()).' '.strtoupper($beneficiaire->getNomConso());

            if (!is_null($beneficiaire->getNouvelle()[count($beneficiaire->getNouvelle()) - 1])){
                $lastNouvelle = $beneficiaire->getNouvelle()[count($beneficiaire->getNouvelle()) - 1];
                $nouvelle = $lastNouvelle->getTitre()." : ".$lastNouvelle->getMessage();
            }

            fputcsv(
                $handle,
                array(
                    $beneficiaire->getId(),
                    utf8_decode($nomBeneficiaire),
                    $beneficiaire->getTelConso(),
                    $beneficiaire->getDateConfMer()->format('d-m-Y'),
                    $beneficiaire->getVilleMer()->getNom()." (".$beneficiaire->getVilleMer()->getCp(),
                    $consultant,
                    $new->getDetailStatut()->getDetail(),
                    $nouvelle
                ),
                ';'
            );
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return $csv;
    }
}