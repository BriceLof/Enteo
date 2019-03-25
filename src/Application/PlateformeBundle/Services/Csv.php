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

    public function getCsvFromListBeneficiaire($beneficiaires)
    {

        $response = new StreamedResponse();
        $response->setCallback(function () use ($beneficiaires) {
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

                foreach ($result as $lastRv1) {
                    $dateRv1 = $lastRv1['dateDebut']->format('d/m/Y');
                }


                if (!is_null($beneficiaire->getAccompagnement())) {
                    if (!is_null($beneficiaire->getAccompagnement()->getFinanceur()[0])) {
                        $financeur1 = $beneficiaire->getAccompagnement()->getFinanceur()[0]->getNom();
                        $montant1 = $beneficiaire->getAccompagnement()->getFinanceur()[0]->getMontant();
                    }
                    if (!is_null($beneficiaire->getAccompagnement()->getFinanceur()[1])) {
                        $financeur2 = $beneficiaire->getAccompagnement()->getFinanceur()[1]->getNom();
                        $montant2 = $beneficiaire->getAccompagnement()->getFinanceur()[1]->getMontant();
                    }
                    if (!is_null($beneficiaire->getAccompagnement()->getDateDebut())) {
                        $dateDebut = $beneficiaire->getAccompagnement()->getDateDebut()->format('d/m/Y');
                    }
                    if (!is_null($beneficiaire->getAccompagnement()->getDateFin())) {
                        $dateFin = $beneficiaire->getAccompagnement()->getDateFin()->format('d/m/Y');
                    }
                    $duree = $beneficiaire->getAccompagnement()->getHeure();
                }

                if (!is_null($beneficiaire->getConsultant())) {
                    $consultant = ucfirst($beneficiaire->getConsultant()->getPrenom()[0]) . ". " . strtoupper($beneficiaire->getConsultant()->getNom());
                }

                if (!is_null($beneficiaire->getFactures())) {
                    foreach ($beneficiaire->getFactures() as $factureBene) {
                        $facture .= ' ' . $factureBene->getNumero() . ' ';
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

    public function getCsvFileEmployeur($beneficiaires)
    {
        $response = new StreamedResponse();
        $response->setCallback(function () use ($beneficiaires) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, array(
                'Employeur raison sociale',
                'Employeur ape nace',
                'Employeur cp',
                'Employeur ville',
                'Beneficiaire id',
                'Beneficiaire nom',
                'Beneficiaire prenom',
                'Beneficiaire cp',
                'Beneficiaire ville',
                'Beneficiaire statut commercial',
                'Beneficiaire statut administratif',
                'Beneficiaire detail statut administratif',
                'Contact employeur nature',
                'Contact employeur civilite',
                'Contact employeur nom',
                'Contact employeur prenom',
                'Contact employeur tel',
                'Contact employeur email',
                'Financeur 1 nom',
                'Financeur 1 organisme',
                'Financeur 2 nom',
                'Financeur 2 organisme',
            ), ';');

            foreach ($beneficiaires as $beneficiaire) {

                $employeur = $beneficiaire->getEmployeur();
                $contact = $beneficiaire->getContactEmployeur()[0];
                $financeur1 = $beneficiaire->getAccompagnement()->getFinanceur()[0];
                $financeur2 = $beneficiaire->getAccompagnement()->getFinanceur()[1];

                fputcsv(
                    $handle,
                    array(
                        $employeur->getRaisonSociale(),
                        $employeur->getApeNace(),
                        !is_null($employeur->getVille()) ? utf8_decode($employeur->getVille()->getCp()) : '',
                        !is_null($employeur->getVille()) ? utf8_decode($employeur->getVille()->getNom()) : '',
                        $beneficiaire->getId(),
                        $beneficiaire->getNomConso(),
                        $beneficiaire->getPrenomConso(),
                        $beneficiaire->getVille()->getCp(),
                        $beneficiaire->getVille()->getNom(),
                        !is_null($beneficiaire->getLastDetailStatutCommercial()) ? utf8_decode($beneficiaire->getLastDetailStatutCommercial()->getStatut()->getNom()) : '',
                        !is_null($beneficiaire->getLastDetailStatutAdmin()) ? utf8_decode($beneficiaire->getLastDetailStatutAdmin()->getStatut()->getNom()) : '',
                        !is_null($beneficiaire->getLastDetailStatutAdmin()) ? utf8_decode($beneficiaire->getLastDetailStatutAdmin()->getDetail()) : '',
                        $contact->getNature(),
                        $contact->getCivilite(),
                        utf8_decode($contact->getNom()),
                        utf8_decode($contact->getPrenom()),
                        $contact->getTel(),
                        $contact->getEmail(),
                        !is_null($beneficiaire->getAccompagnement()) ? $financeur1->getNom() : "",
                        !is_null($beneficiaire->getAccompagnement()) ? $financeur1->getOrganisme() : "",
                        !is_null($beneficiaire->getAccompagnement()) ? $financeur2->getNom() : "",
                        !is_null($beneficiaire->getAccompagnement()) ? $financeur2->getOrganisme() : ""
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

    public function getCsvFromEcoleBeneficiaire($beneficiaires)
    {

        $response = new StreamedResponse();
        $response->setCallback(function () use ($beneficiaires) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, array(
                utf8_decode('École/Université'),
                utf8_decode('diplome visé'),
                utf8_decode('Nom Prénom'),
                utf8_decode('Téléphone'),
                utf8_decode('Date Début'),
                'Date Fin',
                'statut'
            ), ';');

            foreach ($beneficiaires as $beneficiaire) {
                $dateDebut = null;
                $dateFin = null;


                if (!is_null($beneficiaire->getAccompagnement())) {
                    if (!is_null($beneficiaire->getAccompagnement()->getDateDebut())) {
                        $dateDebut = $beneficiaire->getAccompagnement()->getDateDebut()->format('d/m/Y');
                    }
                    if (!is_null($beneficiaire->getAccompagnement()->getDateFin())) {
                        $dateFin = $beneficiaire->getAccompagnement()->getDateFin()->format('d/m/Y');
                    }
                }


                fputcsv(
                    $handle,
                    array(
                        utf8_decode($beneficiaire->getEcoleUniversite()),
                        utf8_decode($beneficiaire->getDiplomeVise()),
                        utf8_decode($beneficiaire->getNomConso()) . ' ' . utf8_decode($beneficiaire->getPrenomConso()),
                        $dateDebut,
                        $dateFin,
                        utf8_decode($beneficiaire->getDiplomeVise()),
                        utf8_decode($beneficiaire->getStatut()),
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

    public function getCsvForFicheNonMaj($historiques)
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array(
            'date = ' . (new \DateTime('now'))->modify('-15 day')->format('d-m-Y')
        ));
        fputcsv($handle, array());
        fputcsv($handle, array(
            'date RV',
            'Type Rv',
            utf8_decode('Bénéficiaire'),
            'Consultant',
            utf8_decode('Tél Consultant'),
            'Ville',
        ), ';');

        foreach ($historiques as $historique) {
            $beneficiaire = $historique->getBeneficiaire();
            $consultant = $historique->getConsultant();


            fputcsv(
                $handle,
                array(
                    $historique->getDateDebut()->format('d-m-Y'),
                    $historique->getSummary(),
                    utf8_decode($beneficiaire->getPrenomConso() . ' ' . strtoupper($beneficiaire->getNomConso())),
                    utf8_decode($consultant->getPrenom() . ' ' . strtoupper($consultant->getNom())),
                    $consultant->getTel1(),
                    (is_null($historique->getBureau())) ? 'Distanciel' : $historique->getBureau()->getVille()->getNom(),
                ),
                ';'
            );
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return $csv;
    }

    public function getCvsForMailSuivi($suivis, $statut)
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, array(
            'date = ' . (new \DateTime('now'))->format('d-m-Y')
        ));
        fputcsv($handle, array(
            'statut = ' . $statut
        ));
        fputcsv($handle, array());
        fputcsv($handle, array(
            'id',
            'Nom',
            'Prenom',
            'financeur',
            'date',
        ), ';');

        foreach ($suivis as $suivi) {

            $beneficiaire = $suivi->getBeneficiaire();

            $financeur1 = null;
            $financeur2 = null;
            $date = null;
            $consultant = null;


            fputcsv(
                $handle,
                array(
                    $beneficiaire->getId(),
                    utf8_decode($beneficiaire->getNomConso()),
                    utf8_decode($beneficiaire->getPrenomConso()),
                    $beneficiaire->getTypeFinanceur() . ' ' . $beneficiaire->getOrganisme(),
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

    public function getCvsForMailNews($news)
    {
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

            if (!is_null($beneficiaire->getConsultant())) {
                $consultant = ucfirst($beneficiaire->getConsultant()->getPrenom()[0]) . ". " . strtoupper($beneficiaire->getConsultant()->getNom());
            }

            $nomBeneficiaire = ucfirst($beneficiaire->getCiviliteConso()) . ' ' . ucfirst($beneficiaire->getPrenomConso()) . ' ' . strtoupper($beneficiaire->getNomConso());

            if (!is_null($beneficiaire->getNouvelle()[count($beneficiaire->getNouvelle()) - 1])) {
                $lastNouvelle = $beneficiaire->getNouvelle()[count($beneficiaire->getNouvelle()) - 1];
                $nouvelle = $lastNouvelle->getTitre() . " : " . $lastNouvelle->getMessage();
            }

            fputcsv(
                $handle,
                array(
                    $beneficiaire->getId(),
                    utf8_decode($nomBeneficiaire),
                    $beneficiaire->getTelConso(),
                    $beneficiaire->getDateConfMer()->format('d-m-Y'),
                    $beneficiaire->getVilleMer()->getNom() . " (" . $beneficiaire->getVilleMer()->getCp(),
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

    public function getCsvForFacture($factures)
    {
//        dump($factures);
//        foreach ($factures as $facture) {
//            $historiquesFactures = $this->em->getRepository('ApplicationPlateformeBundle:HistoriquePaiementFacture')->findByFacture($facture);
//            dump(count($historiquesFactures));
//        }
//        exit;
        $response = new StreamedResponse();
        $response->setCallback(function () use ($factures) {
            $handle = fopen('php://output', 'w+');
            fputcsv($handle, array(
                utf8_decode('Bénéficiaire'),
                'Ville mer',
                utf8_decode('Début accompagnement'),
                'Fin accompagnement',
                'Financeur',
                'Consultant',
                utf8_decode('Numéro'),
                'Date',
                'Montant facture',
                utf8_decode('Montant réglé'),
                utf8_decode('Statut création'),
                'Statut facture',
                utf8_decode('Mode de réglement'),
                'Banque',
                utf8_decode('Date de réglement'),
                utf8_decode('Montant'),
                'Commentaire',
            ), ';');

            foreach ($factures as $facture) {
                if ($facture->getOuvert() == 1) $statutCreation = "Ouvert";
                else $statutCreation = "Fermer";

                $tabValue = array(
                    ucwords(utf8_decode($facture->getBeneficiaire()->getNomConso() . " " . $facture->getBeneficiaire()->getPrenomConso())),
                    $facture->getBeneficiaire()->getVilleMer()->getNom(),
                    $facture->getDateDebutAccompagnement()->format('d-m-Y'),
                    $facture->getDateFinAccompagnement()->format('d-m-Y'),
                    utf8_decode($facture->getFinanceur()),
                    utf8_decode($facture->getBeneficiaire()->getConsultant()->getNom() . " " . $facture->getBeneficiaire()->getConsultant()->getPrenom()),
                    $facture->getNumero(),
                    $facture->getDate()->format('d-m-Y'),
                    $facture->getMontant(),
                    $facture->getMontantPayer(),
                    $statutCreation,
                    ucfirst($facture->getStatut()),
                );

                $historiquesFactures = $this->em->getRepository('ApplicationPlateformeBundle:HistoriquePaiementFacture')->findByFacture($facture);

                if(count($historiquesFactures) > 0){
                    $i = 0;

                    foreach ($historiquesFactures as $historiqueFacture) {

                        if (!is_null($historiqueFacture->getDatePaiement()) && $historiqueFacture->getDatePaiement() != '')
                            $datePaiementPrevu = $historiqueFacture->getDatePaiement()->format('d-m-Y');
                        else
                            $datePaiementPrevu = "N.C.";

                        if ($historiqueFacture->getModePaiement() == 'cheque') {
                            if (!is_null($historiqueFacture->getBanque()) && $historiqueFacture->getBanque() != '')
                                $banque = $historiqueFacture->getBanque();
                            else
                                $banque = "N.C.";
                        } else {
                            $banque = "";
                        }


                        array_push($tabValue, ucfirst($historiqueFacture->getModePaiement()), ucfirst(utf8_decode($banque)), $datePaiementPrevu, $historiqueFacture->getMontant(), ucfirst(utf8_decode($historiqueFacture->getCommentaire())));

                        if ($i == 0) {
                            fputcsv(
                                $handle,
                                $tabValue,
                                ';'
                            );
                        } else {
                            fputcsv(
                                $handle,
                                array(
                                    '', '', '', '', '', '', '', '', '', '', '', '',
                                    ucfirst($historiqueFacture->getModePaiement()),
                                    ucfirst(utf8_decode($banque)),
                                    $datePaiementPrevu,
                                    $historiqueFacture->getMontant(),
                                    ucfirst(utf8_decode($historiqueFacture->getCommentaire()))
                                ),
                                ';'
                            );
                        }
                        $i++;
                    }
                }else{
                    fputcsv(
                        $handle,
                        $tabValue,
                        ';'
                    );
                }

            }
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="facture_export.csv"');
        return $response;
    }
}