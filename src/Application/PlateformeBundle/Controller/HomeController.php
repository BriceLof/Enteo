<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Form\NewsType;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends Controller
{
    public function indexAction(Request $request, $page)
    {
        $session = new Session();
 
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        //var_dump($this->getUser()->getRoles());die;

        //demande de philippe pour que le nombre de beneficiaire par page soit de 50
        //auparavant 4
        $nbPerPage = 50;
        
        $em = $this->getDoctrine()->getManager();  
        // Récupération liste béneficiaires
        $repository_beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire');
        
        // ADMIN et autres 
        $authorization = $this->get('security.authorization_checker');
        if($authorization->isGranted('ROLE_ADMIN') || $authorization->isGranted('ROLE_COMMERCIAL')){
            $beneficiaires = $repository_beneficiaire->getBeneficiaire($page, $nbPerPage);
           //var_dump($beneficiaires);
        }
        // CONSULTANT
        else {
            $beneficiaires = $repository_beneficiaire->getBeneficiaire($page, $nbPerPage,$this->getUser()->getId() );
            //$this->getUser()->getBeneficiaire();
            if(count($beneficiaires) == 0 ) 
            {
                $beneficiaires = null;
                return $this->render('ApplicationPlateformeBundle:Home:index.html.twig', array('liste_beneficiaire'    => $beneficiaires));
            }
            
        }
        
        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($beneficiaires) / $nbPerPage);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
    
        // Formulaire d'ajout d'une news à un bénéficiaire
        $news = new News;
        $form = $this->get("form.factory")->create(NewsType::class, $news);
        //$form->get('detailStatutActuelIDHidden')->setData($news->getStatut()->getId());
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $news = $form->getData();
           
            $beneficiaire_id = $request->request->get('beneficiaire_id');
            $news->setBeneficiaire($repository_beneficiaire->findOneById($beneficiaire_id));

            $em->persist($news);
            $em->flush();
            
            // Envoi d'un mail selon le statut
            
            
            $url = $this->get('router')->generate('application_plateforme_homepage').'#b'.$beneficiaire_id;
            return $this->redirect($url);
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

