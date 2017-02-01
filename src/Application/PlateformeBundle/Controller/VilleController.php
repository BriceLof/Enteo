<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Ville;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ville controller.
 *
 */
class VilleController extends Controller
{
    /**
     * Ajax form for Ville entity
     *
     */
    public function ajaxSearchByCpAction(Request $request, $cp)
    {
        if ($request->isXmlHttpRequest()) {

            $em = $this->getDoctrine()->getManager();
            $villeCodePostal = $em->getRepository('ApplicationPlateformeBundle:Ville')->findByDepartement($cp);

            if ($villeCodePostal) {
                $villes = array();
                foreach ($villeCodePostal as $ville) {
                    $villes[] = array("id" => $ville->getId(), "nom" => $ville->getNom(), "cp" => $ville->getCp());
                }

            } else {
                $villes = null;
            }

            $response = new JsonResponse();
            return $response->setData(array('ville' => $villes));
        }else{
            throw new \Exception('erreur');
        }
    }
    
    public function getVilleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $villesRepo = $em->getRepository("ApplicationPlateformeBundle:Ville")->findByDepartement($request->get('departement'));
        
        foreach($villesRepo as $villes)
        {
            $tabVille[] = array( "id" => $villes->getId(),  "nom" => $villes->getNom(), "cp" => $villes->getCp());
        }

        $response = new JsonResponse();
        return $response->setData(array('villes' => $tabVille));
        
    }
    
}