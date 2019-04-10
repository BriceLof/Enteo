<?php

namespace Api\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AdviceController extends Controller
{

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This method aims to get advice(s) according to criterion that you provide",
     *  filters={
     *      {"name"="limit", "dataType"="integer", "description"="number of office in response"}
     *  },
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when office is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     * @Rest\View(serializerGroups={"advice"})
     * @Rest\Get("/advices")
     */
    public function getAdvicesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $limit = $request->get('limit');

        $advices = $em->getRepository('ApplicationPlateformeBundle:Avis')->findAllEntheor($limit);

        return $advices;
    }
}