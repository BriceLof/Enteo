<?php

namespace Application\UsersBundle\Twig;

use Application\UsersBundle\Entity\StatutConsultant;
use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Services\Statut;

class UsersExtension extends \Twig_Extension
{
    /**
     * var Statut
     */
    private $statut;

    public function __construct(Statut $statut)
    {
        $this->statut = $statut;
    }

    public function afficherStatut($int){
        $tab = array_flip($this->statut->getTabStatut());
        return $tab[$int];
    }

    public function afficherDetail($int){
        $tab = array_flip($this->statut->getTabDetail());
        return $tab[$int];
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('afficherStatut', array($this, 'afficherStatut')),
            new \Twig_SimpleFunction('afficherDetail', array($this, 'afficherDetail')),
        );
    }

    public function getName(){
        return 'application_users_statut';
    }
}