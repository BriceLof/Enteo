<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Historique;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Form\HistoriqueType;


/**
 * Historique controller.
 *
 */
class HistoriqueController extends Controller
{
    /**
     * Creates a new Historique entity.
     *
     */
    public function newAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        $historique = new Historique();

        $form = $this->createForm(HistoriqueType::class, $historique);
        $historique = $form->getData();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()){
                $historique->setBeneficiaire($beneficiaire);
                $em = $this->getDoctrine()->getManager();
                $em->persist($historique);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Historique bien enregistré');

                return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'beneficiaire' => $beneficiaire,
                    'id' => $beneficiaire->getId(),

                )));
            }else{

                foreach ($form->getIterator() as $key => $child) {
                        foreach ($child->getErrors() as $error) {
                            $errors[$key] = $error->getMessage();
                        }
                }

                $this->get('session')->getFlashBag()->add('info', 'erreur sur l\'enregistrement de l\'historique');


                //var_dump($form->getData());die;

                return $this->redirectToRoute('application_show_beneficiaire', array(
                    'erreurHistorique' => $form->getData(),
                    'id' => $beneficiaire->getId(),
                ));
            }
        }

        return $this->render('ApplicationPlateformeBundle:Historique:new.html.twig', array(
            'historique' => $historique,
            'beneficiaire' => $beneficiaire,
            'form' => isset($form) ? $form->createView() : null,
        ));
    }

    /**
     * Finds and displays all Historique entity.
     *
     */
    public function showAllAction(Beneficiaire $beneficiaire)
    {
        // On recupere les historiques du beneficiaire 
        $em = $this->getDoctrine()->getManager();
        $historiquepast = $em->getRepository("ApplicationPlateformeBundle:Historique")->historiquepast(new \DateTime('now'), $beneficiaire);
        
        return $this->render('ApplicationPlateformeBundle:Historique:showAll.html.twig', array(
            'historiques' => $historiquepast,
        ));
    }

    /**
     * Deletes a Historique entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);
        $beneficiaire = $historique->getBeneficiaire();

        if (!$historique) {
            throw $this->createNotFoundException('Unable to find Historique.');
        }

        $em->remove($historique);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Historique bien supprimé');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }

    /**
     * Creates a form to edit a Historique entity.
     *
     * @param Historique $historique The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Historique $historique)
    {
        $form = $this->createForm(HistoriqueType::class, $historique);

        $form->add('submit',SubmitType::class, array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Historique entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);
        $beneficiaire = $historique ->getBeneficiaire();

        if (!$historique) {
            throw $this->createNotFoundException('Unable to find Historique entity.');
        }

        $editForm = $this->createEditForm($historique);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'id' => $beneficiaire->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Historique:edit.html.twig', array(
            'historique'      => $historique,
            'beneficiaire' => $beneficiaire,
            'edit_form'   => $editForm->createView(),
        ));
    }

    // ================================================== //
    // ===== Formulaire ajout evenement dans agenda ===== //
    // ================================================== //
    public function agendaAction(Request $request, $id){
        $historique = new Historique();
        $form = $this->createForm(HistoriqueType::class, $historique);
        // Traitement du formulaire
        return $this->render('ApplicationPlateformeBundle:Historique:agenda.html.twig', array(
            'form' => $form->createView(),
            'beneficiaire' => $id
        ));
    }
}