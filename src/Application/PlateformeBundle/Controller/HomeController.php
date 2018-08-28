<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Entity\Nouvelle;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Form\NewsType;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Application\PlateformeBundle\Form\NouvelleType;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        // Récupération liste béneficiaires
        $repository_beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire');

        //Mis à jour du statut : verifie si la requete est une requete AJAX
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        $nbPerPage = 50;

        // ADMIN et autres
        $authorization = $this->get('security.authorization_checker');
        if ($authorization->isGranted('ROLE_ADMIN') || $authorization->isGranted('ROLE_COMMERCIAL') || $authorization->isGranted('ROLE_GESTION')) {
            $beneficiaires = $repository_beneficiaire->getBeneficiaire($page, $nbPerPage);
            $nombreBeneficiaire = count($beneficiaires);
            //var_dump($beneficiaires);
        } // CONSULTANT
        else {
            $ids[] = $this->getUser()->getId();
            foreach ($this->getUser()->getConsultants() as $consultant){
                $ids[] = $consultant->getId();
            }
            $beneficiaires = $repository_beneficiaire->getBeneficiaire($page, $nbPerPage, null, true, $ids);
            $nombreBeneficiaire = count($beneficiaires);
            //$this->getUser()->getBeneficiaire();
            if (count($beneficiaires) == 0) {
                $beneficiaires = null;
                return $this->render('ApplicationPlateformeBundle:Home:index.html.twig', array('liste_beneficiaire' => $beneficiaires));
            }

        }

        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($beneficiaires) / $nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // Formulaire de mise à jour du statut d'un bénéficiaire
        $news = new News;
        $form = $this->get("form.factory")->create(NewsType::class, $news);
        
        $nouvelle = new Nouvelle;
        $form_nouvelle = $this->get("form.factory")->create(NouvelleType::class, $nouvelle);

        /* Dernier suivi administratif par beneficiaire qu'on stock dans un tableau pour utiliser dans la vue
         * Lors du passage du statut (news) en RV1 positif, le statut qui sera afficher par las suite sera celui sui suivi administratif
         */
        $suiviAdministratifRepo = $em->getRepository('ApplicationPlateformeBundle:SuiviAdministratif');
        $suiviAdministratifByBeneficiaireArray = array();
        $statutBeneficiaireArray = array();
        $detailStatut = $em->getRepository('ApplicationPlateformeBundle:detailStatut')->findByDetail("RV1 Positif");
        $newsRepo = $em->getRepository('ApplicationPlateformeBundle:News');
        foreach ($beneficiaires as $beneficiaire) {
            $lastSuiviAdministratif = $suiviAdministratifRepo->findByBeneficiaire($beneficiaire);
            if (count($lastSuiviAdministratif) > 0) $suiviAdministratifByBeneficiaireArray[$beneficiaire->getId()] = $lastSuiviAdministratif;
            // scheck si le beneficiaire a un statut en rv1 positif
            $statutBeneficiaire = $newsRepo->findOneBy(
                array('beneficiaire' => $beneficiaire, 'detailStatut' => $detailStatut),
                array('id' => "DESC")
            );
            if (count($statutBeneficiaire) > 0) $statutBeneficiaireArray[$beneficiaire->getId()] = $statutBeneficiaire;
        }

        return $this->render('ApplicationPlateformeBundle:Home:index.html.twig', array(
            'liste_beneficiaire' => $beneficiaires,
            'nbPages' => $nbPages,
            'page' => $page,
            'form_news' => $form->createView(),
            'form_nouvelle' => $form_nouvelle->createView(),
            'nombreBeneficiaire' => $nombreBeneficiaire,
            'suiviAdministratifByBeneficiaire' => $suiviAdministratifByBeneficiaireArray,
            'statutBeneficiaire' => $statutBeneficiaireArray
        ));
    }

    public function detailStatutAction($idStatut)
    {
        $em = $this->getDoctrine()->getManager();

        $statut = $em->getRepository("ApplicationPlateformeBundle:Statut")->find($idStatut);
        $detailsStatut = $em->getRepository("ApplicationPlateformeBundle:DetailStatut")->findBy(array("statut" => $statut));

        foreach ($detailsStatut as $detailStatut) {
            $tabDetail[] = array("id" => $detailStatut->getId(), "detail" => $detailStatut->getDetail());
        }

        $response = new JsonResponse();
        return $response->setData(array('details' => $tabDetail));
    }

    public function infoBeneficiaireAction(Beneficiaire $beneficiaire)
    {
        $em = $this->getDoctrine()->getManager();
        /* Dernier suivi administratif par beneficiaire qu'on stock dans un tableau pour utiliser dans la vue
         * Lors du passage du statut (news) en RV1 positif, le statut qui sera afficher par las suite sera celui sui suivi administratif
         */
        $suiviAdministratifRepo = $em->getRepository('ApplicationPlateformeBundle:SuiviAdministratif');
        $suiviAdministratifByBeneficiaireArray = array();
        $statutBeneficiaireArray = array();
        $detailStatut = $em->getRepository('ApplicationPlateformeBundle:detailStatut')->findByDetail("RV1 Positif");
        $newsRepo = $em->getRepository('ApplicationPlateformeBundle:News');

        $lastSuiviAdministratif = $suiviAdministratifRepo->findByBeneficiaire($beneficiaire);
        if (count($lastSuiviAdministratif) > 0) $suiviAdministratifByBeneficiaireArray[$beneficiaire->getId()] = $lastSuiviAdministratif;
        // scheck si le beneficiaire a un statut en rv1 positif
        $statutBeneficiaire = $newsRepo->findOneBy(
            array('beneficiaire' => $beneficiaire, 'detailStatut' => $detailStatut),
            array('id' => "DESC")
        );
        if (count($statutBeneficiaire) > 0) $statutBeneficiaireArray[$beneficiaire->getId()] = $statutBeneficiaire;

        return $this->render('ApplicationPlateformeBundle:Home:infoBeneficiaire.html.twig', (array(
            'beneficiaire' => $beneficiaire,
            'suiviAdministratifByBeneficiaire' => $suiviAdministratifByBeneficiaireArray,
            'statutBeneficiaire' => $statutBeneficiaireArray
        )));
    }

    /**
     * recherche ajax du bénéficiaire dans la home qui fait appel a l'action search dans BeneficiaireController.php
     * et renvoie un reponse json pour l'ajax
     *
     * @param  Request $request
     *
     * @return Response (json)
     */
    public function ajaxSearchBeneficiaireAction(Request $request)
    {
        $template = $this->forward('ApplicationPlateformeBundle:Beneficiaire:search', (array('request' => $request)))->getContent();
        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}

