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


        if ($numero == 0){
            $numSemaine =(new \DateTime('now'))->format('W');
            $numAnnee =  (new \DateTime('now'))->format('Y');
        }else{
            $numSemaine = $numero;
            $numAnnee = $annee;
        }
        $semaine = $em->getRepository('ApplicationStatsBundle:Semaine')->findOneBy(array(
            'numero' => $numSemaine,
            'annee' => $numAnnee
        ));

        if (!is_null($semaine)){
            $edit = true;
        }else{

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

            $edit = false;
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

        for ($i = 0; $i < sizeof($semaine->getAppels()); $i++ ){
            $appel = $semaine->getAppels()[$i];
            $appel->setJour($i+1);
            $appel->setDate((new \DateTime())->setISODate($numAnnee, $numSemaine)->modify($i. ' day'));
            $totalAm[$i+1] = $appel->getNbReponseAm() + $appel->getNbContactAm();
            $totalPm[$i+1] = $appel->getNbReponsePm() + $appel->getNbContactPm();
            $totalAppelAm += $totalAm[$i+1];
            $totalAppelPm += $totalPm[$i+1];
            $totalSansReponseAm += $appel->getNbReponseAm();
            $totalSansReponsePm += $appel->getNbReponsePm();
            $totalNbContactAm += $appel->getNbContactAm();
            $totalNbContactPm += $appel->getNbContactPm();
            $totalNbRdvAm += $appel->getNbRdvAm();
            $totalNbRdvPm += $appel->getNbRdvPm();
        }

        if ($totalAppelAm != 0){
            $percentSansReponseAm = $totalSansReponseAm * 100 / $totalAppelAm;
            $percentSansReponsePm = $totalSansReponsePm * 100 / $totalAppelPm;
            $percentNbContactAm = $totalNbContactAm * 100 / $totalAppelAm;
            $percentNbContactPm = $totalNbContactPm * 100 / $totalAppelPm;
            $percentNbRdvAm = $totalNbRdvAm * 100 / $totalAppelAm;
            $percentNbRdvPm = $totalNbRdvPm * 100 / $totalAppelPm;
        }

        $lastDay = (new \DateTime())->setISODate($numAnnee, $numSemaine)->modify('+6 day');

        if ($edit == true) {
            $form = $this->createForm(AppelSemaineType::class, $semaine, array(
                'action' => $this->generateUrl('appel_edit', array(
                    'numero' => $numSemaine,
                    'annee' => $numAnnee
                )),
                'method' => 'post'
            ));
        }else {
            $form = $this->createForm(AppelSemaineType::class, $semaine, array(
                'action' => $this->generateUrl('appel_homepage'),
                'method' => 'post'
            ));
        }
        $form->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $semaine->setAnnee($numAnnee);
            $semaine->setNumero($numSemaine);

            $em->persist($semaine);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'le tableau a été modifié avec succès');

            return $this->redirect($this->generateUrl('appel_homepage', array(

                )
            ));
        }


        return $this->render('ApplicationStatsBundle:Appel:index.html.twig', array(
            'form'   => $form->createView(),
            'totalAm' => $totalAm,
            'totalPm' => $totalPm,
            'totalSansReponseAm' => $totalSansReponseAm,
            'totalSansReponsePm'=> $totalSansReponsePm,
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
            'annee' => $numAnnee

        ));
    }

    public function listAction($numero, $annee){
        $em = $this->getDoctrine()->getManager();
        $haveAfter = true;
        $semaines = $em->getRepository('ApplicationStatsBundle:Semaine')->findAll();
        $before = $this->generateUrl('appel_edit', array(
            'numero' => (new \DateTime())->setISODate($annee, $numero)->modify('-1 week')->format('W'),
            'annee' => (new \DateTime())->setISODate($annee, $numero)->modify('-1 week')->format('Y'),
        ));
        $after = $this->generateUrl('appel_edit', array(
            'numero' => (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')->format('W'),
            'annee' => (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')->format('Y'),
        ));

        if (new \DateTime() < (new \DateTime())->setISODate($annee, $numero)->modify('+1 week')){
            $haveAfter = false;
        }



        return $this->render('ApplicationStatsBundle:Appel:list.html.twig', array(
            'semaines' => $semaines,
            'numero' => $numero,
            'annee' => $annee,
            'after' => $after,
            'before' => $before,
            'haveAfter' => $haveAfter
        ));
    }
}
