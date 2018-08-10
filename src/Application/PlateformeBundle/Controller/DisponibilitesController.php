<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Bureau;
use Application\PlateformeBundle\Entity\Disponibilites;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Form\AdminCalendarConsultantType;
use Application\PlateformeBundle\Form\AdminCalendarType;
use Application\PlateformeBundle\Form\CalendarType;
use Application\PlateformeBundle\Form\DisponibilitesType;
use Application\UsersBundle\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;


/**
 * Disponibilité controller.
 *
 */
class DisponibilitesController extends Controller
{
    /**
     *
     */
    public function newAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN')
            || true === $authorization->isGranted('ROLE_COMMERCIAL')
            || true === $authorization->isGranted('ROLE_GESTION')
            || $this->getUser()->getId() == $id
            || $this->getUser()->getConsultants()->contains($consultant) ){
        }else{
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }
        $disponibilite = new Disponibilites();

        $formDispo = $this->createForm(DisponibilitesType::class, $disponibilite);
        $formDispo->add('submit',  SubmitType::class, array('label' => 'Enregistrer'));

        $formDispo->handleRequest($request);

        if ($formDispo->isSubmitted() && $formDispo->isValid()) {

            $stack = $this->get('request_stack');
            $masterRequest = $stack->getMasterRequest();
            $currentRoute = $masterRequest->get('_route');

            $googleCalendar = $this->get('fungio.google_calendar');
            $redirectUri = "http://dev.application.entheor.com/web/app_dev.php/calendar/getClient";
            $googleCalendar->setRedirectUri($redirectUri);
            //recuperation du client
            if ($request->query->has('code') && $request->get('code')) {
                $client = $googleCalendar->getClient($request->get('code'));
            } else {
                $client = $googleCalendar->getClient();
            }
            if (is_string($client)) {
                return new RedirectResponse($client);
            }

            if ($formDispo["villeNom"]->getData() != null) {
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                    'nom' => $formDispo["villeNom"]->getData(),
                ));
                $disponibilite->setVille($ville);
                $location = $ville->getCp().' '.$ville->getNom();
            }

            $date = $formDispo['date']->getData();
            $disponibilite->setConsultant($consultant);
            $dateDebut = $disponibilite->getDateDebuts();
            $dateFin = $disponibilite->getDateFins();
            $disponibilite->setDateDebuts($dateDebut->setDate($date->format('Y'),$date->format('m'), $date->format('d')));
            $disponibilite->setDateFins($dateFin->setDate($date->format('Y'),$date->format('m'), $date->format('d')));

            if (isset($ville)){
                $eventSummary = $ville->getNom().' de '.$disponibilite->getDateDebuts()->format('H').'h'.$disponibilite->getDateDebuts()->format('i').'-'.$disponibilite->getDateFins()->format('H').'h'.$disponibilite->getDateFins()->format('i');
            }
            else{
                $eventSummary = 'De '.$disponibilite->getDateDebuts()->format('H').'h'.$disponibilite->getDateDebuts()->format('i').'-'.$disponibilite->getDateFins()->format('H').'h'.$disponibilite->getDateFins()->format('i');
            }

            $eventDescription = '<a href="http://dev.application.entheor.com/web/user/'.$consultant->getId().'/show">(voir le profil du consultant)<a/>';
            //on ajoutera plus tard la disponibilité de la personne dans les bureaux de la ville
            //ce qui veut dire qu'il faudra récuperer le bureau a partir de la ville en bouclant sur la ville, eventuellement le dpt

            $event = $googleCalendar->addEvent($consultant->getCalendrierid(), $disponibilite->getDateDebuts(), $disponibilite->getDateFins(), $eventSummary,$eventDescription,"",isset($location)? $location : '',[], true);

            $disponibilite->setEventId($event['id']);
            $disponibilite->setSummary($eventSummary);

            $em->persist($disponibilite);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Disponibilité ajoutée avec succès');

            return $this->forward('ApplicationPlateformeBundle:Calendar:adminAddEvent', array(
                    'consult' => $disponibilite->getConsultant(),
                )
            );
        }
        return $this->render('ApplicationPlateformeBundle:Disponibilites:new.html.twig', array(
            'form_dispo' => $formDispo->createView(),
            'consultant' => $consultant,
        ));
    }

    public function deleteAction(Request $request,$id){
        //supprimer l'historique et l'evenement sur google agenda
        $googleCalendar = $this->get('fungio.google_calendar');

        //url de redirection
        $redirectUri = "http://dev.application.entheor.com/web/app_dev.php/calendar/getClient";
        $googleCalendar->setRedirectUri($redirectUri);

        //recuperation du client
        if ($request->query->has('code') && $request->get('code')) {
            $client = $googleCalendar->getClient($request->get('code'));
        } else {
            $client = $googleCalendar->getClient();
        }
        if (is_string($client)) {
            return new RedirectResponse($client);
        }

        $em = $this->getDoctrine()->getManager();
        $disponibilite = $em->getRepository('ApplicationPlateformeBundle:Disponibilites')->find($id);

        if (!$disponibilite) {
            throw $this->createNotFoundException('Unable to find disponibilité.');
        }

        $calendarId = $disponibilite->getConsultant()->getCalendrierid();
        $eventId = $disponibilite->getEventId();

        $googleCalendar->deleteEvent($calendarId,$eventId);

        $em->remove($disponibilite);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'disponibilité supprimé avec succès');

        return $this->forward('ApplicationPlateformeBundle:Calendar:adminAddEvent', array(
                'consult' => $disponibilite->getConsultant(),
            )
        );
    }

    public function showAllAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

        if ($request->isXmlHttpRequest()) {

            $template = $this->render('ApplicationPlateformeBundle:Disponibilites:showAll.html.twig', array(
                'consultant' => $consultant,
            ))->getContent();
            $json = json_encode($template);
            $response = new Response($json, 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }else{
            return $this->render('ApplicationPlateformeBundle:Disponibilites:showAll.html.twig', array(
                'consultant' => $consultant,
            ));
        }
    }
}