<?php

namespace Application\PlateformeBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Application\PlateformeBundle\Form\AccompagnementType;
use Application\PlateformeBundle\Entity\Accompagnement;



/**
 * Accompagnement controller.
 *
 */
class AccompagnementController extends Controller
{
    /**
     * affiche la partie accompagnement dans fiche beneficiaire
     *
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);


        $accompagnement = $beneficiaire->getAccompagnement();

        $montantTotal = 0;

        if($accompagnement != null){
            foreach ($accompagnement->getFinanceur() as $financeur){
                $montantTotal += $financeur->getMontant();
            }
        }

        $dateDebut = 1;
        $dateFin = 1;
        $heure = null;

        if($accompagnement != null){
            if ($accompagnement->getDateDebut() == null){
                $dateDebut = 0;
            }
            if ($accompagnement->getDateFin() == null ) {
                $dateFin = 0;
            }
            if ($accompagnement->getHeure() == null ){
                $heure = $accompagnement->getHeure();
            }
        }

        if(is_null($accompagnement)){
            $accompagnement = new Accompagnement();
        }
        if (!$beneficiaire) {
            throw $this->createNotFoundException('le bénéfiiaire n\'existe pas.');
        }
        $editForm = $this->createEditForm($accompagnement);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $beneficiaire->setAccompagnement($accompagnement);
            $em->persist($beneficiaire);
            foreach ($accompagnement->getFinanceur() as $financeur){
                $financeur->setAccompagnement($accompagnement);
                $em->persist($financeur);
            }
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Accompagnement modifié avec succès');
            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'beneficiaire' => $beneficiaire,
                'id' => $beneficiaire->getId(),
            )).'#accompagnement');
        }
        return $this->render('ApplicationPlateformeBundle:Accompagnement:edit.html.twig', array(
            'montantTotal' => $montantTotal,
            'beneficiaire' => $beneficiaire,
            'accompagnement' => $accompagnement,
            'edit_form_a' => $editForm->createView(),
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'heure' => $heure,
        ));
    }

    /**
     * Creates a form to edit a beneficiaire entity.
     *
     * @param Accompagnement $accompagnement The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Accompagnement $accompagnement)
    {
        $form = $this->createForm(AccompagnementType::class, $accompagnement);
        $form->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));
        return $form;
    }
}