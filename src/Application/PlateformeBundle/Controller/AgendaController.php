<?php
namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\HistoriqueType;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\AgendaB;

class AgendaController extends Controller
{
    // Autocompletion
    public function autocompletionsAction(Request $request){
        $em = $this->getDoctrine()->getManager(); // Entity manager
        if($request->query->get('sentinel') == 1){
            // nom
            $query = $em->createQuery('SELECT b.id, b.nomConso, b.prenomConso FROM ApplicationPlateformeBundle:Beneficiaire b WHERE b.nomConso LIKE :nom');
            $query->setParameter('nom', $request->query->get('term').'%');
        }
        (count($query->getResult()) <= 0)? $results = array('nomConso' => -1): $results = $query->getResult();;
        return new JsonResponse(json_encode($results));
    }

    // Traitement des liens des calendriers de beneficiaires
    public function agendasAction(Request $request){

        //var_dump($this->getUser()->getId());die;

        if(true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') || $this->getUser()->getId() != $request->query->get('userid')){
            throw $this->createNotFoundException('Vous n\'avez pas accès à cette partie.');
        }

        $em = $this->getDoctrine()->getManager(); // Entity manager
        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll(); // On recupere tous les bureaux
        if(empty($request->query->get('userid'))){
            // Recuperer de tous les consultants (Pour Admin)
            $resultat = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        }
        else{
            // Recuperer le consultant
            $resultat = $em->getRepository('ApplicationUsersBundle:Users')->find($request->query->get('userid'));
        }
        // Instanciation du formulaire d'ajout d'evenement
        $historique = new Historique();
        $form = $this->createForm(HistoriqueType::class, $historique);
        return $this->render('ApplicationPlateformeBundle:Agenda:agendas.html.twig', array(
            'consultant' => $resultat,
            'bureau' => $bureau,
            'form' => $form->createView()
        ));
    }

    // Traitement de l'ajout d'un evenement dans le calendrier du Consultant
    public function evenementAction(Request $request){
        $redirectUri = 'http://'.$_SERVER['SERVER_NAME'].$this->get('router')->generate('application_plateforme_agenda_evenement', array(), true);
        $em = $this->getDoctrine()->getManager(); // Doctrine manager
        // Traitement des données emises par le formulaire

        if ($request->isMethod('POST')){
            switch(true){
                case (!empty($request->query->get('userId'))):
                    $_SESSION['calendrierId'] = $request->query->get('calendrierid');
                    // On stocke l'id du user pour la personnalisation du fichier credentials
                    // dans google calendar qui permet la connexion à l'agenda du consultant
                    $_SESSION['useridcredencial'] = $request->query->get('userId');
                    break;
            }
            $historique = new Historique(); // Historique
            // On recupere le bureau
            if($request->request->get('bureauselect') != -1){
                $bureauObject = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($request->request->get('bureauselect'));
                $historique->setBureau($bureauObject); // bureau
            }
            // On recupere le beneficiaire
            $benef = ($request->request->get('idbeneficiaire') != -1)? $em->getRepository("ApplicationPlateformeBundle:Beneficiaire")->find($request->request->get('idbeneficiaire')) : NULL;
            $historique->setBeneficiaire($benef); // beneficiaire
            $form = $this->createForm(HistoriqueType::class, $historique);
            $url = 'http://'.$_SERVER['SERVER_NAME'].$this->get('router')->generate('application_plateforme_agenda_evenement', array(), true);
            if ($form->handleRequest($request)->isValid()) {
                $historique->setTypeRdv($request->request->get('typeRdv'));
                // On stocke Les infos dans un tableau
                $donnespost[] = array(
                    'nom' => $request->request->get('nomb'),
                    'prenom' => $request->request->get('prenombeneficiaire'),
                    'bureau' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('bureauRdv'):'',
                    'ville' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('villeh'):'',
                    'adresse' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('adresseh'):'',
                    'zip' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('ziph'):'',
                    'rdv' => ($request->request->get('typeRdv') == 'presenciel')? '': $request->request->get('typeRdv')
                );
                $donnespost[] = $historique; // On stocke l'objet dans une session
                $_SESSION['agenda'] = $donnespost; // Données du formulaire
            }
			
			
        }
        // Instanciation du calendrier
        $googleCalendar = $this->get('application_google_calendar');
        $googleCalendar->setRedirectUri($redirectUri);
        if ($request->query->has('code') && $request->get('code')){
            $client = $googleCalendar->getClient($request->get('code'));
        }else {
            $client = $googleCalendar->getClient();
        }
        if (is_string($client)) {
            header('Location: ' . filter_var($client, FILTER_SANITIZE_URL)); // Redirection sur l'url d'autorisation
            exit;
        }
        // Ajout de l'evenement dans la calendrier
        if(isset($_SESSION['agenda']) && isset($_SESSION['calendrierId'])){
            $lieu = $_SESSION['agenda'][0]['adresse'].' '.$_SESSION['agenda'][0]['zip'];
            $typerdv = $_SESSION['agenda'][0]['rdv'];
            $summary = $_SESSION['agenda'][1]->getHeureDebut()->format('H:i').'-'.$_SESSION['agenda'][1]->getHeureFin()->format('H:i').' '.$typerdv.' '.$_SESSION['agenda'][0]['bureau'].', '.$_SESSION['agenda'][0]['nom'].' '.$_SESSION['agenda'][0]['prenom'].' '.$_SESSION['agenda'][1]->getSummary();
            $eventInsert = $googleCalendar->addEvent(
                $_SESSION['calendrierId'],
                $_SESSION['agenda'][1]->getDateDebut(),
                $_SESSION['agenda'][1]->getDateFin(),
                $summary,
                $_SESSION['agenda'][1]->getDescription(),
                $eventAttendee = "",
                $location = $lieu,
                $optionalParams = [],
                $allDay = true
            );
            // On enregistre l'historique en BD
            $em->persist($_SESSION['agenda'][1]);
            $em->flush();
        }
        // On le redirige sur la page agenda
        if($this->getUser()->getRoles()[0] == 'ROLE_ADMIN'){
            // Redirection sur la page agenda de l'Admin
            return $this->redirectToRoute('application_plateforme_agenda');
        }
        else{
            // fichier credential de l'utilisateur est crée alors pas de
            // generation de nouveau code d'autorisation par google, on passe
            // directement dans le if.
            if(!empty($request->query->get('userId'))){
                // Redirection sur la page agenda du consultant
                if(!empty($request->query->get('calendrierid'))){
                    $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll(); // On recupere tous les bureaux
                    $resultat = $em->getRepository('ApplicationUsersBundle:Users')->find($request->query->get('userId'));
                    $historique = new Historique(); // Historique
                    $form = $this->createForm(HistoriqueType::class, $historique);
                    return $this->render('ApplicationPlateformeBundle:Agenda:agendas.html.twig', array(
                        'consultant' => $resultat,
                        'bureau' => $bureau,
                        'form' => $form->createView()
                    ));
                }
                else{
                    // Redirection sur la page agenda de l'Admin
                    return $this->redirectToRoute('application_plateforme_agenda');
                }
            }
            else{
                // 1ere connexion et fichier credential n'existe pas
                $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll(); // On recupere tous les bureaux
                $resultat = $em->getRepository('ApplicationUsersBundle:Users')->find($_SESSION['useridcredencial']);
                $historique = new Historique(); // Historique
                $form = $this->createForm(HistoriqueType::class, $historique);
                return $this->render('ApplicationPlateformeBundle:Agenda:agendas.html.twig', array(
                    'consultant' => $resultat,
                    'bureau' => $bureau,
                    'form' => $form->createView()
                ));
            }
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

