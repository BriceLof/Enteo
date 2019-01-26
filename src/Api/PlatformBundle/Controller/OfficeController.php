<?php

namespace Api\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class OfficeController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"office"})
     * @Rest\Get("/offices")
     */
    public function getOfficesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $city = null;
        $limit = $request->get('limit');

        if (!is_null($request->get('zip'))){
            $city = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array('cp' => $request->get('zip')));
        }

//        return $city;

        $offices = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll2($city, $limit);

        return $offices;
    }

    /**
     * @Rest\View(serializerGroups={"office"})
     * @Rest\Get("/offices/{id}")
     */
    public function getOfficeAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $office = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);

        if (empty($office)){
            return new JsonResponse(['message' => 'office not found'], Response::HTTP_NOT_FOUND);
        }

        return $office;
    }
}
