<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Avis;
use Application\PlateformeBundle\Form\AvisType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AvisController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $avis = $em->getRepository('ApplicationPlateformeBundle:Avis')->findBy(
            array(), // Critere
            array('id' => 'desc')       // Tri
        );

        $noteTotalGlobale = 0 ;
        $nombrePublierEntheor = 0 ;
        $noteMoyenneAvisPublier = 0;
        foreach ($avis as $avisBeneficiaire){
            $noteTotalGlobale = $noteTotalGlobale + $avisBeneficiaire->getNoteGlobale();

            if($avisBeneficiaire->getAutorisationPublication())
                $noteMoyenneAvisPublier = $noteMoyenneAvisPublier + $avisBeneficiaire->getNoteGlobale();

            if($avisBeneficiaire->getAutorisationPublicationEntheor())
                $nombrePublierEntheor = $nombrePublierEntheor + 1;
        }
        return $this->render('ApplicationPlateformeBundle:Avis:index.html.twig', array(
            'avis' => $avis,
            'noteMoyenne' => $noteTotalGlobale / count($avis),
            'noteMoyenneAvisPublier' => $noteMoyenneAvisPublier,
            'nombreAvisPublierSurEntheor' => $nombrePublierEntheor
        ));
    }


    public function addAction(Request $request)
    {
        $avis = new Avis;
        $form = $this->createForm(AvisType::class, $avis);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $avis = $form->getData();

            $beneficiaireGet = $request->get('beneficiaire_ajax');

            if($beneficiaireGet != '' && !is_null($beneficiaireGet)){
                $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findOneById($beneficiaireGet);
                $avis->setBeneficiaire($beneficiaire);
            }

            if($avis->getAutorisationPublication())
                $avis->setDateAccordPublication(new \DateTime());

            $em->persist($avis);

            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Avis créée');

            return $this->redirectToRoute('application_list_avis');

        }

        return $this->render('ApplicationPlateformeBundle:Avis:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function searchAjaxBeneficiaireAction($string)
    {
        $em = $this->getDoctrine()->getManager();
        $nom = $string;
        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->searchBeneficiaireByNom($nom);

        $arrayBeneciaires = array();
        foreach($beneficiaires as $beneficiaire){
            $arrayBeneciaires[] = array(
                'id' => $beneficiaire->getId(),
                'nom' => $beneficiaire->getNomConso(),
                'prenom' => $beneficiaire->getPrenomConso(),
            );
        }

        return new JsonResponse(json_encode($arrayBeneciaires));

    }

}
