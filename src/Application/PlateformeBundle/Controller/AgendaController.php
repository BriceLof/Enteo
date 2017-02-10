<?php
namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Bureau;
use Application\PlateformeBundle\Form\HistoriqueType;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\AgendaB;

class AgendaController extends Controller
{
    // Autocompletion
    public function autocompletionsAction(Request $request){
        $em = $this->getDoctrine()->getManager(); // Entity manager
        if($request->query->get('sentinel') == 1){
            // Beneficiaire
            $nomc = '%'.$request->query->get('term').'%';
            $idbenef = $request->query->get('id');
            $query = $em->createQuery('SELECT b.id, b.nomConso, b.prenomConso FROM ApplicationPlateformeBundle:Beneficiaire b WHERE b.consultant = :id AND b.nomConso LIKE :nom');
            $query->setParameters(array('id'=>$idbenef, 'nom'=>$nomc));
            (count($query->getResult()) <= 0)? $results = array('nomConso' => -1): $results = $query->getResult();
        }
        else if($request->query->get('sentinel') == 2){
            // Bureau existant
            $cp = $request->query->get('term').'%';
            $query = $em->createQuery('SELECT b.id, b.adresse, v.cp, v.departementId, v.nom, b.nombureau FROM ApplicationPlateformeBundle:Bureau b JOIN b.ville v WHERE v.cp LIKE :nom');
            $query->setParameter('nom', $cp);
            (count($query->getResult()) <= 0)? $results = array('nom' => -1): $results = $query->getResult();
        }
        else if($request->query->get('sentinel') == 3){
            // Autre bureau
            $cp = $request->query->get('term').'%';
            $query = $em->createQuery('SELECT v.nom, v.id, v.cp FROM ApplicationPlateformeBundle:Ville v WHERE v.cp LIKE :nom');
            $query->setParameter('nom', $cp);
            (count($query->getResult()) <= 0)? $results = array('nom' => -1): $results = $query->getResult();
        }
        return new JsonResponse(json_encode($results));
    }

    // Traitement des liens des calendriers de beneficiaires
    public function agendasAction(Request $request){    
        $tabColor = ["#00FFFF", "#0000FF", "#FF00FF", "#808080", "#00FF00", "#800000", "#000080", "#808000", "#800080", "#FF0000", "#C0C0C0", "#008080", "#FFFF00"];
        $authorization = $this->get('security.authorization_checker');
		switch(true){
                case($_SERVER['SERVER_NAME'] == 'dev.application.entheor.com'):
                        // remote
						 if(!$authorization->isGranted('ROLE_ADMIN') && !$authorization->isGranted('ROLE_COMMERCIAL'))
                        {
							if($this->getUser()->getId() != $request->query->get('userid') && $_SERVER['REQUEST_URI'] != '/web/app_dev.php/agenda' && $_SERVER['REQUEST_URI'] != '/web/agenda')
                                return $this->redirect( $this->generateUrl('application_plateforme_agenda', array('userid' => $this->getUser()->getId()))); 
						}
                break;
                default:
                        // Test si l'utilisateur n'a pas le role Admin il ne peut pas switcher sur l'agenda d'un autre consultant
                        if(!$authorization->isGranted('ROLE_ADMIN') && !$authorization->isGranted('ROLE_COMMERCIAL'))
                        {
                            if($this->getUser()->getId() != $request->query->get('userid') && $_SERVER['REQUEST_URI'] != '/enteo/web/app_dev.php/agenda' && $_SERVER['REQUEST_URI'] != '/enteo/web/agenda')
                                return $this->redirect( $this->generateUrl('application_plateforme_agenda', array('userid' => $this->getUser()->getId())));
                        }
                break;
        }
        $em = $this->getDoctrine()->getManager(); // Entity manager
        if(empty($request->query->get('userid'))){
            // Recuperer tous les consultants (Pour Admin)
            $resultat = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser('ROLE_CONSULTANT');
            $cons = 1;
        }
        else{
            $resultat = $em->getRepository('ApplicationUsersBundle:Users')->find($request->query->get('userid'));
            $cons = 0;
        }
        // Definition de la couleur associé au calendrie
        if($cons == 1){
            $array_color_non_utilise = array_rand($tabColor,1); // Tirage d'une couleur
        }
        $beneficiaire = null;
        if(!is_null($request->query->get('benef')))
            $beneficiaire = $em->getRepository("ApplicationPlateformeBundle:Beneficiaire")->find($request->query->get('benef'));
        
        // Instanciation du formulaire d'ajout d'evenement
        $historique = new Historique();
        $form = $this->createForm(HistoriqueType::class, $historique);
        return $this->render('ApplicationPlateformeBundle:Agenda:agendas.html.twig', array(
            'consultant' => $resultat,
            'couleurs' => (isset($array_color_non_utilise))? $tabColor[$array_color_non_utilise]: 0,
            'form' => $form->createView(),
            'beneficiaire' => $beneficiaire
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
        // Initialisation des erreurs
        $this->get('session')->set('errorsdate', false);
        $this->get('session')->set('erreurs', false);
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
                // nouveau Bureau
                if(!empty($request->request->get('ville_id'))){
                    // On recupere la ville
                    $_SESSION['ville_id'] = $request->request->get('ville_id');
                    // On recupère l'id du beneficiaire
                    $_SESSION['benef'] = $request->request->get('idbeneficiaire');
                    $historique->setTypeRdv($request->request->get('typeRdv'));
                    // On stocke Les infos dans un tableau
                    $donnespost[] = array(
                        'nom' => $request->request->get('bureauRdv'),
                        'prenom' => $request->request->get('prenombeneficiaire'),
                        'bureau' => $request->request->get('namebureauselect'),
                        'ville' => $request->request->get('autrebureau'),
                        'adresse' => $request->request->get('adresse'),
                        'zip' => $request->request->get('ziph'),
                        'rdv' => $request->request->get('typeRdv')
                    );
                }
                else{
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
                        'bureau' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('namebureauselect'):'',
                        'ville' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('villeh'):'',
                        'adresse' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('adresseh'):'',
                        'zip' => ($request->request->get('typeRdv') == 'presenciel')? $request->request->get('ziph'):'',
                        'rdv' => ($request->request->get('typeRdv') == 'presenciel')? '' : $request->request->get('typeRdv')
                    );
                }
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
            // ===================================================================== //
            // ===== Verifier que les heures selectionnées ne sont pas passées ===== //
            // ===================================================================== //
            $dateCourant = new \DateTime('now');
            if($dateCourant >= $_SESSION['agenda'][1]->getDateDebut()->setTime($hd[0], $hd[1], $hd[2])){
                // Affiché l'erreur dans agendas.html.twig
                $this->get('session')->set('errorsdate', true);
                $this->get('session')->set('heuredate', $_SESSION['agenda'][1]->getDateDebut()->setTime($hd[0], $hd[1], $hd[2])->format('d-m-Y H:i:s'));
            }
            else{
                // On recupere les rendez-vous du beneficiaire 
                $resultats = $em->getRepository('ApplicationPlateformeBundle:Historique')->dateocuppee($_SESSION['agenda'][1]->getDateDebut()->setTime($hd[0], $hd[1], $hd[2]), $_SESSION['agenda'][1]->getDateFin()->setTime($hf[0], $hf[1], $hf[2]), $benef);
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
                    // On recupere la ville
                    if(!empty($_SESSION['ville_id'])){
                        $villeObject = $em->getRepository('ApplicationPlateformeBundle:Ville')->find($_SESSION['ville_id']);
                        // On instancie le nouveau Bureau
                        $bureau = new Bureau();
                        $bureau->setVille($villeObject); // ville
                        $bureau->setAdresse($_SESSION['agenda'][0]['adresse']); // adresse
                        $bureau->setNombureau($_SESSION['agenda'][0]['bureau']); // nom bureau
                        $bureau->setTemporaire(true); // temporaire pour tous bureaux ajoutés par le consultant ou l'admin lors de la saisie du formulaire d'agenda
                        $bureau->setActifInactif(1); // bureau actif
                        // Sauvegarde du bureau 
                        $em->persist($bureau);
                        $em->flush($bureau);
                        // On s'assure qu'on a le nouveau bureau enregistrer
                        $em->refresh($bureau);
                        $_SESSION['agenda'][1]->setBureau($bureau); // bureau
                    }
                    // On recupere le user pour le stcker en BD
                    $userbd = $em->getRepository("ApplicationUsersBundle:Users")->find($_SESSION['useridcredencial']);
                    $_SESSION['agenda'][1]->setConsultant($userbd); // le consultant
                    // On enregistre l'historique en BD
                    $_SESSION['agenda'][1]->getDateDebut()->setTime($hd[0], $hd[1], $hd[2]); // incrementation heure debut 
                    $_SESSION['agenda'][1]->getDateFin()->setTime($hf[0], $hf[1], $hf[2]); // incrementation heure debut 
                    $em->persist($_SESSION['agenda'][1]); // Mise en attente de sauvegarde de l'historique en BD
                    $em->flush();
                    
                }
            }
            // On supprime les sessions pour soulager le gc
            unset($_SESSION['agenda']);
            unset($_SESSION['ville_id']);
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

