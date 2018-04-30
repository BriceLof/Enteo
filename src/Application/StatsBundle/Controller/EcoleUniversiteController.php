<?php

namespace Application\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Application\StatsBundle\Form\EcoleUniversiteType;

class EcoleUniversiteController extends Controller
{
    public function indexAction(Request $request){
        $form = $this->createForm(EcoleUniversiteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $debut = $form['dateFrom']->getData();
            $fin = $form['dateTo']->getData();

            $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->searchByEcoleAndDate($debut, $fin);
            $beneficiaires = $query->getResult();

            return $this->get('application_plateforme.csv')->getCsvFromEcoleBeneficiaire($beneficiaires);
        }

        return $this->render("ApplicationStatsBundle:EcoleUniversite:index.html.twig", array(
            'form' => $form->createView(),
        ));
    }
}
