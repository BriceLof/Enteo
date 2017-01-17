<?php

namespace Application\PlateformeBundle\Controller;

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

        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        //var_dump(is_array($this->getUser()->getBeneficiaire()));die;
        /**
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_CONSULTANT') && !$this->getUser()->getBeneficiaire()->contains($beneficiaire) ){
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        }
         */
        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') || $this->getUser()->getBeneficiaire()->contains($beneficiaire) ) {
        }else{
            throw $this->createNotFoundException('Vous n\'avez pas accès a cette page!');
        }

        if (!$beneficiaire) {
            throw $this->createNotFoundException('le bénéfiiaire n\'existe pas.');
        }
        /**
        $lastNews = [];
        $lastNews = end($beneficiaire->getNews());
        var_dump($lastNews);die;
         */
        $editConsultantForm = $this->createConsultantEditForm($beneficiaire);

        $editForm = $this->createEditForm($beneficiaire);
        $editForm->handleRequest($request);

        if ($request->isMethod('POST') && $editForm->isValid()) {
            $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneByNom($editForm['ville']['nom']->getData());

            $ville->addBeneficiaire($beneficiaire);
            $qb = $em->createQueryBuilder();
            $q = $qb->update('ApplicationPlateformeBundle:Beneficiaire', 'u')
                ->set('u.ville', '?1')
                ->set('u.civiliteConso', '?3')
                ->set('u.nomConso', '?4')
                ->set('u.prenomConso', '?5')
                ->set('u.poste', '?6')
                ->set('u.encadrement', '?7')
                ->set('u.telConso', '?8')
                ->set('u.tel2', '?9')
                ->set('u.emailConso', '?10')
                ->set('u.email2', '?11')
                ->set('u.pays', '?12')
                ->set('u.numSecu', '?13')
                ->set('u.dateNaissance', '?14')
                ->where('u.id = ?2')
                ->setParameter(1, $ville)
                ->setParameter(2, $id)
                ->setParameter(3, $beneficiaire->getCiviliteConso())
                ->setParameter(4, $beneficiaire->getNomConso())
                ->setParameter(5, $beneficiaire->getPrenomConso())
                ->setParameter(6, $beneficiaire->getPoste())
                ->setParameter(7, $beneficiaire->getEncadrement())
                ->setParameter(8, $beneficiaire->getTelConso())
                ->setParameter(9, $beneficiaire->getTel2())
                ->setParameter(10, $beneficiaire->getEmailConso())
                ->setParameter(11, $beneficiaire->getEmail2())
                ->setParameter(12, $beneficiaire->getPays())
                ->setParameter(13, $beneficiaire->getNumSecu())
                ->setParameter(14, $beneficiaire->getDateNaissance())
                ->getQuery();
            $p = $q->execute();
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
            $em->persist($beneficiaire);
            $em->flush();
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
                    'nom' => $form["ville"]["nom"]->getData(),
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
                'results' => $results,
                'nbPages'               => $nbPages,
                'page'                  => $page,
                'form_news'             => $formNews->createView()
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
                'nom' => $form["ville"]["nom"]->getData(),
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