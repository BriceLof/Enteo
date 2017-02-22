<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Employeur;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Entity\Ville;
use Application\PlateformeBundle\Form\ConsultantType;
use Application\PlateformeBundle\Form\NewsType;
use Application\PlateformeBundle\Form\ProjetType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Application\PlateformeBundle\Form\RechercheBeneficiaireType;
use Symfony\Component\HttpFoundation\Response;


/**
 * Announce controller.
 *
 */
class BeneficiaireController extends Controller
{
    /**
     * affiche la partie fiche bénéficiaire
     *
     */
    public function showAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        if(isset($id)){
            // stockage de l'id du beneficiaire 
            $_SESSION['beneficiaireid'] = $id;
            $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        }
        else if(!empty($_SESSION['beneficiaireid'])){
            $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($_SESSION['beneficiaireid']);
            unset($_SESSION['beneficiaireid']);
        }
        $histo_beneficiaire = $em->getRepository("ApplicationPlateformeBundle:Historique")->beneficiaireOne($beneficiaire);
        // ====================================================== //
        // ===== Mise à jour des evenements du beneficiaire ===== //
        // ====================================================== //
        if(count($histo_beneficiaire) > 0 && $histo_beneficiaire[0]->getEventId() != '0' && empty($_SESSION['majevenementdanshistorique'])){
            $redirectUri = 'http://'.$_SERVER['SERVER_NAME'].$this->get('router')->generate('application_plateforme_agenda_evenement', array(), true);
            if(!empty($_SESSION['firstpast'])){
                 unset($_SESSION['firstpast']); // On supprime la session
                 // Appel des services de Google calendar
                 $googleCalendar = $this->get('application_google_calendar');
                 $googleCalendar->setRedirectUri($redirectUri);
                 if (!empty($_SESSION['code'])){
                    $client = $googleCalendar->getClient($_SESSION['code']);
                 }else {
                    $client = $googleCalendar->getClient();
                 }
                 if (is_string($client)) {
                    header('Location: ' . filter_var($client, FILTER_SANITIZE_URL)); // Redirection sur l'url d'autorisation
                    exit;
                 } 
            }
            else{
                // stockage des infos
                $donnes[] = $id;
                $donnes[] = $this->getUser()->getId();
                $donnes[] = 'page beneficiaire';
                $_SESSION['firstpast'] = $donnes;
                // Données pour Google calendar
                $_SESSION['calendrierId'] = $histo_beneficiaire[0]->getConsultant()->getCalendrierid(); // id du calendrier
                // On stocke l'id du user pour la personnalisation du fichier credentials
                // dans google calendar qui permet la connexion à l'agenda du consultant
                $_SESSION['useridcredencial'] = $histo_beneficiaire[0]->getConsultant()->getId();
                // Appel du service google calendar
                $googleCalendar = $this->get('application_google_calendar');
                $googleCalendar->setRedirectUri($redirectUri);
                if (!empty($_SESSION['code'])){
                    $client = $googleCalendar->getClient($_SESSION['code']);
                }else {
                    $client = $googleCalendar->getClient();
                }
                if (is_string($client)) {
                    header('Location: ' . filter_var($client, FILTER_SANITIZE_URL)); // Redirection sur l'url d'autorisation
                    exit;
                }
            }
        }
        // Si le client existe alors on recupere les evenements
        if(isset($client) && empty($_SESSION['majevenementdanshistorique'])){           
            foreach($histo_beneficiaire as $histo){
                $evenement = $googleCalendar->getEvent($_SESSION['calendrierId'], $histo->getEventId(), []);
                // Si l'evenement est supprimé dans le calendrier depuis la boite gmail alors on l'archive
                if($evenement->getStatus() == 'cancelled'){
                    $query = $em->getRepository("ApplicationPlateformeBundle:Historique")->historiqueArchive($histo->getEventId(), 'on');
                }
                else{
                    // On met à jour les evenements
                    $heuredeb = str_replace('T',' ',$evenement->getStart()->getDateTime());
                    $heurefin = str_replace('T',' ',$evenement->getEnd()->getDateTime());
                    $heuredeb = str_replace('+01:00','',$heuredeb); 
                    $heurefin = str_replace('+01:00','',$heurefin);
                    $datedeb = new \DateTime($heuredeb); // date debut
                    $datefin = new \DateTime($heurefin); // date fin
                    $heuredeb = (new \DateTime($heuredeb))->format('H:i:s'); // heure debut
                    $heurefin = (new \DateTime($heurefin))->format('H:i:s'); // heure fin
                    if($heuredeb != $histo->getHeuredebut() || $heurefin != $histo->getHeurefin()){
                        // Mise à jour en BD  
                        $em->getRepository("ApplicationPlateformeBundle:Historique")->historiquemaj($datedeb, $datefin, $heuredeb, $heurefin, $histo->getEventId());
                    }
                }
            }
        }
        if(!empty($_SESSION['majevenementdanshistorique'])) unset($_SESSION['majevenementdanshistorique']); // On supprime la session
        if(!empty($_SESSION['firstpast'])) unset($_SESSION['firstpast']); // On supprime la session
        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || $authorization->isGranted('ROLE_COMMERCIAL') || $this->getUser()->getBeneficiaire()->contains($beneficiaire ) ) {
        }else{
            throw $this->createNotFoundException('Vous n\'avez pas accès a cette page!');
        }
        if (!$beneficiaire) {
            throw $this->createNotFoundException('le bénéfiiaire n\'existe pas.');
        }
        $editConsultantForm = $this->createConsultantEditForm($beneficiaire);
        $editForm = $this->createEditForm($beneficiaire);

        $editForm->get('codePostalHiddenBeneficiaire')->setData($beneficiaire->getVille()->getCp());
        $editForm->get('idVilleHiddenBeneficiaire')->setData($beneficiaire->getVille()->getId());

        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            foreach ($beneficiaire->getContactEmployeur() as $contactEmployeur){
                $contactEmployeur->setBeneficiaire($beneficiaire);
                $em->persist($contactEmployeur);
            }
            $employeur = $beneficiaire->getEmployeur();
            if ($employeur === NULL){
                $employeur = new Employeur();
            }
            $em->persist($employeur);
            $em->persist($beneficiaire);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Fiche Bénéficiaire modifié avec succès');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:affiche.html.twig', array(
            'form_consultant' => $editConsultantForm->createView(),
            'beneficiaire'      => $beneficiaire,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * editer le consultant affilié au bénéficiaire
     *
     */
    public function editConsultantAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        if (!$beneficiaire) {
            throw $this->createNotFoundException('le bénéfiiaire n\'existe pas.');
        }

        $editConsultantForm = $this->createConsultantEditForm($beneficiaire);
        $editConsultantForm->handleRequest($request);

        if ($request->isMethod('POST') && $editConsultantForm->isValid()) {
            $beneficiaire = $editConsultantForm->getData();

            //enregistrement de l'ajout ou modification de consultant dans le
            $historique = new Historique();
            $historique->setHeuredebut(new \DateTime('now'));
            $historique->setHeurefin(new \DateTime('now'));
            $historique->setSummary("");
            $historique->setTypeRdv("");
            $historique->setConsultant($beneficiaire->getConsultant());
            $historique->setBeneficiaire($beneficiaire);
            $historique->setDescription("Ajout/modification de consultant : ".ucfirst(strtolower($beneficiaire->getConsultant()->getPrenom()))." ".ucfirst(strtolower($beneficiaire->getConsultant()->getNom())));
            $historique->setEventId("0");

            $em->persist($beneficiaire);
            $em->persist($historique);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Consultant modifié avec succès');
            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:editConsultant.html.twig', array(
            'form_consultant' => $editConsultantForm->createView(),
            'beneficiaire'      => $beneficiaire,
        ));
    }

    /**
     * afficher/editer le projet du bénéficiaire
     *
     */
    public function projetAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        if (!$beneficiaire) {
            throw $this->createNotFoundException('le bénéfiiaire n\'existe pas.');
        }

        $projetForm = $this->createProjetEditForm($beneficiaire);
        $projetForm->add('submit',  SubmitType::class, array(
            'label' => 'Mettre à jour'
        ));

        $projetForm->handleRequest($request);

        if ($request->isMethod('POST') && $projetForm->isValid()) {
            $beneficiaire = $projetForm->getData();
            $em->persist($beneficiaire);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Projet bénéficiaire modifié avec succès');
            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ).'#projetEditForm');
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:projet.html.twig', array(
            'projet_form' => $projetForm->createView(),
            'beneficiaire'      => $beneficiaire,
        ));
    }


    /**
     * Creates a form to edit a beneficiaire entity.
     *
     * @param Beneficiaire $beneficiaire The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Beneficiaire $beneficiaire)
    {
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);

        $form->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));

        return $form;
    }

    /**
     * Creates a form to edit consultant in beneficiaire entity.
     *
     * @param Beneficiaire $beneficiaire The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createConsultantEditForm(Beneficiaire $beneficiaire)
    {
        $form = $this->createForm(ConsultantType::class, $beneficiaire);
        return $form;
    }

    /**
     * Creates a form to edit projet in beneficiaire entity.
     *
     * @param Beneficiaire $beneficiaire The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createProjetEditForm(Beneficiaire $beneficiaire)
    {
        $form = $this->createForm(ProjetType::class, $beneficiaire);
        return $form;
    }

    /**
     * Search form for Beneficiaire entity
     *
     * @param
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function searchAction(Request $request,$page = 1 )
    {
        $idUtilisateur = null;

        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        }else{
            $idUtilisateur = $this->getUser()->getId();
        }

        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);

        $form->add('submit', SubmitType::class, array('label' => 'Affiner'));

        $form->handleRequest($request);

        if ($form->isValid()){
            if (!is_null($form["ville"]["nom"]->getData())) {
                $em = $this->getDoctrine()->getManager();
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                    'id' => $form["ville"]["nom"]->getData(),
                ));
                $beneficiaire->setVille($ville);
            }

            $codePostal = null;
            $dateDebut = null;
            $dateFin = null;

            if(!is_null($form["ville"]["cp"]->getData())){
                $codePostal = $form["ville"]["cp"]->getData();
            }

            if(!is_null($form['dateDebut']->getData())){
                $dateDebut = $form['dateDebut']->getData();
            }
            if(!is_null($form['dateFin']->getData())){
                $dateFin = $form['dateFin']->getData();
            }




            $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(), $dateDebut, $dateFin, $idUtilisateur);
            $results = $query->getResult();
            $nbPages = ceil(count($results) / 50);
            // Formulaire d'ajout d'une news à un bénéficiaire
            $news = new News();
            $formNews = $this->get("form.factory")->create(NewsType::class, $news);

            return $this->render('ApplicationPlateformeBundle:Home:index.html.twig',array(
                'liste_beneficiaire' => $results,
                'form' => $form->createView(),
                'liste_beneficiaire' => $results,
                'results' => $results,
                'nbPages'               => $nbPages,
                'page'                  => $page,
                'form_news'             => $formNews->createView(),
                'nombreBeneficiaire'    => count($results)
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:recherche.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Ajax form for Beneficiaire entity
     *
     */
    public function ajaxSearchAction(Request $request)
    {

        $beneficiaire = new Beneficiaire();

        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);

        $form->add('submit', SubmitType::class, array('label' => 'Rechercher'));

        $form->handleRequest($request);

        $dateDebut = null;
        $dateFin = null;
        $ville = new Ville();
        
        if (!is_null($form["ville"]["nom"]->getData())) {
            $em = $this->getDoctrine()->getManager();
            $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                'id' => $form["ville"]["nom"]->getData(),
            ));
            $beneficiaire->setVille($ville);
        }

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(), $dateDebut, $dateFin);
        $results = $query->getArrayResult();
        $resultats = new JsonResponse(json_encode($results));
        $resultats->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $resultats->headers->set('Access-Control-Allow-Origin', '*');
        $resultats->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');


        return $resultats;
    }

    /**
     * Search form for Beneficiaire entity
     */
    public function search2Action(Request $request)
    {
        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);

        $form->add('submit', SubmitType::class, array('label' => 'Rechercher'));

        $form->handleRequest($request);

        if ($form->isValid()){

            $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData());
            $results = $query->getResult();

            $template = $this->forward('ApplicationPlateformeBundle:Beneficiaire:resultatRecherche.html.twig',array(
                'results' => $results,
            ))->getContent();

            $response = new Response($template);
            $response->headers->set('Content-Type', 'text/html');

            return $response;
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:recherche.html.twig',array(
            'form' => $form->createView(),
        ));
    }
}