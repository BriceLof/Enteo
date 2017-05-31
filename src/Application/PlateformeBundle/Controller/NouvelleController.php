<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Nouvelle;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Application\PlateformeBundle\Entity\DetailStatut;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Form\NouvelleType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Nouvelle controller.
 *
 */
class NouvelleController extends Controller
{
    /**
     * Creates a new Nouvelle entity.
     *
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        $nouvelle = new Nouvelle();
        $form = $this->createForm(NouvelleType::class, $nouvelle);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $nouvelle = $form->getData();
            $nouvelle->setBeneficiaire($beneficiaire);
            $nouvelle->setUtilisateur($this->getUser());

            $em->persist($nouvelle);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'News bien ajoutée');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'beneficiaire' => $beneficiaire,
                'id' => $beneficiaire->getId(),
            )));
        }

        return $this->render('ApplicationPlateformeBundle:Nouvelle:new.html.twig', array(
            'nouvelle' => $nouvelle,
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays all Nouvelle entity.
     *
     */
    public function showAllAction(Beneficiaire $beneficiaire)
    {
        return $this->render('ApplicationPlateformeBundle:Nouvelle:showAll.html.twig', array(
            'beneficiaire' => $beneficiaire,
        ));
    }
    
    public function showAllAjaxAction($idBeneficiaire)
    {
        $em = $this->getDoctrine()->getManager();
        
        $beneficiaire = $em->getRepository("ApplicationPlateformeBundle:Beneficiaire")->find($idBeneficiaire);
        $nouvelles = $beneficiaire->getNouvelle();
        
       
        $arrayNouvelle = array();
        foreach($nouvelles as $nouvelle)
        {
            $arrayNouvelle[] = array("from" => substr($nouvelle->getUtilisateur()->getPrenom(),0,1).'. '.$nouvelle->getUtilisateur()->getNom(),
                                     "date" => $nouvelle->getDate()->format('d/m/Y').' à '.$nouvelle->getDate()->format('H:i'),
                                     "titre" => ucfirst($nouvelle->getTitre()),
                                     "message" => ucfirst($nouvelle->getMessage())
									 );
        }
        return  new JsonResponse(array('nouvelles' => array_reverse($arrayNouvelle) ));
    }

    /**
     * Deletes a Nouvelle entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $nouvelle = $em->getRepository('ApplicationPlateformeBundle:Nouvelle')->find($id);
        $beneficiaire = $nouvelle->getBeneficiaire();

        if (!$nouvelle) {
            throw $this->createNotFoundException('Unable to find Nouvelle.');
        }

        $em->remove($nouvelle);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Nouvelle supprimé avec succès');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }

    /**
     * Edits an existing Nouvelle entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $nouvelle = $em->getRepository('ApplicationPlateformeBundle:Nouvelle')->find($id);
        $beneficiaire = $nouvelle->getBeneficiaire();

        if (!$nouvelle) {
            throw $this->createNotFoundException('Unable to find Nouvelle entity.');
        }

        $editForm = $this->createEditForm($nouvelle);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Nouvelle modifié avec succès');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Nouvelle:edit.html.twig', array(
            'nouvelle'      => $nouvelle,
            'beneficiaire' => $beneficiaire,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Nouvelle entity.
     *
     * @param Nouvelle $nouvelle The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Nouvelle $nouvelle)
    {
        $form = $this->createForm(NouvelleType::class, $nouvelle);

        return $form;
    }
}