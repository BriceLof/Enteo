<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Financeur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Application\PlateformeBundle\Form\FinanceurType;

/**
 * Financeur controller.
 *
 */
class FinanceurController extends Controller
{
    /**
     * Lists all Financeur entities.
     *
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $accompagnement = $em->getRepository('ApplicationPlateformeBundle:Accompagnement')->find($id);

        return $this->render('ApplicationPlateformeBundle:Financeur:index.html.twig', array(
            'accompagnement' => $accompagnement,
        ));
    }

    /**
     * Creates a new financeur entity.
     *
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        $financeur = new Financeur();
        $Financeurform = $this->createForm(FinanceurType::class, $financeur);
        $Financeurform->add('submit',  SubmitType::class, array(
            'label' => 'Enregistrer'
        ));
        $Financeurform->handleRequest($request);

        if ($request->isMethod('POST') && $Financeurform->isValid()) {
            $financeur = $Financeurform->getData();
            $financeur->setAccompagnement($beneficiaire->getAccompagnement());
            $em = $this->getDoctrine()->getManager();
            $em->persist($financeur);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Financement bien ajouté');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'beneficiaire' => $beneficiaire,
                    'id' => $beneficiaire->getId(),
                )).'#');                                    //faudrait ajouter une redirection ici
        }

        return $this->render('ApplicationPlateformeBundle:Financeur:new.html.twig', array(
            'financeur' => $financeur,
            'beneficiaire' => $beneficiaire,
            'financeurform' => $Financeurform->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing financeur entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $financeur = $em->getRepository('ApplicationPlateformeBundle:Financeur')->find($id);

        if (!$financeur) {
            throw $this->createNotFoundException('Unable to find Financeur entity.');
        }

        $editForm = $this->createForm(FinanceurType::class, $financeur);
        $editForm->add('submit',  SubmitType::class, array(
            'label' => 'Enregistrer'
        ));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->get('session')->getFlashBag()->add('info', 'Bureau modifié avec succès');

            return $this->redirect($this->generateUrl('application_index_bureau'));
        }

        return $this->render('ApplicationPlateformeBundle:Bureau:edit.html.twig', array(
            'bureau' => $financeur,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Financeur entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $financeur = $em->getRepository('ApplicationPlateformeBundle:Financeur')->find($id);
        if (!$financeur) {
            throw $this->createNotFoundException('Unable to find Financeur.');
        }

        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findOneBy(array(
             'accompagnement' => $financeur->getAccompagnement(),
        ));

        $em->remove($financeur);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Financeur supprimé avec succès');

        return $this->redirect($this->generateUrl('application_show_beneficiaire',array(
            'beneficiaire' => $beneficiaire,
            'id' => $beneficiaire->getId(),
        )));
    }

    /**
     * la liste des opcaOpacif en ajax
     */
    public function listeOpcaOpacifAction(Request $request, $nom){
        if ($request->isXmlHttpRequest()) {
            if($nom == 'OPCA'){
                $results = array('','AGEFOS-PME','AGEFICE','OPCALIA','ACTALIANS','AFDAS','ANFA','ANFH','CONSTRUCTYS','FAFIEC','FAFIH','FAF.TT','FAFSEA','FORCO','FIF PL','INTERGROS','OPCA 3+','OPCA BAIA','OPCA CGM','OPCA DEFI','OPCA PL','OPCA de la contruction','OPCA Transports','OPCAIM ADEFIM','OPCALIM','UNIFAF','UNIFORMATION','AGECIF CAMA','UNAGECIF');
                $resultats = new JsonResponse(json_encode($results));
                return $resultats;
            }else{
                $results = array('','FONGECIF','AFDAS','AGECIF CAMA','FAFSEA','FAF TT','UNAGECIF','UNIFAF','UNIFORMATION','ANFH','OPCALIM');
                $resultats = new JsonResponse(json_encode($results));
                return $resultats;
            }
        }
    }
}
