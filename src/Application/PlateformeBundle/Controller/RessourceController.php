<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Ressource;
use Application\PlateformeBundle\Entity\RessourceRubrique;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;



class RessourceController extends Controller
{

    public function indexAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();

        if($id == null)
            $ressources = $em->getRepository('ApplicationPlateformeBundle:Ressource')->findAll();
        else
            $ressources = $em->getRepository('ApplicationPlateformeBundle:Ressource')->findByRubrique($id);

        $ressourceRubriques = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->findBy(array(), array('ordre' => 'ASC'));

        $rubrique = new RessourceRubrique();
        $formOrdreRubrique = $this->createForm('Application\PlateformeBundle\Form\RubriqueRessourceOrdreType', $rubrique);

        return $this->render('ApplicationPlateformeBundle:Ressource:index.html.twig', array(
            'ressources' => $ressources,
            'ressourceRubriques' => $ressourceRubriques,
            'formOrdreRubrique' => $formOrdreRubrique->createView(),
            'idRubrique' => $id
        ));
    }

    public function addAction(Request $request)
    {

        $ressource = new Ressource();
        $form = $this->createForm('Application\PlateformeBundle\Form\RessourceType', $ressource);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $ressource->getFile();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $ressource->setFormat($file->guessExtension());

            $file->move(
                $this->getParameter('ressource_share_directory'),
                $fileName
            );

            $ressource->setFile($fileName);

            $em = $this->getDoctrine()->getManager();

            $rubrique = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->find($ressource->getRubrique());
            if(!is_null($rubrique)){
                $compteurCurrent = $rubrique->getCompteur();
                $rubrique->setCompteur($compteurCurrent + 1);
                $em->persist($rubrique);
            }

            $em->persist($ressource);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Ressource ajouté avec succès');

            return $this->redirect($this->generateUrl('application_index_ressource'));
        }


        return $this->render('ApplicationPlateformeBundle:Ressource:add.html.twig', array(
            'ressource' => $ressource,
            'form' => $form->createView(),
        ));
    }

    public function showAction(Ressource $ressource)
    {
        return $this->render('ressource/show.html.twig', array(
            'ressource' => $ressource,
        ));
    }


    public function editAction(Request $request, Ressource $ressource)
    {

        //$deleteForm = $this->createDeleteForm($ressource);
        $oldRubrique = $ressource->getRubrique();
        $oldFile = $ressource->getFile();
        $editForm = $this->createForm('Application\PlateformeBundle\Form\RessourceType', $ressource);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $ressource->getFile();
            if(!is_null($file)){
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $ressource->setFormat($file->guessExtension());

                $file->move(
                    $this->getParameter('ressource_share_directory'),
                    $fileName
                );

                $ressource->setFile($fileName);
            }else{
                $ressource->setFile($oldFile);

            }

            $em = $this->getDoctrine()->getManager();

            // ajouter un +1 dans la nouvelle rubrique et soustraire 1 pour l'ancienne
            $rubrique = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->find($ressource->getRubrique());
            if(!is_null($rubrique) && ($oldRubrique != $ressource->getRubrique()) ){
                $compteurCurrent = $rubrique->getCompteur();
                $rubrique->setCompteur($compteurCurrent + 1);
                $em->persist($rubrique);

                $rubriqueOld = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->find($oldRubrique);
                if(!is_null($rubriqueOld)){
                    $compteurCurrent = $rubriqueOld->getCompteur();
                    $rubriqueOld->setCompteur($compteurCurrent - 1);
                    $em->persist($rubriqueOld);
                }
            }

            $em->persist($ressource);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Ressource modifié avec succès');

            return $this->redirect($this->generateUrl('application_index_ressource'));
        }

        return $this->render('ApplicationPlateformeBundle:Ressource:edit.html.twig', array(
            'ressource' => $ressource,
            'edit_form' => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));
    }


    public function deleteAction(Request $request, Ressource $ressource)
    {

        if(!is_null($request->get('delete')) && $request->get('delete') == "yes"){

            $em = $this->getDoctrine()->getManager();
            // suppresion du fichier sur le serveur
            unlink($this->getParameter('ressource_share_directory').'/'.$ressource->getFile());

            $rubrique = $em->getRepository('ApplicationPlateformeBundle:RessourceRubrique')->find($ressource->getRubrique());
            if(!is_null($rubrique)){
                $compteurCurrent = $rubrique->getCompteur();
                $rubrique->setCompteur($compteurCurrent - 1);
                $em->persist($rubrique);
            }

            $em->remove($ressource);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Ressource supprimé avec succès');

            return $this->redirect($this->generateUrl('application_index_ressource'));
        }

        return $this->render('ApplicationPlateformeBundle:Ressource:delete.html.twig', array(
            'ressource' => $ressource,
        ));
    }


}
