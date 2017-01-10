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
            $villeCodePostal = $em->getRepository('ApplicationPlateformeBundle:Ville')->findBy(array('cp' => $cp));

            if ($villeCodePostal) {
                $villes = array();
                foreach ($villeCodePostal as $ville) {
                    $villes[] = $ville->getNom();
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
}