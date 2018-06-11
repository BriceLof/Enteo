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
     * cette fonction permet  d'ajouter un evenement (RDV) dans l'agenda d'un consultant et celui du bureau si celle si en a un
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
        $redirectUri = "https://appli.entheor.com/web/app_dev.php/calendar/getClient";
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

//        si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $historique = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $this->get("application_plateforme.calendar")->createEvent($form, $historique, $beneficiaire, $consultant);

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

    /**
     * cette fonction permet d'ajouter un evenement dans un agenda mais sauf que le formulaire n'est pas dédié a un seul bénéficiaire
     *
     * @param Request $request
     * @param null $id
     * @param null $beneficiaire
     * @param null $consult
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function adminAddEventAction(Request $request, $id=null, $beneficiaire = null, $consult = null){
        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');
        //url de redirection
        $redirectUri = "https://appli.entheor.com/web/app_dev.php/calendar/getClient";
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

            //recuperation du consultant renseigné dans le formulaire
            $consultant = $historique->getConsultant();

            $em = $this->getDoctrine()->getManager();
            $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($form['beneficiaire']->getData());

            $historique->setBeneficiaire($beneficiaire);

            $this->get("application_plateforme.calendar")->createEvent($form, $historique, $beneficiaire, $consultant);

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
            'consult' => $consult,
        ));
    }

    /**
     * AJAX pour verifier si le créneau entré est elle occupé ou pas
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxBusySlotAction(Request $request){
        $bool = false;
        $historique = new Historique();
        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->handleRequest($request);

        $historique = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($form['beneficiaire']->getData());

        $consultant = $beneficiaire->getConsultant();
        $dateDebut = $historique->getHeureDebut()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));
        $dateFin = $historique->getHeureFin()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));

        $bool = true;
        $googleCalendar = $this->get('fungio.google_calendar');
        //url de redirection
        $redirectUri = "https://appli.entheor.com/web/app_dev.php/calendar/getClient";
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
        $redirectUri = "https://appli.entheor.com/web/app_dev.php/calendar/getClient";
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

    /**
     * cette fonction permet d'editer un evenement sur l'agenda d'un consultant.
     * faut faire attention car elle modifie aussi les entrées sur l'agendas des bureaux
     *
     * @param Request $request
     * @param $id (historique)
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editEventAction(Request $request, $id)
    {
        //recuperation du service
        $googleCalendar = $this->get('fungio.google_calendar');
        //url de redirection
        $redirectUri = "https://appli.entheor.com/web/app_dev.php/calendar/getClient";
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

        
        $old_rdv = $historique->getDateDebut();
        
        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION') || $this->getUser()->getBeneficiaire()->contains($beneficiaire ) ) {
        }else{
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

        $calendarId = $historique->getConsultant()->getCalendrierid();
        $eventId = $historique->getEventId();
        $eventBureauId = $historique->getEventIdBureau();

        if ($eventBureauId != null){
            $calendarBureauId = $historique->getBureau()->getCalendrierid();
        }

        $form = $this->createForm(AdminCalendarType::class, $historique);
        $form->add('submit', SubmitType::class, array('label' => 'Modifier'));

        //si le formulaire est validé et qu'il ne présente pas d'erreur
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $historique = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $consultant = $historique->getConsultant();

            if ($eventBureauId != null){
                if($googleCalendar->getEvent($calendarBureauId, $eventBureauId)->getStatus() != "cancelled") {
                    $googleCalendar->deleteEvent($calendarBureauId, $eventBureauId);
                }
                $historique->setEventIdBureau(null);
            }

            $this->get("application_plateforme.calendar")->createEvent($form, $historique, $beneficiaire, $consultant, true, $old_rdv);

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

    /**
     * supprime un evenement sur l'agenda du consultant et celui du bureau si celle ci existe
     *
     * @param Request $request
     * @param $id (historique
     * @return RedirectResponse
     */
    public function deleteEventAction(Request $request,$id){

        //supprimer l'historique et l'evenement sur google agenda
        $googleCalendar = $this->get('fungio.google_calendar');

        //url de redirection
        $redirectUri = "https://appli.entheor.com/web/app_dev.php/calendar/getClient";
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
		$old_rdv = $historique->getDateDebut();

        $type = $historique->getSummary();

        if (!$historique) {
            throw $this->createNotFoundException('Unable to find Document.');
        }

        $beneficiaire = $historique->getBeneficiaire();

        if ($historique->getDateDebut() > new \DateTime() && $historique->getDateDebut() < (new \DateTime())->modify('+1 day')){
            $historique->setCanceled(1);
        }
        else {
            $historique->setEventarchive('on');
        }

        $calendarId = $historique->getConsultant()->getCalendrierid();
        $eventId = $historique->getEventId();


        if ($historique->getBureau() != null) {
            if ($historique->getEventIdBureau() != null) {
                $calendarBureauId = $historique->getBureau()->getCalendrierid();
                $eventBureauId = $historique->getEventIdBureau();
                if($googleCalendar->getEvent($calendarBureauId, $eventBureauId)->getStatus() != "cancelled") {
                    $googleCalendar->deleteEvent($calendarBureauId, $eventBureauId);
                }
            }
        }

        if($googleCalendar->getEvent($calendarId, $eventId)->getStatus() != "cancelled") {
            $googleCalendar->deleteEvent($calendarId, $eventId);
        }


        $em->persist($historique);
        $em->flush();

        $this->get("application_plateforme.statut.mail.mail_rv_agenda")->alerteRdvAgendaSupprime($beneficiaire, $old_rdv, $type);
        $this->get('session')->getFlashBag()->add('info', 'historique supprimé avec succès');


        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }
}