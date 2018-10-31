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
        $ressourceRubriques = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->findBy(array(), array('ordre' => 'ASC'));

        $rubrique = new RessourceRubrique();
        $formOrdreRubrique = $this->createForm('Application\PlateformeBundle\Form\RubriqueRessourceOrdreType', $rubrique);


        return $this->render('ApplicationPlateformeBundle:Ressource:index.html.twig', array(
            'ressources' => $ressources,
            'ressourceRubriques' => $ressourceRubriques,
            'formOrdreRubrique' => $formOrdreRubrique->createView(),
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
            $nombreRubriques = count($em->getRepository("ApplicationPlateformeBundle:RessourceRubrique")->findAll());
            $rubrique->setOrdre($nombreRubriques + 1 );

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

    // Liste des rubriques affichés dans le menu, onglet "ressource partagés"
    public function listForLayoutAction(){
        $em = $this->getDoctrine()->getManager();
        $rubriques = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->findBy(array(), array('ordre' => 'ASC'));

        return $this->render('ApplicationPlateformeBundle:RubriqueRessource:listLayout.html.twig', array(
            'rubriques' => $rubriques,
        ));
    }

    public function editOrdreAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $rubriqueOrderArray = $request->get('rubrique');
        foreach($rubriqueOrderArray as $idRubrique => $ordre){
            $rubrique = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->find($idRubrique);
            $rubrique->setOrdre($ordre);
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Ordre d\'affichage des rubriques modifiées avec succès');

        return $this->redirectToRoute('application_index_ressource');
    }


}
