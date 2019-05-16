<?php

namespace Application\PlateformeBundle\Services;

class PlacesApi
{
    public function getPlaces($term, $region = null){
        if (!is_null($region))
            return  file_get_contents("https://maps.googleapis.com/maps/api/place/autocomplete/json?input=".$term."&region=".$region."&types=geocode&key=AIzaSyB919NmSoOAO6kmrPgpsQKWPvsTH1oneTU");
        return file_get_contents("https://maps.googleapis.com/maps/api/place/autocomplete/json?input=".$term."&types=geocode&key=AIzaSyB919NmSoOAO6kmrPgpsQKWPvsTH1oneTU");
    }
}