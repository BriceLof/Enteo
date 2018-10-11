<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Accompagnement;
use Application\PlateformeBundle\Entity\Employeur;
use Application\PlateformeBundle\Entity\Financeur;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Entity\Ville;
use Application\PlateformeBundle\Entity\Nouvelle;
use Application\PlateformeBundle\Form\ConsultantType;
use Application\PlateformeBundle\Form\NouvelleType;
use Application\PlateformeBundle\Form\NewsType;
use Application\PlateformeBundle\Form\ProjetType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Application\PlateformeBundle\Form\AddBeneficiaireType;
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
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        $listConsultantRv = $em->getRepository('ApplicationPlateformeBundle:News')->getListConsultantGotRv1OrRv2($beneficiaire);

        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN')
            || true === $authorization->isGranted('ROLE_COMMERCIAL')
            || true === $authorization->isGranted('ROLE_GESTION')
            || $this->getUser()->getBeneficiaire()->contains($beneficiaire )
            || $this->getUser()->getConsultants()->contains($beneficiaire->getConsultant()) ) {
        }else{
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }
        if (!$beneficiaire) {
            throw $this->createNotFoundException('le bénéfiiaire n\'existe pas.');
        }
         

        $editConsultantForm = $this->createConsultantEditForm($beneficiaire);
        $editForm = $this->createEditForm($beneficiaire);

        $editForm->get('codePostalHiddenBeneficiaire')->setData($beneficiaire->getVille()->getCp());
        $editForm->get('idVilleHiddenBeneficiaire')->setData($beneficiaire->getVille()->getId());

        $codePostalHiddenEmployeur = 0;
        $idVilleHiddenEmployeur = 0;

        if ($beneficiaire->getEmployeur() != null) {
            if ($beneficiaire->getEmployeur()->getVille() != null) {
                $codePostalHiddenEmployeur = $beneficiaire->getEmployeur()->getVille()->getCp();
                $idVilleHiddenEmployeur = $beneficiaire->getEmployeur()->getVille()->getId();
            }
        }

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

            $country = $editForm->get('pays')->getData();
            if($country == "FR"){
                $beneficiaire->setVilleMer($beneficiaire->getVille());
                $beneficiaire->setPays('FR');
            }
            else{
                $villeNoFr = $editForm->get('villeNoFr')->getData();
                $zipNoFr = $editForm->get('code_postal')->getData();
                $ville = new Ville();
                $ville->setNom($villeNoFr);
                $ville->setCp($zipNoFr);
                $ville->setDepartementId(0);

                $ville->setPays($country);
                $em->persist($ville);

                $beneficiaire->setVilleMer($ville);
                $beneficiaire->setVille($ville);
                $beneficiaire->setPays($country);
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

        if($beneficiaire->getAccompagnement() == null){
            $accompagnement = new Accompagnement();
            $financeur = new Financeur();
            $financeur2 = new Financeur();
            $accompagnement->addFinanceur($financeur);
            $accompagnement->addFinanceur($financeur2);
            $beneficiaire->setAccompagnement($accompagnement);
            $em->persist($beneficiaire);
            $em->flush();
        }

        $historiqueConsultant = $em->getRepository('ApplicationPlateformeBundle:Historique')->getHistoriqueConsultantForBeneficiaire($beneficiaire);

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:affiche.html.twig', array(
            'codePostalHiddenEmployeur' => $codePostalHiddenEmployeur,
            'idVilleHiddenEmployeur' => $idVilleHiddenEmployeur,
            'form_consultant' => $editConsultantForm->createView(),
            'beneficiaire'      => $beneficiaire,
            'edit_form'   => $editForm->createView(),
            'histoConsultant' => $historiqueConsultant,
            'listConsultantRv' => $listConsultantRv
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

            // =================================================================================================== //
            // ====================== Archiver tous les rendez-vous anterieurs de ================================ //
            // ====================== ce beneficiaire s'il est lié à un autre consultant ========================= //
            // ============= On part sur le fait que un beneficiaire est associé qu'à un seul consultant ========= //
            // =================================================================================================== //
            if(!empty($beneficiaire->getConsultant())){
                // On recupere les historiques de ce beneficiaire
                $historique = $beneficiaire->getHistorique();
                if(sizeof($historique)>0){
                    foreach($historique as $objet){
                        $objet->setEventarchive("on");
                        $em->persist($objet);
                    }
                }
            }
            $beneficiaire = $editConsultantForm->getData();
            $consultant = $beneficiaire->getConsultant();

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
            $historique->setUser($this->getUser());

            $mission = $em->getRepository('ApplicationUsersBundle:Mission')->findOneBy(array(
                "beneficiaire" => $beneficiaire
            ));
            if (!is_null($mission)) {
                $message = "Changement de consultant";
                $this->forward('ApplicationUsersBundle:MissionArchive:new', array(
                    'mission' => $mission,
                    'message' => $message,
                    'state' => 'modified',
                    'beneficiaire' => $beneficiaire,
                ));
            }

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

            if(!is_null($beneficiaire->getAccompagnement())){
                $financeur = $beneficiaire->getAccompagnement()->getFirstFinanceur();
                if($financeur != null){
                    $financeur->setNom($beneficiaire->getTypeFinanceur());
                    $financeur->setOrganisme($beneficiaire->getOrganisme());
                    $em->persist($financeur);
                }
            }

            $em->persist($beneficiaire);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Projet bénéficiaire modifié avec succès');
            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ).'#projetEditForm');
        }

        //$heureAccompagnement = $beneficiaire->getAccompagnement()->getHeure();
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
        $form->add('submit', SubmitType::class, array(
            'label' => 'Enregister',
            'attr' => array(
                'class' => 'btn btn-primary'
            )
        ));

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
     * earch form for Beneficiaire entity
     * le formulaire de recherche dans la page home et la reponse est utilisé en Ajax par l'action AjaxSearchBeneficiaire
     * dans HomeController.php
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        ini_set('memory_limit',-1);

        $idUtilisateur = null;
        $ids = null;

        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') or true === $this->get('security.authorization_checker')->isGranted('ROLE_COMMERCIAL') or true === $this->get('security.authorization_checker')->isGranted('ROLE_GESTION')) {
        }else{
            $idUtilisateur = $this->getUser()->getId();
            $ids[] = $this->getUser()->getId();
            foreach ($this->getUser()->getConsultants() as $consultant){
                $ids[] = $consultant->getId();
            }
        }

        $beneficiaire = new Beneficiaire();

        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);
        $form->add('submit', SubmitType::class, array('label' => 'Affiner'));
        $form->handleRequest($request);
		
//        if ($form->isValid()){
        if ($form->isSubmitted()){

            $complementStatut = $form->get('complementStatut')->getData();
            $complementDetailStatut = $form->get('complementDetailStatut')->getData();
            if (is_null($complementDetailStatut)){
                $complementDetailStatut = '>=';
            }
			$detailStatut =  $form->get("detailStatut")->getData();
			$cacher =  $form->get("cacher")->getData();
			$statut =  $form->get("statut")->getData();
            $tri = (int)$form['tri']->getData();
            $page = (int)$form['page']->getData();
            $ville = $form['ville']->getData();
			
            $codePostal = null;
            $dateDebut = null;
            $dateFin = null;
            
            $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(), $dateDebut, $dateFin, $idUtilisateur, false, $tri, $ville,$statut, $detailStatut, $complementStatut, $cacher, $complementDetailStatut, $ids);
            $results = $query->getResult();

            $start = 50*$page;

            $beneficiaires = array_slice($results,$start,50);
            $nbBeneficiaire = count($results);

            $page++;

            $nbPages = ceil(count($results) / 50);
            // Formulaire d'ajout d'une news à un bénéficiaire
            $news = new News();
            $formNews = $this->get("form.factory")->create(NewsType::class, $news);

            $nouvelle = new Nouvelle();
            $form_nouvelle = $this->get("form.factory")->create(NouvelleType::class, $nouvelle);

            return $this->render('ApplicationPlateformeBundle:Home:listeBeneficiaire.html.twig',array(
                'liste_beneficiaire' => $beneficiaires,
                'form' => $form->createView(),
                'results' => 'oui',
                'nbPages'               => $nbPages,
                'page'                  => $page,
                'form_news'             => $formNews->createView(),
                'form_nouvelle'             => $form_nouvelle->createView(),
                'nombreBeneficiaire'    => $nbBeneficiaire
            ));
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:recherche.html.twig',array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Fonction qui permet de rechercher un bénéficiaire par son nom et le retourne en Ajax
     * notamment utilisé pour le formulaire d'ajout dans le calendrier globale d'un consultant
     *
     * Ajax form for Beneficiaire entity
     *
     */
    public function ajaxSearchAction(Request $request)
    {

        $idUser = $request->query->get('consultant');

        $beneficiaire = new Beneficiaire();

        $nom = $request->query->get('nom');

        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);

        $form->add('submit', SubmitType::class, array('label' => 'Rechercher'));

        $form->handleRequest($request);

        $dateDebut = null;
        $dateFin = null;
        $ville = new Ville();

        if ($nom != null ){
            $beneficiaire->setNomConso($nom);
        }

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(), $dateDebut, $dateFin, $idUser, true);

        $results = $query->getArrayResult();
        $resultats = new JsonResponse(json_encode($results));

        return $resultats;
    }
    
    // Ajouter un bénéficiare manuellement (à l'opposé du webservice) 
    public function addBeneficiaireAction(Request $request)
    {   
        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(AddBeneficiaireType::class,$beneficiaire);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            // Beneficiaire
            $beneficiaire = $form->getData();
            $beneficiaire->setDateConfMer(new \DateTime());
            $beneficiaire->setDateHeureMer(new \DateTime());

            $country = $form->get('country')->getData();
            if($country == "FR"){
                $beneficiaire->setVilleMer($beneficiaire->getVille());
                $beneficiaire->setPays('FR');
            }
            else{
                $villeNoFr = $form->get('villeNoFr')->getData();
                $zipNoFr = $form->get('codePostal')->getData();
                $ville = new Ville();
                $ville->setNom($villeNoFr);
                $ville->setCp($zipNoFr);
                $ville->setDepartementId(0);
                $ville->setPays($country);
                $em->persist($ville);

                $beneficiaire->setVilleMer($ville);
                $beneficiaire->setVille($ville);
                $beneficiaire->setPays($country);
            }

            // origine beneficiaire 
            $origineBene1 =$form->get('origineMerQui')->getData();
            $origineBene2 =$form->get('origineMerComment')->getData();
            $origineBene3 = "";

            if($form->get('origineMerDetailComment')->getData() != "" && $form->get('origineMerComment')->getData() == 'payant' )
                $origineBene3 ="_".$form->get('origineMerDetailComment')->getData();

            $beneficiaire->setOrigineMer($origineBene1."_".$origineBene2.$origineBene3);
            
            $em->persist($beneficiaire);
           
            // News par défaut
            $news = new News;
            $news->setBeneficiaire($beneficiaire);

            $repositoryDetailStatut = $em->getRepository("ApplicationPlateformeBundle:DetailStatut");
            $detailStatut = $repositoryDetailStatut->find(1);

            $news->setStatut($detailStatut->getStatut());
            $news->setDetailStatut($detailStatut);
            $em->persist($news);
            
            $historique = new Historique();
            $historique->setSummary("");
            $historique->setTypeRdv("");
            $historique->setBeneficiaire($beneficiaire);
            $historique->setDescription(date('d/m/Y')." | ".$beneficiaire->getVille()->getNom()." | ".$beneficiaire->getOrigineMer());
            $historique->setEventId("0");
            $em->persist($historique);
            
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info', 'Bénéficiaire ajouté avec succès');
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }
        return $this->render('ApplicationPlateformeBundle:Beneficiaire:add.html.twig',array(
            'form' => $form->createView()
        ));
    }

    /**
     * la fonction qui permet de faire un print dans la fiche bénéficiaire
     *
     * @param $id
     * @return Response
     */
    public function printAction($id){
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        $montantTotal = 0;
        if($beneficiaire->getAccompagnement() != null){
            foreach ($beneficiaire->getAccompagnement()->getFinanceur() as $financeur){
                $montantTotal += $financeur->getMontant();
            }
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:print.html.twig',array(
            'montantTotal' => $montantTotal,
            'beneficiaire' => $beneficiaire
        ));
    }
}