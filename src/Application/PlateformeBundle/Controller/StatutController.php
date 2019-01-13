<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Application\PlateformeBundle\Form\PlanningPrevisionnelType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;



class StatutController extends Controller
{

    public function indexAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        $form = $this->createForm(PlanningPrevisionnelType::class, $beneficiaire);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Enregister',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $beneficiaire = $form->getData();

            $em->persist($beneficiaire);
            $em->flush();
            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }



        return $this->render('ApplicationPlateformeBundle:Beneficiaire:form/afficheStatutForm.html.twig', array(
            'formPlanning' => $form->createView(),
            'beneficiaire'      => $beneficiaire,
        ));
    }
}
