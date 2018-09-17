<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\RessourceRubrique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;



class RubriqueRessourceController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ressources = $em->getRepository('ApplicationPlateformeBundle:Ressource')->findAll();
        $ressourceRubriques = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->findAll();

        return $this->render('ApplicationPlateformeBundle:Ressource:index.html.twig', array(
            'ressources' => $ressources,
            'ressourceRubriques' => $ressourceRubriques
        ));
    }

    public function addAction(Request $request)
    {

        $rubrique = new RessourceRubrique();
        $form = $this->createForm('Application\PlateformeBundle\Form\RubriqueRessourceType', $rubrique);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $rubrique = $form->getData();
            $rubrique->setCompteur(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rubrique);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Rubrique ajoutée avec succès');

            return $this->redirect($this->generateUrl('application_index_ressource'));
        }


        return $this->render('ApplicationPlateformeBundle:RubriqueRessource:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showAction(Ressource $ressource)
    {
        return $this->render('ressource/show.html.twig', array(
            'ressource' => $ressource,
        ));
    }


    public function editAction(Request $request, RessourceRubrique $rubrique)
    {
        $oldRubrique = $rubrique;

        $editForm = $this->createForm('Application\PlateformeBundle\Form\RubriqueRessourceType', $rubrique);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $rubrique = $editForm->getData();
            $em->persist($rubrique);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Rubrique modifiée avec succès');

            return $this->redirect($this->generateUrl('application_index_ressource'));
        }

        return $this->render('ApplicationPlateformeBundle:RubriqueRessource:edit.html.twig', array(
            'rubrique' => $rubrique,
            'edit_form' => $editForm->createView(),
        ));
    }


    public function deleteAction(Request $request, RessourceRubrique $rubrique)
    {

        if(!is_null($request->get('delete')) && $request->get('delete') == "yes"){
            $em = $this->getDoctrine()->getManager();
            $em->remove($rubrique);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Rubrique supprimé avec succès');

            return $this->redirect($this->generateUrl('application_index_ressource'));
        }

        return $this->render('ApplicationPlateformeBundle:RubriqueRessource:delete.html.twig', array(
            'rubrique' => $rubrique,
        ));
    }


}
