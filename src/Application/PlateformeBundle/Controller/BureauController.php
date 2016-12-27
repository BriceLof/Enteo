<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Bureau;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Application\PlateformeBundle\Form\BureauType;

/**
 * Bureau controller.
 *
 */
class BureauController extends Controller
{
    /**
     * Lists all bureau entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bureaux = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll();

        return $this->render('ApplicationPlateformeBundle:Bureau:index.html.twig', array(
            'bureaux' => $bureaux,
        ));
    }

    /**
     * Creates a new bureau entity.
     *
     */
    public function newAction(Request $request)
    {
        $bureau = new Bureau();
        $form = $this->createForm(BureauType::class, $bureau);
        $form ->add('submit',  SubmitType::class, array(
            'label' => 'Enregistrer'
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneByNom($form['ville']['nom']->getData());
            $bureau->setVille($ville);
            $em->flush($bureau);

            return $this->redirect($this->generateUrl('application_index_bureau'));
        }

        return $this->render('ApplicationPlateformeBundle:Bureau:new.html.twig', array(
            'bureau' => $bureau,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a bureau entity.
     *
     */
    public function showAction(Bureau $bureau)
    {
        $deleteForm = $this->createDeleteForm($bureau);

        return $this->render('bureau/show.html.twig', array(
            'bureau' => $bureau,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bureau entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);

        if (!$bureau) {
            throw $this->createNotFoundException('Unable to find Historique entity.');
        }

        $editForm = $this->createForm(BureauType::class, $bureau);
        $editForm->add('submit',  SubmitType::class, array(
            'label' => 'Enregistrer'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('application_index_bureau'));
        }

        return $this->render('ApplicationPlateformeBundle:Bureau:edit.html.twig', array(
            'bureau' => $bureau,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a bureau entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);
        if (!$bureau) {
            throw $this->createNotFoundException('Unable to find Bureau.');
        }
        $em->remove($bureau);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'bureau bien supprimé');

        return $this->redirect($this->generateUrl('application_index_bureau'));
    }
}
