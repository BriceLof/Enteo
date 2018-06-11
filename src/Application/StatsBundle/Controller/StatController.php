<?php

namespace Application\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\StatsBundle\Form\IntervalDateType;
use Application\StatsBundle\Services\StatsBeneficiaire;

class StatController extends Controller
{
    public function indexAction(Request $request)
    {
        ini_set('memory_limit',-1);
        ini_set('max_execution_time', 300);

        // Appeler le service normalement
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

        $beneficiaireStatutFacturationToday = $statsBeneficiaire->getBeneficiaireStatutFacturation();
        $listeInfoBeneficiaireStatutFacturationToday = array($beneficiaireStatutFacturationToday, array('dateDebut' => '', 'dateFin' => ''));

        $beneficiaireStatutReglementToday = $statsBeneficiaire->getBeneficiaireStatutReglement();
        $listeInfoBeneficiaireStatutReglementToday = array($beneficiaireStatutReglementToday, array('dateDebut' => '', 'dateFin' => ''));

        $beneficiaireTerminerToday = $statsBeneficiaire->getBeneficiaireTerminer();
        $listeInfoBeneficiairesTerminerToday = array($beneficiaireTerminerToday, array('dateDebut' => '', 'dateFin' => ''));

        $beneficiaireAbandonToday = $statsBeneficiaire->getBeneficiaireAbandon();
        $listeInfoBeneficiairesAbandonToday = array($beneficiaireAbandonToday, array('dateDebut' => '', 'dateFin' => ''));


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dateDebut = new \DateTime($form->get('dateFrom')->getData());
            $dateFin = new \DateTime($form->get('dateTo')->getData());

            $em = $this->getDoctrine()->getManager();
            // Appeler le service en passant des arguments aux constructeur. L'instancier comme une classe normale. Ne pas oublier le use en début de fichier
            $statsBeneficiaire = new StatsBeneficiaire($em, $dateDebut, $dateFin);

            $beneficiaires = $statsBeneficiaire->getBeneficiaireOnPeriod();
            $listeInfoNewBeneficiaires = array($beneficiaires, array('dateDebut' => $dateDebut, 'dateFin' => $dateFin));
            

            $beneficiaireNonAbouti = $statsBeneficiaire->getBeneficiaireNonAbouti();
            $listeInfoBeneficiairesNonAbouti = array($beneficiaireNonAbouti, array('dateDebut' => $dateDebut, 'dateFin' => $dateFin));

            $beneficiaireRvCommerciaux = $statsBeneficiaire->getBeneficiaireRvCommerciaux();
            $listeInfoBeneficiaireRvCommerciaux = array($beneficiaireRvCommerciaux, array('dateDebut' => $dateDebut, 'dateFin' => $dateFin));

            $beneficiaireStatutFinancement = $statsBeneficiaire->getBeneficiaireStatutFinancement();
            $listeInfoBeneficiaireStatutFinancement = array($beneficiaireStatutFinancement, array('dateDebut' => $dateDebut, 'dateFin' => $dateFin));

            $beneficiaireStatutFacturation = $statsBeneficiaire->getBeneficiaireStatutFacturation();
            $listeInfoBeneficiaireStatutFacturation = array($beneficiaireStatutFacturation, array('dateDebut' => '', 'dateFin' => ''));

            $beneficiaireStatutReglement = $statsBeneficiaire->getBeneficiaireStatutReglement();
            $listeInfoBeneficiaireStatutReglement = array($beneficiaireStatutReglement, array('dateDebut' => '', 'dateFin' => ''));

            $beneficiaireTerminer = $statsBeneficiaire->getBeneficiaireTerminer();
            $listeInfoBeneficiairesTerminer = array($beneficiaireTerminer, array('dateDebut' => '', 'dateFin' => ''));

            $beneficiaireAbandon = $statsBeneficiaire->getBeneficiaireAbandon();
            $listeInfoBeneficiairesAbandon = array($beneficiaireAbandon, array('dateDebut' => '', 'dateFin' => ''));

            return $this->render("ApplicationStatsBundle:Stat:index.html.twig", array(
                'beneficiaireOfDay' => $listeInfoNewBeneficiaires,
                'beneficiaireNonAboutiOfDay' => $listeInfoBeneficiairesNonAbouti,
                'beneficiaireRvCommerciauxOfDay' => $listeInfoBeneficiaireRvCommerciaux,
                'beneficiaireStatutFinancementOfDay' => $listeInfoBeneficiaireStatutFinancement,
                'beneficiaireStatutFacturationOfDay' => $listeInfoBeneficiaireStatutFacturation,
                'beneficiaireStatutReglementOfDay' => $listeInfoBeneficiaireStatutReglement,
                'beneficiaireTerminerOfDay' => $listeInfoBeneficiairesTerminer,
                'beneficiaireAbandonOfDay' => $listeInfoBeneficiairesAbandon,
                'form' => $form->createView(),
            ));
        }


        return $this->render("ApplicationStatsBundle:Stat:index.html.twig", array(
            'beneficiaireOfDay' => $listeInfoNewBeneficiaires,
            'beneficiaireNonAboutiOfDay' => $listeInfoBeneficiairesNonAboutiToday,
            'beneficiaireRvCommerciauxOfDay' => $listeInfoBeneficiaireRvCommerciauxToday,
            'beneficiaireStatutFinancementOfDay' => $listeInfoBeneficiaireStatutFinancementToday,
            'beneficiaireStatutFacturationOfDay' => $listeInfoBeneficiaireStatutFacturationToday,
            'beneficiaireStatutReglementOfDay' => $listeInfoBeneficiaireStatutReglementToday,
            'beneficiaireTerminerOfDay' => $listeInfoBeneficiairesTerminerToday,
            'beneficiaireAbandonOfDay' => $listeInfoBeneficiairesAbandonToday,
            'form' => $form->createView(),
        ));
    }
}
