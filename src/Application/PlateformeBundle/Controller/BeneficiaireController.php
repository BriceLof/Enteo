<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Ville;
use Application\PlateformeBundle\Form\ConsultantType;
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
            ));
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
    public function searchAction(Request $request)
    {

        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);

        $form->add('submit', SubmitType::class, array('label' => 'Rechercher'));

        $form->handleRequest($request);

        if ($form->isValid()){
            $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData());
            $results = $query->getResult();

            return $this->render('ApplicationPlateformeBundle:Beneficiaire:recherche.html.twig',array(
                'form' => $form->createView(),
                'results' => $results,
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


        if ($form->isValid()){

            $ville = new Ville();
            if (!is_null($form["ville"]["nom"]->getData())) {
                $em = $this->getDoctrine()->getManager();
                $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                    'nom' => $form["ville"]["nom"]->getData(),
                ));
            }

            $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(),$ville);
            $results = $query->getArrayResult();
            return new JsonResponse(json_encode($results));
        }

        return $this->render('ApplicationPlateformeBundle:Beneficiaire:recherche.html.twig',array(
            'form' => $form->createView(),
        ));
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