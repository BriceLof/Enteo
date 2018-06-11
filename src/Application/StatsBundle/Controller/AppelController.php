<?php

namespace Application\StatsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\StatsBundle\Form\AppelSemaineType;
use Application\StatsBundle\Entity\Appel;
use Application\StatsBundle\Entity\Semaine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AppelController extends Controller
{
    public function indexAction(Request $request, $numero = 0, $annee = 2018)
    {
        $em = $this->getDoctrine()->getManager();


        if ($numero == 0) {
            $numSemaine = (new \DateTime('now'))->format('W');
            $numAnnee = (new \DateTime('now'))->format('Y');
        } else {
            $numSemaine = $numero;
            $numAnnee = $annee;
        }

        $semaines = $em->getRepository('ApplicationStatsBundle:Semaine')->findBy(array(
            'numero' => $numSemaine,
            'annee' => $numAnnee
        ));

        $firstDay = (new \DateTime())->setISODate($numAnnee, $numSemaine);


        $totalAppelAm = 0;
        $totalAppelPm = 0;
        $totalSansReponseAm = 0;
        $totalSansReponsePm = 0;
        $totalNbContactAm = 0;
        $totalNbContactPm = 0;
        $totalNbRdvAm = 0;
        $totalNbRdvPm = 0;
        $percentSansReponseAm = 0;
        $percentSansReponsePm = 0;
        $percentNbContactAm = 0;
        $percentNbContactPm = 0;
        $percentNbRdvAm = 0;
        $percentNbRdvPm = 0;

        for ($j = 0; $j < sizeof($semaines); $j++){
            for ($i = 0; $i < sizeof($semaines[$j]->getAppels()); $i++) {
                $appel = $semaines[$j]->getAppels()[$i];

                $nbRdvAm[$i][$j] = $appel->getNbRdvAm();
                $nbRdvPm[$i][$j] = $appel->getNbRdvPm();
                $nbReponseAm[$i][$j] = $appel->getNbReponseAm();
                $nbReponsePm[$i][$j] = $appel->getNbReponsePm();
                $nbContactAm[$i][$j] = $appel->getNbContactAm();
                $nbContactPm[$i][$j] = $appel->getNbContactPm();
                $totalAm[$i][$j] = $appel->getNbReponseAm() + $appel->getNbContactAm();
                $totalPm[$i][$j] = $appel->getNbReponsePm() + $appel->getNbContactPm();

                $tab[$i] = array(
                    'date' => $appel->getDate(),
                    'jour' => $appel->getJour(),
                    'nbRdvAm' => array_sum($nbRdvAm[$i]),
                    'nbRdvPm' => array_sum($nbRdvPm[$i]),
                    'nbReponseAm' => array_sum($nbReponseAm[$i]),
                    'nbReponsePm' => array_sum($nbReponsePm[$i]),
                    'nbContactAm' => array_sum($nbContactAm[$i]),
                    'nbContactPm' => array_sum($nbContactPm[$i]),
                    'totalAm' => array_sum($totalAm[$i]),
                    'totalPm' => array_sum($totalPm[$i]),
                );


                $totalAppelAm += $appel->getNbReponseAm() + $appel->getNbContactAm();
                $totalAppelPm += $appel->getNbReponsePm() + $appel->getNbContactPm();
                $totalSansReponseAm += $appel->getNbReponseAm();
                $totalSansReponsePm += $appel->getNbReponsePm();
                $totalNbContactAm += $appel->getNbContactAm();
                $totalNbContactPm += $appel->getNbContactPm();
                $totalNbRdvAm += $appel->getNbRdvAm();
                $totalNbRdvPm += $appel->getNbRdvPm();
            }
        }

        if ($totalAppelAm != 0) {
            $percentSansReponseAm = $totalSansReponseAm * 100 / $totalAppelAm;
            $percentNbContactAm = $totalNbContactAm * 100 / $totalAppelAm;
            $percentNbRdvAm = $totalNbRdvAm * 100 / $totalAppelAm;
        }

        if ($totalAppelPm != 0) {
            $percentSansReponsePm = $totalSansReponsePm * 100 / $totalAppelPm;
            $percentNbContactPm = $totalNbContactPm * 100 / $totalAppelPm;
            $percentNbRdvPm = $totalNbRdvPm * 100 / $totalAppelPm;
        }

        $lastDay = (new \DateTime())->setISODate($numAnnee, $numSemaine)->modify('+6 day');


        return $this->render('ApplicationStatsBundle:Appel:index.html.twig', array(
            'totalAm' => $totalAm,
            'totalPm' => $totalPm,
            'totalSansReponseAm' => $totalSansReponseAm,
            'totalSansReponsePm' => $totalSansReponsePm,
            'totalNbContactAm' => $totalNbContactAm,
            'totalNbContactPm' => $totalNbContactPm,
            'totalNbRdvAm' => $totalNbRdvAm,
            'totalNbRdvPm' => $totalNbRdvPm,
            'totalAppelAm' => $totalAppelAm,
            'totalAppelPm' => $totalAppelPm,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay,
            'percentSansReponseAm' => $percentSansReponseAm,
            'percentSansReponsePm' => $percentSansReponsePm,
            'percentNbContactAm' => $percentNbContactAm,
            'percentNbContactPm' => $percentNbContactPm,
            'percentNbRdvAm' => $percentNbRdvAm,
            'percentNbRdvPm' => $percentNbRdvPm,
            'numero' => $numSemaine,
            'annee' => $numAnnee,
            'semaines' => $semaines,
            'tabs' => $tab

        ));
    }

    public function listAction($numero, $annee, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $semaines = $em->getRepository('ApplicationStatsBundle:Semaine')->findAll();

        $before = $this->generateUrl('appel_edit', array(
            'numero' => (new \DateTime())->setISODate($annee, $numero)->modify('-1 week')->format('W'),
            'annee' => (new \DateTime())->setISODate($annee, $numero)->modify('-1 week')->format('Y'),
        ));
        $after = $this->generateUrl('appel_edit', array(
            'numero' => (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')->format('W'),
            'annee' => (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')->format('Y'),
        ));


        $haveAfter = true;

        if (new \DateTime() < (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')) {
            $haveAfter = false;
        }


        return $this->render('ApplicationStatsBundle:Appel:list.html.twig', array(
            'semaines' => $semaines,
            'numero' => $numero,
            'annee' => $annee,
            'after' => $after,
            'before' => $before,
            'haveAfter' => $haveAfter,
        ));
    }

    public function listUserAction($numero, $annee, $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $commercial = $em->getRepository('ApplicationUsersBundle:Users')->find($id);
        $semaines = $em->getRepository('ApplicationStatsBundle:Semaine')->findBy(array(
            'commercial' => $commercial
        ));

        $before = $this->generateUrl('appel_show', array(
            'numero' => (new \DateTime())->setISODate($annee, $numero)->modify('-1 week')->format('W'),
            'annee' => (new \DateTime())->setISODate($annee, $numero)->modify('-1 week')->format('Y'),
            'id' => $commercial->getId()
        ));
        $after = $this->generateUrl('appel_show', array(
            'numero' => (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')->format('W'),
            'annee' => (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')->format('Y'),
            'id' => $commercial->getId()
        ));

        $haveAfter = true;

        if (new \DateTime() < (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')) {
            $haveAfter = false;
        }


        return $this->render('ApplicationStatsBundle:Appel:listUser.html.twig', array(
            'semaines' => $semaines,
            'numero' => $numero,
            'annee' => $annee,
            'after' => $after,
            'before' => $before,
            'haveAfter' => $haveAfter,
            'idCommercial' => $id
        ));
    }

    public function showAction(Request $request, $id, $numero = 0, $annee = 2018)
    {
        $em = $this->getDoctrine()->getManager();

        $commercial = $em->getRepository('ApplicationUsersBundle:Users')->find($id);


        if ($numero == 0) {
            $numSemaine = (new \DateTime('now'))->format('W');
            $numAnnee = (new \DateTime('now'))->format('Y');
        } else {
            $numSemaine = $numero;
            $numAnnee = $annee;
        }
        $semaine = $em->getRepository('ApplicationStatsBundle:Semaine')->findOneBy(array(
            'numero' => $numSemaine,
            'annee' => $numAnnee,
            'commercial' => $commercial
        ));

        if (is_null($semaine)) {

            $lundi = new Appel();
            $mardi = new Appel();
            $mercredi = new Appel();
            $jeudi = new Appel();
            $vendredi = new Appel();
            $samedi = new Appel();

            $semaine = new Semaine();
            $semaine->addAppel($lundi);
            $semaine->addAppel($mardi);
            $semaine->addAppel($mercredi);
            $semaine->addAppel($jeudi);
            $semaine->addAppel($vendredi);
            $semaine->addAppel($samedi);
            $semaine->setCommercial($commercial);


        }
        $firstDay = (new \DateTime())->setISODate($numAnnee, $numSemaine);


        $totalAppelAm = 0;
        $totalAppelPm = 0;
        $totalSansReponseAm = 0;
        $totalSansReponsePm = 0;
        $totalNbContactAm = 0;
        $totalNbContactPm = 0;
        $totalNbRdvAm = 0;
        $totalNbRdvPm = 0;
        $percentSansReponseAm = 0;
        $percentSansReponsePm = 0;
        $percentNbContactAm = 0;
        $percentNbContactPm = 0;
        $percentNbRdvAm = 0;
        $percentNbRdvPm = 0;

        for ($i = 0; $i < sizeof($semaine->getAppels()); $i++) {
            $appel = $semaine->getAppels()[$i];
            $appel->setJour($i + 1);
            $appel->setDate((new \DateTime())->setISODate($numAnnee, $numSemaine)->modify($i . ' day'));
            $totalAm[$i + 1] = $appel->getNbReponseAm() + $appel->getNbContactAm();
            $totalPm[$i + 1] = $appel->getNbReponsePm() + $appel->getNbContactPm();
            $totalAppelAm += $totalAm[$i + 1];
            $totalAppelPm += $totalPm[$i + 1];
            $totalSansReponseAm += $appel->getNbReponseAm();
            $totalSansReponsePm += $appel->getNbReponsePm();
            $totalNbContactAm += $appel->getNbContactAm();
            $totalNbContactPm += $appel->getNbContactPm();
            $totalNbRdvAm += $appel->getNbRdvAm();
            $totalNbRdvPm += $appel->getNbRdvPm();
        }

        if ($totalAppelAm != 0) {
            $percentSansReponseAm = $totalSansReponseAm * 100 / $totalAppelAm;
            $percentNbContactAm = $totalNbContactAm * 100 / $totalAppelAm;
            $percentNbRdvAm = $totalNbRdvAm * 100 / $totalAppelAm;
        }

        if ($totalAppelPm != 0) {
            $percentSansReponsePm = $totalSansReponsePm * 100 / $totalAppelPm;
            $percentNbContactPm = $totalNbContactPm * 100 / $totalAppelPm;
            $percentNbRdvPm = $totalNbRdvPm * 100 / $totalAppelPm;
        }

        $lastDay = (new \DateTime())->setISODate($numAnnee, $numSemaine)->modify('+6 day');

        $form = $this->createForm(AppelSemaineType::class, $semaine, array(
            'action' => $this->generateUrl('appel_show', array(
                'numero' => $numSemaine,
                'annee' => $numAnnee,
                'id' => $commercial->getId()
            )),
            'method' => 'post'
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $semaine->setAnnee($numAnnee);
            $semaine->setNumero($numSemaine);

            $em->persist($semaine);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'le tableau a été modifié avec succès');

            return $this->redirect($this->generateUrl('appel_show', array(
                    'numero' => $numSemaine,
                    'annee' => $numAnnee,
                    'id' => $commercial->getId()
                )
            ));
        }


        return $this->render('ApplicationStatsBundle:Appel:show.html.twig', array(
            'form' => $form->createView(),
            'totalAm' => $totalAm,
            'totalPm' => $totalPm,
            'totalSansReponseAm' => $totalSansReponseAm,
            'totalSansReponsePm' => $totalSansReponsePm,
            'totalNbContactAm' => $totalNbContactAm,
            'totalNbContactPm' => $totalNbContactPm,
            'totalNbRdvAm' => $totalNbRdvAm,
            'totalNbRdvPm' => $totalNbRdvPm,
            'totalAppelAm' => $totalAppelAm,
            'totalAppelPm' => $totalAppelPm,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay,
            'percentSansReponseAm' => $percentSansReponseAm,
            'percentSansReponsePm' => $percentSansReponsePm,
            'percentNbContactAm' => $percentNbContactAm,
            'percentNbContactPm' => $percentNbContactPm,
            'percentNbRdvAm' => $percentNbRdvAm,
            'percentNbRdvPm' => $percentNbRdvPm,
            'numero' => $numSemaine,
            'annee' => $numAnnee,
            'commercial' => $commercial

        ));
    }
}
