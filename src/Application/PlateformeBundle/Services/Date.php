<?php

namespace Application\PlateformeBundle\Services;

use Doctrine\ORM\EntityManager;

class Date
{
    private $em;
    
    public function __construct(EntityManager $em)
    {
        $this->em = $em;  
    }
    
    public function dateFr($date){
        $Jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi","Samedi");
        $Mois = array("","Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        $datefr = $Jour[date_format($date,"w")]." ".date_format($date, "d")." ".$Mois[date_format($date,"n")]." ".date_format($date, "Y");
        return $datefr;
    }

    public function dateCourtFr($date){
        $Mois = array("","Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        $datefr = date_format($date, "d")." ".$Mois[date_format($date,"n")]." ".date_format($date, "Y");
        return $datefr;
    }

}