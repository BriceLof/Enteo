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

        //var_dump($this->getUser()->getRoles());die;

        //demande de philippe pour que le nombre de beneficiaire par page soit de 50
        //auparavant 4
        $nbPerPage = 50;
        
        $em = $this->getDoctrine()->getManager();  
        // Récupération liste béneficiaires
        $repository_beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire');
        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $beneficiaires = $repository_beneficiaire->getBeneficiaire($page, $nbPerPage);
        }else {
            
            if(count($this->getUser()->getBeneficiaire()) > 0)
            {
                $beneficiaires = $this->getUser()->getBeneficiaire();
                
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
            else{
                return $this->render('ApplicationPlateformeBundle:Home:index.html.twig', array(
                    'liste_beneficiaire'    => null, 
                ));
            }
            
            
        }
        
        
       /* foreach($beneficiaires as $b)
        {
            var_dump($b);exit;
        }
        exit;*/
        
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

