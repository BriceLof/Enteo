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
     * retourne une liste de ville par rapport au cp
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

    /**
     * retourne une liste de ville par rapport au departement
     *
     * @param Request $request
     * @return $this
     */
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


    /**
     * retourne une liste de ville par rapport a son nom
     * utilisé par l'autocompletion
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxGetVilleAction(Request $request)
    {
        $tabVille = [];
        $em = $this->getDoctrine()->getManager();

        $villesRepo = $em->getRepository("ApplicationPlateformeBundle:Ville")->getVilles($request->query->get('nomVille'));

        foreach($villesRepo as $villes)
        {
            $tabVille[] = array( "id" => $villes->getId(),  "nom" => $villes->getNom(), "cp" => $villes->getCp());
        }

        $resultats = new JsonResponse(json_encode($tabVille));
        return $resultats;

    }

    /**
     * retourne une liste de departement par rapport a la region
     *
     * @param $region
     * @return JsonResponse
     */
    public function getDptByRegionAction($region){
        $em = $this->getDoctrine()->getManager();
        $villes = $em->getRepository("ApplicationPlateformeBundle:Ville")->findBy(array(
            'region' => $region
        ));
        $tab = array();
        $tab[] = array(
            'dpt' => '',
            'code' =>'',
            'codeShow' => ''
        );
        foreach ($villes as $ville){
            if (preg_match('/^97/', $ville->getCp()) || preg_match('/^98/', $ville->getCp())){
                $code = substr($ville->getCp(), 0, 3);
            }else{
                $code = substr($ville->getCp(), 0, 2);
            }
            if (!array_key_exists($code, $tab)){
                $tab[$code] = array(
                    'dpt' => $ville->getDpt(),
                    'code' => $code,
                    'codeShow' => '('.$code.')',
                );
            }
        }
        $resultats = new JsonResponse(json_encode($tab));
        return $resultats;
    }

}