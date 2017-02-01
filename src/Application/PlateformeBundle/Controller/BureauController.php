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
            $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->find($form['ville']['nom']->getData());
            $bureau->setVille($ville);
            $em->persist($bureau);
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
            if(preg_match("/^[0-9]{5}$/",$editForm['ville']['nom']->getData())){
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->find($editForm['ville']['nom']->getData());
            }else{
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneByNom($editForm['ville']['nom']->getData());
            }

            $ville->addBureaux($bureau);
            $qb = $em->createQueryBuilder();
            $q = $qb->update('ApplicationPlateformeBundle:Bureau', 'u')
                ->set('u.ville', '?1')
                ->set('u.adresse', '?2')
                ->set('u.nombureau', '?3')
                ->where('u.id = ?4')
                ->setParameter(1, $ville)
                ->setParameter(2, $bureau->getAdresse())
                ->setParameter(3, $bureau->getNombureau())
                ->setParameter(4, $id)
                ->getQuery();
            $p = $q->execute();

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

    /**
     * activer ou desactiver un bureau
     */
    public function actifInactifAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);
        if (!$bureau) {
            throw $this->createNotFoundException('Unable to find Bureau.');
        }
        if($bureau->getActifInactif()== true){
            $bureau->setActifInactif(false);
            $em->persist($bureau);
            $em->flush($bureau);
        }else{
            $bureau->setActifInactif(true);
            $em->persist($bureau);
            $em->flush($bureau);
        }

        return $this->redirect($this->generateUrl('application_index_bureau'));
    }
}
