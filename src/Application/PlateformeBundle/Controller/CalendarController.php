<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Bureau;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Form\AdminCalendarConsultantType;
use Application\PlateformeBundle\Form\AdminCalendarType;
use Application\PlateformeBundle\Form\CalendarType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;


/**
 * TestCalendar controller.
 *
 */
class CalendarController extends Controller
{
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
        //recuperation du bénéficiaire
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION') || $this->getUser()->getBeneficiaire()->contains($beneficiaire ) ) {
        }else{
            return $this->redirect($this->generateUrl('application_admin_add_evenement'));
        }

        //recuperation du calendarId du consultant du bénéficiaire
        $consultant = $beneficiaire->getConsultant();
        $calendarId = $beneficiaire->getConsultant()->getCalendrierid();

        //recuperation du service
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

        $historique = new Historique();
        $historique->setUser($this->getUser());
        $historique->setBeneficiaire($beneficiaire);
        $historique->setConsultant($beneficiaire->getConsultant());
        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $historique = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $location = "";

            if($form['typerdv']->getData() == 'distantiel'){
                $historique->setBureau(null);
                $eventSummary = $beneficiaire->getNomConso().', '.$historique->getSummary();
            }

            if($historique->getAutreSummary() != null){
                $historique->setSummary($historique->getAutreSummary());
            }

            $eventSummaryBureau = $consultant->getNom().' '.$consultant->getPrenom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            $eventDescription = $historique->getDescription().' (<a href="http://dev.application.entheor.com/web/beneficiaire/show/'.$beneficiaire->getId().'">voir fiche bénéficiaire</a>)';
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
                $eventSummary = $historique->getBureau()->getVille()->getNom().', '.$beneficiaire->getNomConso().', '.$historique->getSummary();
            }else{
                //ajouter l'evenement dans le calendrier du bureau seulement si c'est en presentiel
                if($historique->getBureau() != null) {
                    $eventSummary = $historique->getBureau()->getVille()->getNom().', '.$beneficiaire->getNomConso().', '.$historique->getSummary();
                    if ($historique->getBureau()->getCalendrierid() != ""){
                        $eventBureau = $googleCalendar->addEvent($historique->getBureau()->getCalendrierid(), $dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                        $historique->setEventIdBureau($eventBureau['id']);
                    }
                }
            }

            if ($historique->getBureau() != null ){
                $location = $historique->getBureau()->getAdresse().', '.$historique->getBureau()->getVille()->getCp();
            }

            //utiliser event pour jouer avec l'evenement
            $event = $googleCalendar->addEvent($consultant->getCalendrierid(), $dateDebut, $dateFin, $eventSummary, $eventDescription,"",$location);

            $historique->setConsultant($beneficiaire->getConsultant());
            $historique->setEventId($event['id']);
            $historique->setDateFin($dateFin);
            $date = $historique->getDateDebut()->setTime($historique->getHeureDebut()->format('H'),$historique->getHeureDebut()->format('i'));
            $historique->setDateDebut($date);

            $em->persist($historique);
            $em->flush();

            $em->persist($historique);
            $em->flush();

            $this->get("application_plateforme.statut.mail.mail_rv_agenda")->alerteRdvAgenda($beneficiaire, $historique);
            $this->get('session')->getFlashBag()->add('info', 'Rendez-vous ajouté avec succès');

            //returne à n'importe lequel url eventuellement au show agenda??
            //à changer peut être?/////////////////////////////////////////////////
            return $this->redirect($this->generateUrl('application_add_evenement', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Calendar:addEvent.html.twig', array(
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ));
    }


    public function adminAddEventAction(Request $request, $id=null, $beneficiaire = null){
        //recuperation du service
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

        $historique = new Historique();
        $historique->setUser($this->getUser());
        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));

        $formConsultant = $this->createForm(AdminCalendarConsultantType::class, $historique);

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $beneficiaire == null && $form->handleRequest($request)->isValid()) {

            //recuperation du bénéficiaire
            $beneficiaire = $historique->getBeneficiaire();

            $location = "";
            //recuperation du consultant renseigné dans le formulaire
            //si le consultant n'est pas celui du bénéficiaire ou il n'a pas encore de consultant, on fait comment???
            ////////////A FAIRE/////////////
            $consultant = $historique->getConsultant();
            $em = $this->getDoctrine()->getManager();

            if($form['typerdv']->getData() == 'distantiel'){
                $historique->setBureau(null);
                $eventSummary = $beneficiaire->getNomConso().', '.$historique->getSummary();
            }

            if($historique->getAutreSummary() != null){
                $historique->setSummary($historique->getAutreSummary());
            }

            $eventSummaryBureau = $consultant->getNom().' '.$consultant->getPrenom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            $eventDescription = $historique->getDescription().' (<a href="http://dev.application.entheor.com/web/beneficiaire/show/'.$beneficiaire->getId().'">voir fiche bénéficiaire</a>)';
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
                $eventSummary = $historique->getBureau()->getVille()->getNom().', '.$beneficiaire->getNomConso().', '.$historique->getSummary();
            }else{
                //ajouter l'evenement dans le calendrier du bureau seulement si c'est en presentiel
                if($historique->getBureau() != null) {
                    $eventSummary = $historique->getBureau()->getVille()->getNom().', '.$beneficiaire->getNomConso().', '.$historique->getSummary();
                    if ($historique->getBureau()->getCalendrierid() != ""){
                        $eventBureau = $googleCalendar->addEvent($historique->getBureau()->getCalendrierid(), $dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                        $historique->setEventIdBureau($eventBureau['id']);
                    }
                }
            }

            if ($historique->getBureau() != null ){
                $location = $historique->getBureau()->getAdresse().', '.$historique->getBureau()->getVille()->getCp();
            }

            //utiliser event pour jouer avec l'evenement
            $event = $googleCalendar->addEvent($consultant->getCalendrierid(), $dateDebut, $dateFin, $eventSummary, $eventDescription,"",$location);

            $historique->setConsultant($beneficiaire->getConsultant());
            $historique->setEventId($event['id']);
            $historique->setDateFin($dateFin);
            $date = $historique->getDateDebut()->setTime($historique->getHeureDebut()->format('H'),$historique->getHeureDebut()->format('i'));
            $historique->setDateDebut($date);

            $this->get("application_plateforme.statut.mail.mail_rv_agenda")->alerteRdvAgenda($beneficiaire, $historique);
            $this->get('session')->getFlashBag()->add('info', 'Rendez-vous ajouté avec succès');

            $em->persist($historique);
            $em->flush();

            //returne à n'importe lequel url eventuellement au show agenda??
            //à changer peut être?/////////////////////////////////////////////////
            return $this->forward('ApplicationPlateformeBundle:Calendar:adminAddEvent', array(
                'beneficiaire' => $beneficiaire,
                )
            );
        }

        return $this->render('ApplicationPlateformeBundle:Calendar:adminAddEvent.html.twig', array(
            'form' => $form->createView(),
            'form_consultant' => $formConsultant->createView(),
            'beneficiaire' => $beneficiaire,
        ));
    }

    /**
     * AJAX pour verifier si le créneau entré est elle occupé ou pas
     */
    public function ajaxBusySlotAction(Request $request){
        $bool = false;
        $historique = new Historique();
        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->handleRequest($request);

        $historique = $form->getData();

        $consultant = $historique->getBeneficiaire()->getConsultant();
        $dateDebut = $historique->getHeureDebut()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));
        $dateFin = $historique->getHeureFin()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));

        if ((new \DateTime('now'))->getTimestamp() < $dateDebut->getTimeStamp()){
            $bool = true;
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
            $calendarId = $consultant->getCalendrierid();
            //recupération des évenements du consultant
            $events = $googleCalendar->getEventsForDate($calendarId, $historique->getDateDebut());
            foreach ($events->getItems() as $item){
                if ($item->getId() == $historique->getEventId() ){

                }else {
                    if ((new \DateTime($item->getStart()->getDatetime()))->getTimestamp() >= $dateDebut->getTimestamp() && (new \DateTime($item->getStart()->getDatetime()))->getTimestamp() < $dateFin->getTimestamp()) {
                        $bool = false;
                        return new JsonResponse(json_encode($bool));
                    }
                    if ((new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() > $dateDebut->getTimestamp() && (new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() <= $dateFin->getTimestamp()) {
                        $bool = false;
                        return new JsonResponse(json_encode($bool));
                    }
                    if ((new \DateTime($item->getStart()->getDatetime()))->getTimestamp() <= $dateDebut->getTimestamp() && (new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() >= $dateFin->getTimestamp()) {
                        $bool = false;
                        return new JsonResponse(json_encode($bool));
                    }
                }
            }

            //recupération des evenements du bureau si elle existe
            if($historique->getBureau() != null){
                if ($historique->getBureau()->getCalendrierid() != ""){
                    $calendarIdBureau = $historique->getBureau()->getCalendrierid();
                    $eventsBureau = $googleCalendar->getEventsForDate($calendarIdBureau, $historique->getDateDebut());
                    foreach ($eventsBureau->getItems() as $item){
                        if ($item->getId() == $historique->getEventIdBureau() ){
                        }else {
                            if ((new \DateTime($item->getStart()->getDatetime()))->getTimestamp() >= $dateDebut->getTimestamp() && (new \DateTime($item->getStart()->getDatetime()))->getTimestamp() < $dateFin->getTimestamp()) {
                                $bool = false;
                                return new JsonResponse(json_encode($bool));
                            }
                            if ((new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() > $dateDebut->getTimestamp() && (new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() <= $dateFin->getTimestamp()) {
                                $bool = false;
                                return new JsonResponse(json_encode($bool));
                            }
                            if ((new \DateTime($item->getStart()->getDatetime()))->getTimestamp() <= $dateDebut->getTimestamp() && (new \DateTime($item->getEnd()->getDatetime()))->getTimestamp() >= $dateFin->getTimestamp()) {
                                $bool = false;
                                return new JsonResponse(json_encode($bool));
                            }
                        }
                    }
                }
            }
        }
        $resultats = new JsonResponse(json_encode($bool));
        return $resultats;
    }

    public function showAllEventAction(Request $request, $id)
    {
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
        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');
        $googleCalendar->setParameters(22);
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
        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);
        $beneficiaire = $historique->getBeneficiaire();
        $historique->setUser($this->getUser());

        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION') || $this->getUser()->getBeneficiaire()->contains($beneficiaire ) ) {
        }else{
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

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

            $location = "";

            if($form['typerdv']->getData() == 'distantiel'){
                $historique->setBureau(null);
                $eventSummary = $beneficiaire->getNomConso().', '.$historique->getSummary();
            }

            if($historique->getSummary() == "Autre"){
                $historique->setSummary($historique->getAutreSummary());
            }

            $eventSummaryBureau = $consultant->getNom().' '.$consultant->getPrenom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            $eventDescription = $historique->getDescription().' (<a href="http://dev.application.entheor.com/web/beneficiaire/show/'.$beneficiaire->getId().'">voir fiche bénéficiaire</a>)';
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
                $eventSummary = $historique->getBureau()->getVille()->getNom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
            }else{
                //ajouter l'evenement dans le calendrier du bureau seulement si c'est en presentiel
                if($historique->getBureau() != null) {
                    $eventSummary = $historique->getBureau()->getVille()->getNom().', '.$beneficiaire->getNomConso().', '.$historique->getSummary();
                    if ($historique->getBureau()->getCalendrierid() != ""){
                        $eventBureauUpdated = $googleCalendar->updateEvent($historique->getBureau()->getCalendrierid(),$eventBureauId,$dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                    }
                }
            }

            if ($historique->getBureau() != null ){
                $location = $historique->getBureau()->getAdresse().', '.$historique->getBureau()->getVille()->getCp();
            }

            //utiliser event pour jouer avec l'evenement
            $eventUpdated = $googleCalendar->updateEvent($calendarId, $eventId, $dateDebut, $dateFin, $eventSummary, $eventDescription,"",$location);

            $date = $historique->getDateDebut()->setTime($historique->getHeureDebut()->format('H'),$historique->getHeureDebut()->format('i'));
            $historique->setDateDebut($date);

            $em->persist($historique);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Rendez-vous modifié avec succès');

            //returne à n'importe lequel url eventuellement au show agenda??
            //à changer peut être?/////////////////////////////////////////////////
            return $this->redirect($this->generateUrl('application_edit_evenement', array(
                    'id' => $historique->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Calendar:editEvent.html.twig', array(
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
        $historique = $em->getRepository('ApplicationPlateformeBundle:Historique')->find($id);

        if (!$historique) {
            throw $this->createNotFoundException('Unable to find Document.');
        }

        $beneficiaire = $historique->getBeneficiaire();
        $historique->setEventarchive('on');

        $calendarId = $historique->getConsultant()->getCalendrierid();
        $eventId = $historique->getEventId();

        if ($historique->getBureau() != null){
            if($historique->getBureau()->getCalendrierid() != ""){
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