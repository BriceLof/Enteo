<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Form\NewsType;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends Controller
{
    
    public function indexAction(Request $request, $page)
    {

        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
    
        $nbPerPage = 4;
        
        $em = $this->getDoctrine()->getManager();  
        // Récupération liste béneficiaires
        $repository_beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire');
        $beneficiaires = $repository_beneficiaire->getBeneficiaire($page, $nbPerPage);
        
        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($beneficiaires) / $nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
    
        // Formulaire d'ajout d'une news à un bénéficiaire
        $news = new News;
        $form = $this->get("form.factory")->create(NewsType::class, $news);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $news = $form->getData();
           
            $beneficiaire_id = $request->request->get('beneficiaire_id');
            $news->setBeneficiaire($repository_beneficiaire->findOneById($beneficiaire_id));

            $em->persist($news);
            $em->flush();
        }

        return $this->render('ApplicationPlateformeBundle:Home:index.html.twig', array(
            'liste_beneficiaire'    => $beneficiaires, 
            'nbPages'               => $nbPages,
            'page'                  => $page,
            'form_news'             => $form->createView()
        ));
    }
    
    public function detailStatutAction($idStatut)
    {
        $em = $this->getDoctrine()->getManager();
        
        $statut = $em->getRepository("ApplicationPlateformeBundle:Statut")->find($idStatut);
        $detailsStatut = $em->getRepository("ApplicationPlateformeBundle:DetailStatut")->findBy(array("statut" => $statut));
        
        foreach($detailsStatut as $detailStatut)
        {
            $tabDetail[] = array( "id" => $detailStatut->getId(),  "detail" => $detailStatut->getDetail());
        }

        $response = new JsonResponse();
        return $response->setData(array('details' => $tabDetail));
    }
}

