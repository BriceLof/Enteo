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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bureau);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Bureau ajouté avec succès');

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
    public function editAction(Request $request, Bureau $bureau)
    {
        if (!$bureau) {
            throw $this->createNotFoundException('Bureau introuvable.');
        }

        $editForm = $this->createForm(BureauType::class, $bureau);
        $editForm->get('codePostalHidden')->setData($bureau->getVille()->getCp());
        $editForm->get('idVilleHidden')->setData($bureau->getVille()->getId());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bureau);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Bureau modifié avec succès');

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
        $em = $this->getDoctrine()->getManager();
        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);
        if (!$bureau) {
            throw $this->createNotFoundException('Unable to find Bureau.');
        }
        $bureau->setSupprimer(true);
		$bureau->setActifInactif(false);
		$em->persist($bureau);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Bureau supprimé avec succès');

        return $this->redirect($this->generateUrl('application_index_bureau'));
    }

    /**
     * activer ou desactiver un bureau
     */
    public function actifInactifAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);
        if (!$bureau) {
            throw $this->createNotFoundException('Unable to find Bureau.');
        }
        if($bureau->getActifInactif()== true){
            $bureau->setActifInactif(false);
            $em->persist($bureau);
            $em->flush($bureau);

            $this->get('session')->getFlashBag()->add('info', 'Bureau desactivé');
        }else{
            $bureau->setActifInactif(true);
            $em->persist($bureau);
            $em->flush($bureau);

            $this->get('session')->getFlashBag()->add('info', 'Bureau activé');
        }

        return $this->redirect($this->generateUrl('application_index_bureau'));
    }
}
