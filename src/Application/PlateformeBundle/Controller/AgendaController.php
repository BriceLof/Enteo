<?php
    namespace Application\PlateformeBundle\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Application\PlateformeBundle\Entity\Beneficiaire;
    use Application\PlateformeBundle\Form\HistoriqueType;
    use Application\PlateformeBundle\Entity\Historique;
    use Application\PlateformeBundle\Entity\AgendaB;
    

    define('CALENDARID', 'vn0gsc1lned8iosg3qv18k7bg4@group.calendar.google.com'); // VALEUR A RENDRE DYNAMIQUE
    
    class AgendaController extends Controller
    {
        // Traitement des liens des calendriers de beneficiaires
        public function agendasAction(Request $request){
            if(empty($request->query->get('userid'))){
                // Recuperer tous les consultants (Pour Admin)
                $em = $this->getDoctrine()->getManager();
                $resultat = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
            }
            else{
                // Recuperer le consultant  find($productId)
                // Recuperer tous les consultants (Pour Admin)
                $em = $this->getDoctrine()->getManager();
                $resultat = $em->getRepository('ApplicationUsersBundle:Users')->find($request->query->get('userid'));
            }
            // Instanciation du formulaire d'ajout d'evenement
            $historique = new Historique();
            $form = $this->createForm(HistoriqueType::class, $historique);
            
            return $this->render('ApplicationPlateformeBundle:Agenda:agendas.html.twig', array(
               'consultant' => $resultat,
               'form' => $form->createView()
            ));
        }
        
        // Traitement de l'ajout d'un evenement dans le calendrier du beneficiaire
        public function evenementAction(Request $request){
            $redirectUri = 'http://'.$_SERVER['SERVER_NAME'].$this->get('router')->generate('application_plateforme_agenda_evenement', array(), true);
            // Traitement des données emises par le formulaire
            if ($request->isMethod('POST')){
                // ------------------------------------------------------------------------ //
                // ---- Traitement du formulaire d'ajout evenement de la page [Agenda] ---- //
                // ------------------------------------------------------------------------ //
                switch(true){
                    case (empty($request->query->get('userId'))):
                        // On recupere le beneficiaire
                        $em = $this->getDoctrine()->getManager();
                        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($request->query->get('id')); // Beneficiaire
                        // On recupere le calendrier id du consultant
                        switch(true){
                            case (empty($_SESSION['calendrierId'])):
                                $users = $em->getRepository('ApplicationUsersBundle:User')->find($beneficiaire->getConsultant()); // Beneficiaire
                                $_SESSION['calendrierId'] = $users->getCalendrierid();
                                break;
                        }
                        break;
                    case (!empty($request->query->get('userId'))):
                        $_SESSION['calendrierId'] = $request->query->get('calendrierid');
                        break;
                }
                $historique = new Historique(); // Historique
                $form = $this->createForm(HistoriqueType::class, $historique);
                $url = 'http://'.$_SERVER['SERVER_NAME'].$this->get('router')->generate('application_plateforme_agenda_evenement', array(), true);
                if ($form->handleRequest($request)->isValid()) {
                    // On stocke l'objet dans une session
                    $_SESSION['agenda'] = $historique;
               }
            }
            // Instanciation du calendrier
            $googleCalendar = $this->get('application_google_calendar');
            $googleCalendar->setRedirectUri($redirectUri);
            
            if ($request->query->has('code') && $request->get('code')) {
                $client = $googleCalendar->getClient($request->get('code'));
            } else {
                $client = $googleCalendar->getClient();
            }
            if (is_string($client)) {
                header('Location: ' . filter_var($client, FILTER_SANITIZE_URL)); // Redirection sur l'url d'autorisation
                exit;
            }
            // Ajout de l'evenement dans la calendrier
            if(isset($_SESSION['agenda']) && isset($_SESSION['calendrierId'])){
                $summary = $_SESSION['agenda']->getHeureDebut()->format('H:i').' - '.$_SESSION['agenda']->getHeureFin()->format('H:i').' '.$_SESSION['agenda']->getSummary();
                $eventInsert = $googleCalendar->addEvent(
                                    $_SESSION['calendrierId'],
                                    $_SESSION['agenda']->getDateDebut(),
                                    $_SESSION['agenda']->getDateFin(),
                                    $summary,
                                    $_SESSION['agenda']->getDescription(),
                                    $eventAttendee = "",
                                    $location = "",
                                    $optionalParams = [],
                                    $allDay = true
                                );
            }
            // On le redirige sur la page du beneficiaire
            if(!empty($request->query->get('userId'))){
                // Redirection sur la page [Agenda]
                return $this->redirectToRoute('application_plateforme_agenda');
            }
            else{
                // Formulaire qui se trouve dans [Voir Fiche]
                return ((!empty($beneficiaire))?  $this->redirectToRoute('application_show_beneficiaire', array('id' => $beneficiaire->getId())) : $this->redirectToRoute('application_plateforme_homepage', array('page' => 1)));
            }
        }
        
	/**
	 * Expands the home directory alias '~' to the full path.
	 * @param string $path the path to expand.
	 * @return string the expanded path.
	 */
	function expandHomeDirectory($path) {
	  $homeDirectory = getenv('HOME');
	  if (empty($homeDirectory)) {
		$homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
	  }
	  return str_replace('~', realpath($homeDirectory), $path);
	}
    }

