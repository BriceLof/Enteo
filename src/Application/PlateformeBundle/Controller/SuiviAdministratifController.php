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
        $form = $this->createForm(SuiviAdministratifType::class, $suiviAdministratif);

//        dernier suivi administratif
        $lastSuivi = null;
        if (!is_null($beneficiaire->getSuiviAdministratif())){
            $lastSuivi = $beneficiaire->getSuiviAdministratif()[count($beneficiaire->getSuiviAdministratif())-1];
        }

//        creation des critères pour la génération d'un contrat de mission
        $tab = [];
        $consultant = $beneficiaire->getConsultant();
        if (!is_null($consultant)){
            (is_null($consultant->getFacturation())) ? $tab [] = 'Manque Contact facturation Consultant' : null;
            (is_null($consultant->getContrat())) ? $tab [] = 'Manque Contrat cadre Consultant' : null;
        }
        (is_null($beneficiaire->getAccompagnement()->getDateDebut())) ? $tab [] = 'Manque Date debut Accompagnement' : null;
        (is_null($beneficiaire->getAccompagnement()->getDateFin())) ? $tab [] = 'Manque Date fin Accompagnement' : null;
        $autorisation = (empty($tab)) ? 'true' : 'false';

        $bool = 'false';
        foreach ($beneficiaire->getSuiviAdministratif() as $suivi){
            if (!is_null($suivi->getDetailStatut())){
                if ($suivi->getDetailStatut()->getId() == 21 || $suivi->getDetailStatut()->getId() == 22){
                    $bool = 'true';
                    break;
                }
            }
        }

        $stateMission = "pas de contrat envoyé";
        if (!is_null($beneficiaire->getMission())){
            $mission = $beneficiaire->getMission();
            if ($mission->getState() == 'new') $stateMission = "en attente acceptation Consultant";
            elseif ($mission->getState() == 'accepted') $stateMission = "en attente acceptation Enthéor";
            elseif ($mission->getState() == 'confirmed') $stateMission = "en cours";
            elseif ($mission->getState() == 'finished') $stateMission = "mission terminée";
        }elseif (!empty($beneficiaire->getMissionArchives())) $stateMission = "mission archivée";

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $suiviAdministratif = $form->getData();
            $suiviAdministratif->setBeneficiaire($beneficiaire);
            $em = $this->getDoctrine()->getManager();
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
                    'montant' => $form['tarif']->getData()
                ));
                $this->get('session')->getFlashBag()->add('info', 'Mission bien envoyé');
            }

            if (is_null($suiviAdministratif->getInfo()) && ($suiviAdministratif->getDetailStatut() == null || $lastSuivi->getDetailStatut() == $suiviAdministratif->getDetailStatut())){
                $em->detach($suiviAdministratif);
            }else{
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
            'factures' => $factures
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
        foreach ($suiviAdministratifs as $suiviAdministratif){
            if(stristr($suiviAdministratif->getInfo(),'Facture fermée N°') !== false){
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