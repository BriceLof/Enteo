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
            (count($query->getResult()) <= 0)? $results = array('nomConso' => -1): $results = $query->getResult();
        }
        else if($request->query->get('sentinel') == 2){
            $query = $em->createQuery('SELECT b.id, b.adresse, v.cp, v.departementId, v.nom, b.nombureau FROM ApplicationPlateformeBundle:Bureau b JOIN b.ville v WHERE v.departementId LIKE :nom');
            $query->setParameter('nom', $request->query->get('term').'%');
            (count($query->getResult()) <= 0)? $results = array('nom' => -1): $results = $query->getResult();
        }
        return new JsonResponse(json_encode($results));
    }

    // Traitement des liens des calendriers de beneficiaires
    public function agendasAction(Request $request){
		switch(true){
			case($_SERVER['SERVER_NAME'] == 'dev.application.entheor.com'):
				// remote
				if($this->getUser()->getId() != $request->query->get('userid') && $_SERVER['REQUEST_URI'] != '/web/app_dev.php/agenda')
					return $this->redirect( $this->generateUrl('application_plateforme_agenda', array('userid' => $this->getUser()->getId()))); 
			break;
			default:
				// localhost
				if($this->getUser()->getId() != $request->query->get('userid') && $_SERVER['REQUEST_URI'] != '/teo/web/app_dev.php/agenda')
					return $this->redirect( $this->generateUrl('application_plateforme_agenda', array('userid' => $this->getUser()->getId()))); 
			break;
		}
        $em = $this->getDoctrine()->getManager(); // Entity manager
        if(empty($request->query->get('userid'))){
            // Recuperer tous les consultants (Pour Admin)
            $resultat = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser('ROLE_CONSULTANT');
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
            'form' => $form->createView()
        ));
    }
    // Traitement de l'ajout d'un evenement dans le calendrier du Consultant
    // ################################################################ //
    // ####### NB: Vous remarquerez que j'ai incrementé et      ####### //
    // ####### décrementé l'heure de debut et de fin dans       ####### //
    // ####### la fonction ci-dessous, c'est pour l'integrité   ####### //
    // ####### des heures en BD et dans le calendrier Google.   ####### //
    // ####### car les heures dans le calendrier sont en GMT+1  ####### //
    // ####### ce qui veut dire que si on choisi 08h dans le    ####### //
    // ####### formulaire d'ajout evenement; dans le calendrier ####### //
    // ####### on vera 09h au lieu de 08h (GMT+1)               ####### //
    // ################################################################ //
    public function evenementAction(Request $request){
        // =========================================== //
        // ===== Si la demande provient de la page === //
        // ===== Beneficiaire alors on le redrige ==== //  
        // =========================================== //
        if(!empty($_SESSION['firstpast']) && $_SESSION['firstpast'][2] == 'page beneficiaire'){
            $_SESSION['code'] = $request->get('code'); // On recupere le code d'autorisation google
            // redirection sur la page show
            return $this->redirectToRoute('application_show_beneficiaire', array('id'=>$_SESSION['firstpast'][0]));
        }
        $redirectUri = 'http://'.$_SERVER['SERVER_NAME'].$this->get('router')->generate('application_plateforme_agenda_evenement', array(), true);
        // Traitement des données emises par le formulaire
        if ($request->isMethod('POST')){
            $historique = new Historique(); // Historique
            $form = $this->createForm(HistoriqueType::class, $historique);
            $url = 'http://'.$_SERVER['SERVER_NAME'].$this->get('router')->generate('application_plateforme_agenda_evenement', array(), true);
            if ($form->handleRequest($request)->isValid()){
		// On recupere l'id bureau
		if($request->request->get('bureauselect') != -1){
                    $_SESSION['bureau'] = $request->request->get('bureauselect');
		}
		// On recupère l'id du beneficiaire
		$_SESSION['benef'] = $request->request->get('idbeneficiaire');
                $historique->setTypeRdv($request->request->get('typeRdv'));
                // On stocke Les infos dans un tableau
                $donnespost[] = array(
                    'nom' => $request->request->get('nomb'),
                    'prenom' => $request->request->get('prenombeneficiaire'),
                    'bureau' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('bureauselect'):'',
                    'ville' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('villeh'):'',
                    'adresse' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('adresseh'):'',
                    'zip' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('ziph'):'',
                    'rdv' => ($request->request->get('typeRdv') == 'presenciel')? '' : $request->request->get('typeRdv')
                );
                
                $donnespost[] = $historique; // On stocke l'objet dans une session
                $_SESSION['agenda'] = $donnespost; // Données du formulaire
            }
            switch(true){
                case (!empty($request->query->get('userId'))):
                    $_SESSION['calendrierId'] = $request->query->get('calendrierid');
                    // On stocke l'id du user pour la personnalisation du fichier credentials
                    // dans google calendar qui permet la connexion à l'agenda du consultant
                    $_SESSION['useridcredencial'] = $request->query->get('userId');
                    break;
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
        // Ajout de l'evenement dans le calendrier
        if(isset($_SESSION['agenda']) && isset($_SESSION['calendrierId'])){
            $lieu = $_SESSION['agenda'][0]['adresse'].' '.$_SESSION['agenda'][0]['zip'];
            $typerdv = $_SESSION['agenda'][0]['rdv'];
            $summary = $typerdv.' '.$_SESSION['agenda'][0]['bureau'].', '.$_SESSION['agenda'][0]['nom'].' '.$_SESSION['agenda'][0]['prenom'].' '.$_SESSION['agenda'][1]->getSummary();
            // Changer le format en GMT+1 pour prendre en compte les heures dans l'agenda
            $h_d = $_SESSION['agenda'][1]->getHeureDebut()->format('H:i:s');
            $h_f = $_SESSION['agenda'][1]->getHeureFin()->format('H:i:s');
            $hd = explode(":", $h_d); // heure debut
            $hf = explode(":", $h_f); // heure fin
            // Verifier que les heures choisies ne sont pas occupée
            $em = $this->getDoctrine()->getManager(); // Doctrine manager
            // On recupere le beneficiaire
            $benef = ($request->request->get('idbeneficiaire') != -1)? $em->getRepository("ApplicationPlateformeBundle:Beneficiaire")->find($_SESSION['benef']) : NULL;
            $_SESSION['agenda'][1]->setBeneficiaire($benef); // beneficiaire
            // On recupere les rendez-vous du beneficiaire 
            $resultats = $em->getRepository('ApplicationPlateformeBundle:Historique')->dateocuppee($_SESSION['agenda'][1]->getDateDebut()->setTime($hd[0]+1, $hd[1], $hd[2]), $_SESSION['agenda'][1]->getDateFin()->setTime($hf[0]+1, $hf[1], $hf[2]), $_SESSION['agenda'][1]->getHeureDebut()->format('H:i:s'), $_SESSION['agenda'][1]->getHeureFin()->format('H:i:s'), $benef);
            
            if(count($resultats) > 0){
                // Erreur sur l'heure reservée
                $this->get('session')->set('erreurs', true);
            }
            else{
                $eventInsert = $googleCalendar->addEvent(
                    $_SESSION['calendrierId'],
                    $_SESSION['agenda'][1]->getDateDebut()->setTime($hd[0]-1, $hd[1], $hd[2]), // decrementation heure debut 
                    $_SESSION['agenda'][1]->getDateFin()->setTime($hf[0]-1, $hf[1], $hf[2]), // decrementation heure fin
                    $summary,
                    $_SESSION['agenda'][1]->getDescription(),
                    $eventAttendee = "",
                    $location = $lieu,
                    $optionalParams = [],
                    $allDay = false
                );
                // On recupere l'id de l'evenement ajouté
                $_SESSION['agenda'][1]->setEventId($eventInsert["id"]);
                // On recupère le Bureau
                if(!empty($_SESSION['bureau'])){
                    $bureauObject = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($_SESSION['bureau']);
                    $_SESSION['agenda'][1]->setBureau($bureauObject); // bureau
                }
                
                // On recupere le user pour le stcker en BD
                $userbd = $em->getRepository("ApplicationUsersBundle:Users")->find($_SESSION['useridcredencial']);
                $_SESSION['agenda'][1]->setConsultant($userbd); // le consultant
                // On enregistre l'historique en BD
                $_SESSION['agenda'][1]->getDateDebut()->setTime($hd[0], $hd[1], $hd[2]); // incrementation heure debut 
                $_SESSION['agenda'][1]->getDateFin()->setTime($hf[0], $hf[1], $hf[2]); // incrementation heure debut 
                $em->persist($_SESSION['agenda'][1]); // Mise en attente de sauvegarde de l'historique en BD
                $em->flush();
                unset($_SESSION['agenda']);
                $this->get('session')->set('erreurs', false);
            }
            // On supprime les sessions pour soulager le gc
            $this->get('session')->remove('benef');
            $this->get('session')->remove('bureau');
            $this->get('session')->remove('agenda');
            $this->get('session')->remove('calendrierId');
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
                    $resultat = $em->getRepository('ApplicationUsersBundle:Users')->find($request->query->get('userId'));
                    $historique = new Historique(); // Historique
                    $form = $this->createForm(HistoriqueType::class, $historique);
                    return $this->render('ApplicationPlateformeBundle:Agenda:agendas.html.twig', array(
                        'consultant' => $resultat,
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
                $resultat = $em->getRepository('ApplicationUsersBundle:Users')->find($_SESSION['useridcredencial']);
                $this->get('session')->remove('useridcredencial');
                $historique = new Historique(); // Historique
                $form = $this->createForm(HistoriqueType::class, $historique);
                return $this->render('ApplicationPlateformeBundle:Agenda:agendas.html.twig', array(
                    'consultant' => $resultat,
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

