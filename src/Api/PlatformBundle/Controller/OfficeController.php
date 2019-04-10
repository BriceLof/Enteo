<?php

namespace Api\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class OfficeController extends Controller
{

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This method aims to get office(s) according to criterion that you provide",
     *  filters={
     *      {"name"="zip", "dataType"="string", "description"="zip code"},
     *      {"name"="limit", "dataType"="integer", "description"="number of office in response"},
     *      {"name"="elearning", "dataType"="boolean", "description"="elearning office"}
     *  },
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when office is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     * @Rest\View(serializerGroups={"office"})
     * @Rest\Get("/offices")
     */
    public function getOfficesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $city = null;
        $limit = $request->get('limit');

        if (!is_null($request->get('zip'))) {
            $city = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array('cp' => $request->get('zip')));
        }

        $offices = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll2($city, $limit, true);

        if(empty($offices) || ($request->get('elearning') == true )){
            $offices = $this->getOfficeAction(208, $request);
        }

        return $offices;
    }

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This method aims to get office",
     *  requirements={
     *     {
     *         "name"="id",
     *         "dataType"="integer",
     *         "requirement"="\d+",
     *         "description"="id of the office"
     *     }
     *  },
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when office is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     * @Rest\View(serializerGroups={"office"})
     * @Rest\Get("/offices/{id}")
     */
    public function getOfficeAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);

        if (empty($office)) {
            return new JsonResponse(['message' => 'office not found'], Response::HTTP_NOT_FOUND);
        }

        return $office;
    }
}
