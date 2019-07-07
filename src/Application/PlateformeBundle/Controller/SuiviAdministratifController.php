<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Form\SuiviAdministratifType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Application\PlateformeBundle\Entity\Historique;

/**
 * SuiviAdministratif
 *
 */
class SuiviAdministratifController extends Controller
{

    /**
     * Creates a new SuiviAdministratif entity.
     *
     */
    public function newAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        $suiviAdministratif = new SuiviAdministratif();
        if (!is_null($beneficiaire->getLastDetailStatutAdmin()) && $beneficiaire->getLastDetailStatutAdmin()->getStatut()->getType() != "commercial") {
            $suiviAdministratif->setDetailStatut($beneficiaire->getLastDetailStatutAdmin());
            $suiviAdministratif->setStatut($beneficiaire->getLastDetailStatutAdmin()->getStatut());
        }
        $form = $this->createForm(SuiviAdministratifType::class, $suiviAdministratif);

//        creation des critères pour la génération d'un contrat de mission
        $tab = [];
        $consultant = $beneficiaire->getConsultant();
        if (!is_null($consultant)) {
            (is_null($consultant->getFacturation())) ? $tab [] = 'Contact facturation Consultant' : null;

            $boolContrat = false;

            foreach ($consultant->getContrats() as $contrat){
                if ($contrat->getEnabled() == true){
                    $boolContrat = true;
                    break;
                }
            }
            if ($boolContrat == false){
                $tab [] = 'Contrat cadre Consultant';
            }
        }
        (is_null($beneficiaire->getAccompagnement()->getDateDebut())) ? $tab [] = 'Date debut Accompagnement' : null;
        (is_null($beneficiaire->getAccompagnement()->getDateFin())) ? $tab [] = 'Date fin Accompagnement' : null;
        (is_null($beneficiaire->getAccompagnement()->getHeure()) || ($beneficiaire->getAccompagnement()->getHeure() == 0)) ? $tab [] = 'Accompagnement en Heures' : null;
        $autorisation = (empty($tab)) ? 'true' : 'false';

        $bool = 'false';
        foreach ($beneficiaire->getSuiviAdministratif() as $suivi) {
            if (!is_null($suivi->getDetailStatut())) {
                if ($suivi->getDetailStatut()->getId() == 21 || $suivi->getDetailStatut()->getId() == 22) {
                    $bool = 'true';
                    break;
                }
            }
        }

        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->findOneBy(array(
            "beneficiaire" => $beneficiaire
        ));
        $stateMission = "pas de contrat envoyé";
        if (!is_null($mission)) {
            if ($mission->getState() == 'new') $stateMission = "en attente acceptation Consultant";
            elseif ($mission->getState() == 'accepted') $stateMission = "en attente acceptation Enthéor";
            elseif ($mission->getState() == 'confirmed') $stateMission = "en cours";
            elseif ($mission->getState() == 'finished') $stateMission = "mission terminée";
        } elseif (!$beneficiaire->getMissionArchives()->isEmpty()) $stateMission = "mission archivée";

        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isSubmitted()) {
            $suiviAdministratif = $form->getData();
            $suiviAdministratif->setBeneficiaire($beneficiaire);
            $em = $this->getDoctrine()->getManager();

            if ($form['detailStatutHidden']->getData() != '' && !is_null($form['detailStatutHidden']->getData())) {
                $detailStatut = $em->getRepository('ApplicationPlateformeBundle:DetailStatut')->find($form['detailStatutHidden']->getData());
                $suiviAdministratif->setDetailStatut($detailStatut);
            }

            if (!is_null($suiviAdministratif->getStatut())) {
                if ($suiviAdministratif->getStatut()->getSlug() == "recevabilite") {
                    $historique = new Historique();
                    $historique->setHeuredebut(new \DateTime('now'));
                    $historique->setHeurefin(new \DateTime('now'));
                    $historique->setSummary("");
                    $historique->setTypeRdv("");
                    $historique->setBeneficiaire($beneficiaire);
                    $historique->setDescription($suiviAdministratif->getDetailStatut()->getDetail());
                    $historique->setEventId("0");
                    $em->persist($historique);
                }
            }

            if (!is_null($beneficiaire->getAccompagnement())) {
                $financeur = $beneficiaire->getAccompagnement()->getFirstFinanceur();
                if ($financeur != null) {
                    $financeur->setNom($form['typeFinanceur']->getData());
                    $financeur->setOrganisme($form['organisme']->getData());
                    $financeur->setAdresse($form['adresseFinanceur']->getData());
                    $financeur->setComplementAdresse($form['complementAdresseFinanceur']->getData());
                    $financeur->setCp($form['cpFinanceur']->getData());
                    $financeur->setVille($form['villeFinanceur']->getData());
                    $financeur->setCiviliteContact($form['civiliteContactFinanceur']->getData());
                    $financeur->setNomContact($form['nomContactFinanceur']->getData());
                    $financeur->setPrenomContact($form['prenomContactFinanceur']->getData());
                    $financeur->setTelContact($form['telContactFinanceur']->getData());
                    $financeur->setEmailContact($form['emailContactFinanceur']->getData());
                    $em->persist($financeur);
                }
            }
            $beneficiaire->setTypeFinanceur($form['typeFinanceur']->getData());
            $beneficiaire->setOrganisme($form['organisme']->getData());
            $em->persist($beneficiaire);

            //  c'est la fonction qui permet appelle la mission
            if ($form['mission']->getData() == 'true') {
                $this->forward('ApplicationUsersBundle:Mission:new', array(
                    'idBeneficiaire' => $beneficiaire->getId(),
                    'idConsultant' => $beneficiaire->getConsultant()->getId(),
                    'montant' => $form['tarif']->getData(),
                    'duree' => $form['dureeMission']->getData(),
                ));
                $this->get('session')->getFlashBag()->add('info', 'Mission bien envoyée');
            }

            if ($suiviAdministratif->getDetailStatut() == null || $beneficiaire->getLastDetailStatutAdmin() == $suiviAdministratif->getDetailStatut()) {
                if (!is_null($suiviAdministratif->getInfo())) {
                    $suiviAdministratifT = new SuiviAdministratif();
                    $suiviAdministratifT->setBeneficiaire($beneficiaire);
                    $suiviAdministratifT->setInfo($suiviAdministratif->getInfo());
                    $em->persist($suiviAdministratifT);
                } else {
                    $em->detach($suiviAdministratif);
                }
            } else {
                $em->persist($suiviAdministratif);
                $this->get('session')->getFlashBag()->add('info', 'Suivi Administratif bien enregistré');
            }

            $em->flush();

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'beneficiaire' => $beneficiaire,
                'id' => $beneficiaire->getId(),
            )));
        }

        $factureRepo = $em->getRepository('ApplicationPlateformeBundle:Facture');
        $factures = $factureRepo->findByBeneficiaire($beneficiaire);

        return $this->render('ApplicationPlateformeBundle:SuiviAdministratif:new.html.twig', array(
            'suiviAdministratif' => $suiviAdministratif,
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
            'tab' => $tab,
            'autorisation' => $autorisation,
            'afficher' => $bool,
            'stateMission' => $stateMission,
            'factures' => $factures,
            'mission' => $mission
        ));
    }

    /**
     * Finds and displays all SuiviAdministratif entity.
     *
     */
    public function showAllAction(Beneficiaire $beneficiaire)
    {
        $suiviAdministratifs = $beneficiaire->getSuiviAdministratif();

        $tabFactureClosed = array();
        foreach ($suiviAdministratifs as $suiviAdministratif) {
            if (stristr($suiviAdministratif->getInfo(), 'Facture fermée N°') !== false) {
                $tabFactureClosed[] = $suiviAdministratif->getInfo();
            }
        }
        return $this->render('ApplicationPlateformeBundle:SuiviAdministratif:showAll.html.twig', array(
            'beneficiaire' => $beneficiaire,
            'tabFactureClosed' => $tabFactureClosed
        ));
    }

    /**
     * Deletes a SuiviAdministratif entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $suiviAdministratif = $em->getRepository('ApplicationPlateformeBundle:SuiviAdministratif')->find($id);
        $beneficiaire = $suiviAdministratif->getBeneficiaire();


        if (!$suiviAdministratif) {
            throw $this->createNotFoundException('Unable to find SuiviAdministratif.');
        }

        $em->remove($suiviAdministratif);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Suivi Administratif bien supprimé');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }

    /**
     * Edits an existing SuiviAdministratif entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $suiviAdministratif = $em->getRepository('ApplicationPlateformeBundle:SuiviAdministratif')->find($id);
        $beneficiaire = $suiviAdministratif->getBeneficiaire();

        if (!$suiviAdministratif) {
            throw $this->createNotFoundException('Unable to find SuiviAdministratif entity.');
        }

        $editForm = $this->createEditForm($suiviAdministratif);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Suivi Administratif bien modifié');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:SuiviAdministratif:edit.html.twig', array(
            'suiviAdministratif' => $suiviAdministratif,
            'beneficiaire' => $beneficiaire,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a SuiviAdministratif entity.
     *
     * @param SuiviAdministratif $suiviAdministratif The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(SuiviAdministratif $suiviAdministratif)
    {
        $form = $this->createForm(SuiviAdministratifType::class, $suiviAdministratif);

        return $form;
    }
}