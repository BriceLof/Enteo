<?php

namespace Api\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Intl\Intl;

class CityController extends Controller
{

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This method aims to get cities according to criterion that you provide",
     *  filters={
     *      {"name"="zip", "dataType"="integer", "description"="zip"}
     *  },
     *     statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when office is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     * @Rest\View(serializerGroups={"city"})
     * @Rest\Get("/cities")
     */
    public function getCityAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $zip = $request->get('zip');

        $cities = $em->getRepository('ApplicationPlateformeBundle:Ville')->findByDepartement($zip);

        return $cities;
    }

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This method aims to get ccountries",
     *
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when office is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     * @Rest\View(serializerGroups={"countries"})
     * @Rest\Get("/countries")
     */
    public function getCountriesAction(Request $request)
    {
        $countries = Intl::getRegionBundle()->getCountryNames();
        return $countries;
    }

    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This method aims to get places",
     *
     *  statusCodes={
     *         200="Returned when successful",
     *         404={
     *           "Returned when office is not found",
     *           "Returned when something else is not found"
     *         }
     *     }
     * )
     * @Rest\View(serializerGroups={"places"})
     * @Rest\Get("/places")
     */
    public function autocompleteCityAction(Request $request)
    {
        $term = $request->get('term');
        $region = $request->get('region');

        $data = $this->get('application_plateforme.places')->getPlaces($term,strtolower($region));

        $arr = array();
        foreach(json_decode($data)->predictions as $item){
            $arr[] = array(
                'name' => $item->description,
                'city' => $item->structured_formatting->main_text,
            );
        }
        return $arr;
    }
}