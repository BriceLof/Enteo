<?php

namespace Application\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Application\StatsBundle\Form\EcoleUniversiteType;
use Symfony\Component\HttpFoundation\Response;

class EcoleUniversiteController extends Controller
{
    public function indexAction(Request $request)
    {
        $form = $this->createForm(EcoleUniversiteType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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

    public function showAction(Request $request)
    {

        $debut = $request->get('ecole_universite')['dateFrom'];
        $fin = $request->get('ecole_universite')['dateTo'];

        $debut = \DateTime::createFromFormat("d/m/Y",$debut);

        $fin = \DateTime::createFromFormat("d/m/Y",$fin);

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->searchByEcoleAndDate($debut, $fin);
        $beneficiaires = $query->getResult();

        $template = $this->render('ApplicationStatsBundle:EcoleUniversite:show.html.twig', array(
            'beneficiaires' => $beneficiaires,
        ))->getContent();

        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    public function exportAction(Request $request){

        $debut = $request->get('ecole_universite')['dateFrom'];
        $fin = $request->get('ecole_universite')['dateTo'];

        $debut = \DateTime::createFromFormat("d/m/Y",$debut);

        $fin = \DateTime::createFromFormat("d/m/Y",$fin);
        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->searchByEcoleAndDate($debut, $fin);
        $beneficiaires = $query->getResult();

        return $this->get('application_plateforme.csv')->getCsvFromEcoleBeneficiaire($beneficiaires);
    }
}
