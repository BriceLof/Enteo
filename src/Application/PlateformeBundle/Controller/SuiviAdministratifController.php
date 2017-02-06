<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Form\SuiviAdministratifType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


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


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if($form['quoi']->getData()== 'Facture solde'){

                //il faudrait ajour le consultant plus tard
                $this->get('application_plateforme.mail')->sendFactureSoldeMessage($beneficiaire);
            }
            $suiviAdministratif = $form->getData();
            $suiviAdministratif->setBeneficiaire($beneficiaire);
            $em = $this->getDoctrine()->getManager();
            $em->persist($suiviAdministratif);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Suivi Administratif bien enregistré');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'beneficiaire' => $beneficiaire,
                'id' => $beneficiaire->getId(),
            )));
        }

        return $this->render('ApplicationPlateformeBundle:SuiviAdministratif:new.html.twig', array(
            'suiviAdministratif' => $suiviAdministratif,
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays all SuiviAdministratif entity.
     *
     */
    public function showAllAction(Beneficiaire $beneficiaire)
    {
        return $this->render('ApplicationPlateformeBundle:SuiviAdministratif:showAll.html.twig', array(
            'beneficiaire' => $beneficiaire,
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
            'suiviAdministratif'      => $suiviAdministratif,
            'beneficiaire' => $beneficiaire,
            'edit_form'   => $editForm->createView(),
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