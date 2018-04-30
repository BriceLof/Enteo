<?php

namespace Application\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\StatsBundle\Form\IntervalDateType;

class StatController extends Controller
{
    public function indexAction(Request $request)
    {
        $statsBeneficiaire = $this->container->get('application_stats_beneficiaire');

        $form = $this->createForm(IntervalDateType::class);

        $newBeneficiaireToday = $statsBeneficiaire->getBeneficiaireOnPeriod();
        $listeInfoNewBeneficiaires = array($newBeneficiaireToday, array('dateDebut' => '', 'dateFin' => ''));

        $beneficiaireNonAboutiToday = $statsBeneficiaire->getBeneficiaireNonAbouti();
        $listeInfoBeneficiairesNonAboutiToday = array($beneficiaireNonAboutiToday, array('dateDebut' => '', 'dateFin' => ''));

        $beneficiaireRvCommerciauxToday = $statsBeneficiaire->getBeneficiaireRvCommerciaux();
        $listeInfoBeneficiaireRvCommerciauxToday = array($beneficiaireRvCommerciauxToday, array('dateDebut' => '', 'dateFin' => ''));

        $beneficiaireStatutFinancementToday = $statsBeneficiaire->getBeneficiaireStatutFinancement();
        $listeInfoBeneficiaireStatutFinancementToday = array($beneficiaireStatutFinancementToday, array('dateDebut' => '', 'dateFin' => ''));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateDebut = new \DateTime($form->get('dateFrom')->getData());
            $dateFin = new \DateTime($form->get('dateTo')->getData());

            $beneficiaires = $statsBeneficiaire->getBeneficiaireOnPeriod($dateDebut, $dateFin);
            $listeInfoNewBeneficiaires = array($beneficiaires, array('dateDebut' => $dateDebut, 'dateFin' => $dateFin));

            $beneficiaireNonAbouti = $statsBeneficiaire->getBeneficiaireNonAbouti($dateDebut, $dateFin);
            $listeInfoBeneficiairesNonAbouti = array($beneficiaireNonAbouti, array('dateDebut' => $dateDebut, 'dateFin' => $dateFin));

            $beneficiaireRvCommerciaux = $statsBeneficiaire->getBeneficiaireRvCommerciaux($dateDebut, $dateFin);
            $listeInfoBeneficiaireRvCommerciaux = array($beneficiaireRvCommerciaux, array('dateDebut' => $dateDebut, 'dateFin' => $dateFin));

            return $this->render("ApplicationStatsBundle:Stat:index.html.twig", array(
                'beneficiaireOfDay' => $listeInfoNewBeneficiaires,
                'beneficiaireNonAboutiOfDay' => $listeInfoBeneficiairesNonAbouti,
                'beneficiaireRvCommerciauxOfDay' => $listeInfoBeneficiaireRvCommerciaux,
                'form' => $form->createView(),
            ));
        }


        return $this->render("ApplicationStatsBundle:Stat:index.html.twig", array(
            'beneficiaireOfDay' => $listeInfoNewBeneficiaires,
            'beneficiaireNonAboutiOfDay' => $listeInfoBeneficiairesNonAboutiToday,
            'beneficiaireRvCommerciauxOfDay' => $listeInfoBeneficiaireRvCommerciauxToday,
            'beneficiaireStatutFinancementOfDay' => $listeInfoBeneficiaireStatutFinancementToday,
            'form' => $form->createView(),
        ));
    }
}
