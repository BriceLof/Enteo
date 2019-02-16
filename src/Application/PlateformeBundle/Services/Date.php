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

    // Transforme date FR (d/m/Y) en EN (Y-m-d)
    public function transformDateFrtoEn($date)
    {
        if(!is_null($date) && $date != '')
        {
            // si la date est deja dans le format EN
            if(strstr($date, '-')){
                return $date;
            }
            $dateExplode = explode('/', $date);
            return $dateExplode[2].'-'.$dateExplode[1].'-'.$dateExplode[0];
        }else{
            return null;
        }
    }

}