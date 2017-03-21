<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Form\CalendarType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * TestCalendar controller.
 *
 */
class CalendarController extends Controller
{
    public function setSession($id){
        $session = new Session();
        $session->start();
        $session->set('id', $id);
    }

    public function getClient(Request $request){
        $googleCalendar = $this->get('fungio.google_calendar');

        //url de redirection
        $redirectUri = "http://localhost/enteo/enteo/web/app_dev.php/calendar/getClient";
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
        $response = new JsonResponse();
        return $response->setData(array('client' => $client));
    }

    public function addEventAction(Request $request, $id)
    {
        //recuperation du bénéficiaire
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        //recuperation du calendarId du consultant du bénéficiaire
        $calendarId = $beneficiaire->getConsultant()->getCalendrierid();

        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');

        $historique = new Historique();
        $form = $this->createForm(CalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $historique = $form->getData();
            $historique->setBeneficiaire($beneficiaire);
            $em = $this->getDoctrine()->getManager();

            $historique->setDateFin($historique->getDateDebut());
            $eventSummary = $historique->getSummary();
            $eventDescription = $historique->getDescription();
            $dateDebut = $historique->getDateDebut()->setTime($historique->getHeureDebut()->format('H'), $historique->getHeureDebut()->format('i'));
            $dateFin = $historique->getDateFin()->setTime($historique->getHeureFin()->format('H'), $historique->getHeureFin()->format('i'));

            //utiliser event pour jouer avec l'evenement
            $event = $googleCalendar->addEvent($calendarId, $dateDebut, $dateFin, $eventSummary, $eventDescription);
            $historique->setConsultant($beneficiaire->getConsultant());

            $historique->setEventId($event['id']);

            $em->persist($historique);
            $em->flush();

            //returne à n'importe lequel url eventuellement au show agenda??
            //à changer peut être?/////////////////////////////////////////////////
            return $this->redirect($this->generateUrl('application_add_evenement', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }

        //cet event servira a l'enregister au niveau du bureau////////////////////////////////
        //$event2 = $googleCalendar->addEvent($calendarId2, (new \DateTime('now'))->modify('+1 day'), (new \DateTime('now'))->modify('+2 day'), $eventSummary, $eventDescription);
        return $this->render('ApplicationPlateformeBundle:Calendar:addEvent.html.twig', array(
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ));
    }

    public function showAllEventAction(Request $request, $id)
    {
        //recuperation du bénéficiaire
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        $googleCalendar = $this->get('fungio.google_calendar');
        $calendarId = $beneficiaire->getConsultant()->getCalendrierid();
        $eventLists = $googleCalendar->initEventsList($calendarId);

        var_dump($eventLists);
        die;
    }


    public function showEventAction(Request $request)
    {
        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');

        $calendarId = "3v46kt8aoonnfing1t6qv7tjg8@group.calendar.google.com";
        $eventId = '2ibhg35v2dpq30oecrphfkasgg';

        $event = $googleCalendar->getEvent($calendarId, $eventId);

        var_dump($event);
        die;
    }

    public function listCalendarsAction(Request $request)
    {
        $redirectUri = "http://localhost/testcalendar/web/app_dev.php/test/add-event";

        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');
        $googleCalendar->setRedirectUri($redirectUri);
        $googleCalendar->setParameters(22);


        if ($request->query->has('code') && $request->get('code')) {
            $client = $googleCalendar->getClient($request->get('code'));
        } else {
            $client = $googleCalendar->getClient();
        }

        if (is_string($client)) {
            return new RedirectResponse($client);
        }

        $calendarId = "3v46kt8aoonnfing1t6qv7tjg8@group.calendar.google.com";
        $eventId = '2ibhg35v2dpq30oecrphfkasgg';

        $listContacts = $googleCalendar->listCalendars();

        var_dump($listContacts);
        die;
    }

    public function editEventAction(Request $request, $id)
    {
        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');

        $em = $this->getDoctrine()->getManager();
        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);

        $calendarId = $historique->getConsultant()->getCalendrierid();
        $eventId = $historique->getEventId();

        $form = $this->createForm(CalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $historique = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $historique->setDateFin($historique->getDateDebut());
            $eventSummary = $historique->getSummary();
            $eventDescription = $historique->getDescription();
            $dateDebut = $historique->getDateDebut()->setTime($historique->getHeureDebut()->format('H'), $historique->getHeureDebut()->format('i'));
            $dateFin = $historique->getDateFin()->setTime($historique->getHeureFin()->format('H'), $historique->getHeureFin()->format('i'));

            //utiliser event pour jouer avec l'evenement
            $eventUpdated = $googleCalendar->updateEvent($calendarId, $eventId, $dateDebut, $dateFin, $eventSummary, $eventDescription);

            $historique->setEventId($eventUpdated['id']);

            $em->persist($historique);
            $em->flush();

            //returne à n'importe lequel url eventuellement au show agenda??
            //à changer peut être?/////////////////////////////////////////////////
            return $this->redirect($this->generateUrl('application_edit_evenement', array(
                    'id' => $historique->getId(),
                )
            ));
        }

        //cet event servira a l'enregister au niveau du bureau////////////////////////////////
        //$event2 = $googleCalendar->addEvent($calendarId2, (new \DateTime('now'))->modify('+1 day'), (new \DateTime('now'))->modify('+2 day'), $eventSummary, $eventDescription);
        return $this->render('ApplicationPlateformeBundle:Calendar:editEvent.html.twig', array(
            'historique' => $historique,
            'form' => $form->createView(),
        ));

    }

    public function showIframeAction(){
        $iframe = '<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=3v46kt8aoonnfing1t6qv7tjg8%40group.calendar.google.com&amp;color=%23B1365F&amp;ctz=Europe%2FParis" style="border-width:0" width="1200" height="750" frameborder="0" scrolling="no"></iframe>';

        return $this->render('ApplicationPlateformeBundle:TestCalendar:showIframe.html.twig', array(
            'iframe' => $iframe
        ));
    }

    public function deleteEventAction($id){
        //supprimer l'historique et l'evenement sur google agenda
        $googleCalendar = $this->get('fungio.google_calendar');

        $em = $this->getDoctrine()->getManager();
        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);
        $beneficiaire = $historique->getBeneficiaire();

        $calendarId = $historique->getConsultant()->getCalendrierid();
        $eventId = $historique->getEventId();

        $googleCalendar->deleteEvent($calendarId,$eventId);

        if (!$historique) {
            throw $this->createNotFoundException('Unable to find Document.');
        }

        $em->remove($historique);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'historique supprimé avec succès');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }
}