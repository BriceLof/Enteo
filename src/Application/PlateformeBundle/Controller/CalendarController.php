<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Bureau;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Form\AdminCalendarType;
use Application\PlateformeBundle\Form\CalendarType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;


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

    /**
     * fonction AJAX qui permet de récuperer le client au moment de l'ajout d'un evenement dans la page home
     *
     */
    public function getClientAction(Request $request){
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

        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse();
            return $response->setData(array('client' => $client));
        }else{
            return null;
        }
    }

    /**
     *
     *
     * @param Request $request
     * @param $id (beneficiaire)
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addEventAction(Request $request, $id)
    {
        $this->getClientAction($request);
        //recuperation du bénéficiaire
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        //recuperation du calendarId du consultant du bénéficiaire
        $consultant = $beneficiaire->getConsultant();
        $calendarId = $beneficiaire->getConsultant()->getCalendrierid();

        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');

        $historique = new Historique();
        $historique->setBeneficiaire($beneficiaire);
        $historique->setConsultant($beneficiaire->getConsultant());
        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $historique = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $eventSummary = $historique->getBureau()->getNombureau().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();

            if($form['typerdv']->getData() == 'distantiel'){
                $historique->setBureau(null);
                $eventSummary = $beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            }

            if($historique->getAutreSummary() != null){
                $historique->setSummary($historique->getAutreSummary());
            }

            $eventSummaryBureau = $consultant->getNom().' '.$consultant->getPrenom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            $eventDescription = $historique->getDescription();
            $dateDebut = $historique->getHeureDebut()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));
            $dateFin = $historique->getHeureFin()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));

            if ($form['autreBureau']->getData() == true){
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                    'nom' => $form["ville"]->getData(),
                ));
                $bureau = new Bureau();
                $bureau->setTemporaire(true);
                $bureau->setVille($ville);
                $bureau->setAdresse($form['adresseBureau']->getData());
                $bureau->setNombureau($form['nomBureau']->getData());
                $em->persist($bureau);
                $historique->setBureau($bureau);
                $eventSummary = $historique->getBureau()->getNombureau().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            }else{
                //ajouter l'evenement dans le calendrier du bureau seulement si c'est en presentiel
                if($historique->getBureau() != null) {
                    if ($historique->getBureau()->getCalendrierid() != null){
                        $eventBureau = $googleCalendar->addEvent($historique->getBureau()->getCalendrierid(), $dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                        $historique->setEventIdBureau($eventBureau['id']);
                    }
                }
            }

            //utiliser event pour jouer avec l'evenement
            $event = $googleCalendar->addEvent($consultant->getCalendrierid(), $dateDebut, $dateFin, $eventSummary, $eventDescription);

            $historique->setConsultant($beneficiaire->getConsultant());
            $historique->setEventId($event['id']);
            $historique->setDateFin($dateFin);

            $em->persist($historique);
            $em->flush();

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


    public function adminAddEventAction(Request $request, $id=null){


        $this->getClientAction($request);
        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');

        $historique = new Historique();
        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            //recuperation du bénéficiaire
            $beneficiaire = $historique->getBeneficiaire();

            //recuperation du consultant renseigné dans le formulaire
            //si le consultant n'est pas celui du bénéficiaire ou il n'a pas encore de consultant, on fait comment???
            ////////////A FAIRE/////////////
            $consultant = $historique->getConsultant();
            $em = $this->getDoctrine()->getManager();

            $eventSummary = $historique->getBureau()->getNombureau().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();

            if($form['typerdv']->getData() == 'distantiel'){
                $historique->setBureau(null);
                $eventSummary = $beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            }

            if($historique->getAutreSummary() != null){
                $historique->setSummary($historique->getAutreSummary());
            }

            $eventSummaryBureau = $consultant->getNom().' '.$consultant->getPrenom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            $eventDescription = $historique->getDescription();
            $dateDebut = $historique->getHeureDebut()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));
            $dateFin = $historique->getHeureFin()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));

            if ($form['autreBureau']->getData() == true){
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                    'nom' => $form["ville"]->getData(),
                ));
                $bureau = new Bureau();
                $bureau->setTemporaire(true);
                $bureau->setVille($ville);
                $bureau->setAdresse($form['adresseBureau']->getData());
                $bureau->setNombureau($form['nomBureau']->getData());
                $em->persist($bureau);
                $historique->setBureau($bureau);
                $eventSummary = $historique->getBureau()->getNombureau().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            }else{
                //ajouter l'evenement dans le calendrier du bureau seulement si c'est en presentiel
                if($historique->getBureau() != null) {
                    if ($historique->getBureau()->getCalendrierid() != null){
                        $eventBureau = $googleCalendar->addEvent($historique->getBureau()->getCalendrierid(), $dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                        $historique->setEventIdBureau($eventBureau['id']);
                    }
                }
            }

            //utiliser event pour jouer avec l'evenement
            $event = $googleCalendar->addEvent($consultant->getCalendrierid(), $dateDebut, $dateFin, $eventSummary, $eventDescription);

            $historique->setConsultant($beneficiaire->getConsultant());
            $historique->setEventId($event['id']);
            $historique->setDateFin($dateFin);

            $em->persist($historique);
            $em->flush();

            //returne à n'importe lequel url eventuellement au show agenda??
            //à changer peut être?/////////////////////////////////////////////////
            return $this->redirect($this->generateUrl('application_admin_add_evenement', array(
                )
            ));
        }

        //cet event servira a l'enregister au niveau du bureau////////////////////////////////
        //$event2 = $googleCalendar->addEvent($calendarId2, (new \DateTime('now'))->modify('+1 day'), (new \DateTime('now'))->modify('+2 day'), $eventSummary, $eventDescription);
        return $this->render('ApplicationPlateformeBundle:Calendar:adminAddEvent.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * AJAX pour verifier si le créneau entré est elle occupé ou pas
     */
    public function ajaxBusySlotAction(Request $request){
        $bool = false;
        $this->getClientAction($request);
        $historique = new Historique();
        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));
        $form->handleRequest($request);

        $consultant = $historique->getBeneficiaire()->getConsultant();
        $dateDebut = $historique->getHeureDebut()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));
        $dateFin = $historique->getHeureFin()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));

        if ((new \DateTime('now'))->getTimestamp() < $dateDebut->getTimeStamp()){
            $bool = true;
            $googleCalendar = $this->get('fungio.google_calendar');
            $calendarId = $consultant->getCalendrierid();
            //recupération des évenements
            $events = $googleCalendar->getEventsForDate($calendarId, $historique->getDateDebut());

            foreach ($events->getItems() as $item){
                if((new \DateTime($item->getStart()->getDatetime()))->getTimestamp() > $dateDebut->getTimestamp() && (new \DateTime($item->getStart()->getDatetime()))->getTimestamp() < $dateFin->getTimestamp()){
                    $bool = false;
                    return $bool;
                }
                if((new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() > $dateDebut->getTimestamp() && (new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() < $dateFin->getTimestamp()){
                    $bool = false;
                    return $bool;
                }
                if((new \DateTime($item->getStart()->getDatetime()))->getTimestamp() < $dateDebut->getTimestamp() && (new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() > $dateFin->getTimestamp()){
                    $bool = false;
                    return $bool;
                }
            }
        }
        $resultats = new JsonResponse(json_encode($bool));
        return $resultats;
    }

    public function showAllEventAction(Request $request, $id)
    {
        $this->getClientAction($request);
        //recuperation du consultant
        $em = $this->getDoctrine()->getManager();
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

        $googleCalendar = $this->get('fungio.google_calendar');
        $calendarId = $consultant->getCalendrierid();
        //recuperation de tous ces evenements
        $eventLists = $googleCalendar->initEventsList($calendarId);

        var_dump($eventLists);
        die;
    }


    public function showEventAction(Request $request)
    {
        $this->getClientAction($request);
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
        $this->getClientAction($request);
        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');

        $em = $this->getDoctrine()->getManager();
        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);
        $beneficiaire = $historique->getBeneficiaire();
        $calendarId = $historique->getConsultant()->getCalendrierid();
        $eventId = $historique->getEventId();
        $eventBureauId = $historique->getEventIdBureau();

        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Mofidier'));

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $historique = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $consultant = $historique->getConsultant();

            $eventSummary = $historique->getBureau()->getNombureau().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();

            if($form['typerdv']->getData() == 'distantiel'){
                $historique->setBureau(null);
                $eventSummary = $beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            }

            if($historique->getAutreSummary() != null){
                $historique->setSummary($historique->getAutreSummary());
            }

            $eventSummaryBureau = $consultant->getNom().' '.$consultant->getPrenom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            $eventDescription = $historique->getDescription();
            $dateDebut = $historique->getHeureDebut()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));
            $dateFin = $historique->getHeureFin()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));

            if ($form['autreBureau']->getData() == true){
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                    'nom' => $form["ville"]->getData(),
                ));
                $bureau = new Bureau();
                $bureau->setTemporaire(true);
                $bureau->setVille($ville);
                $bureau->setAdresse($form['adresseBureau']->getData());
                $bureau->setNombureau($form['nomBureau']->getData());
                $em->persist($bureau);
                $historique->setBureau($bureau);
                $eventSummary = $historique->getBureau()->getNombureau().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            }else{
                //ajouter l'evenement dans le calendrier du bureau seulement si c'est en presentiel
                if($historique->getBureau() != null) {
                    if ($historique->getBureau()->getCalendrierid() != null){
                        $eventBureau = $googleCalendar->updateEvent($historique->getBureau()->getCalendrierid(),$eventBureauId,$dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                    }
                }
            }

            //utiliser event pour jouer avec l'evenement
            $eventUpdated = $googleCalendar->updateEvent($calendarId, $eventId, $dateDebut, $dateFin, $eventSummary, $eventDescription);

            $em->persist($historique);
            $em->flush();

            //returne à n'importe lequel url eventuellement au show agenda??
            //à changer peut être?/////////////////////////////////////////////////
            return $this->redirect($this->generateUrl('application_edit_evenement', array(
                    'id' => $historique->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Calendar:addEvent.html.twig', array(
            'historique' => $historique,
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ));
    }

    public function showIframeAction(){
        $iframe = '<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=3v46kt8aoonnfing1t6qv7tjg8%40group.calendar.google.com&amp;color=%23B1365F&amp;ctz=Europe%2FParis" style="border-width:0" width="1200" height="750" frameborder="0" scrolling="no"></iframe>';

        return $this->render('ApplicationPlateformeBundle:TestCalendar:showIframe.html.twig', array(
            'iframe' => $iframe
        ));
    }

    public function deleteEventAction(Request $request,$id){
        $this->getClientAction($request);
        //supprimer l'historique et l'evenement sur google agenda
        $googleCalendar = $this->get('fungio.google_calendar');

        $em = $this->getDoctrine()->getManager();
        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);

        if (!$historique) {
            throw $this->createNotFoundException('Unable to find Document.');
        }

        $beneficiaire = $historique->getBeneficiaire();
        $historique->setEventarchive('on');

        $calendarId = $historique->getConsultant()->getCalendrierid();
        $eventId = $historique->getEventId();
        if ($historique->getBureau() != null){
            if($historique->getBureau()->getCalendrierid() != null){
                $calendarBureauId = $historique->getBureau()->getCalendrierid();
                $eventBureauId = $historique->getEventIdBureau();
                $googleCalendar->deleteEvent($calendarBureauId,$eventBureauId);
            }
        }

        $googleCalendar->deleteEvent($calendarId,$eventId);

        $em->persist($historique);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'historique supprimé avec succès');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }
}