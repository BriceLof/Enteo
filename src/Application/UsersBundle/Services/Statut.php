<?php

namespace Application\UsersBundle\Services;

class Statut {


    function getTabDetail(){
        $tab = array(
            'Accompagnement jusqu\'en Master / Master 2' => 1,
            'Aptitude à la formation de nouveaux consultants' => 2,
            'Suivi de formation Entheor' => 3,
        );

        return $tab;
    }

    function getTabStatut(){
        $tab = array(
            'Nouveau Consultant' => 1,
            'Consultant Confirmé' => 2,
            'Consultant Expert' => 3
        );

        return $tab;
    }
}