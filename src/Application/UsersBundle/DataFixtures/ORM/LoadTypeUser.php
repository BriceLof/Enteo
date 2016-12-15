<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace Application\UsersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\UsersBundle\Entity\TypeUser;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadTypeUser implements FixtureInterface 
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        /*
        $type = array("administrateurs", "gestionnaires", "consultants", "commerciaux", "bénéficiaire");
        for($i = 0 ;  $i < count($type); $i++) {
          // On crée la mise en relation 
          $typeUser = new TypeUser();
          $typeUser->setName($type[$i]);
          // On la persiste
          $manager->persist($typeUser);
        }

        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
        
        */
    } 
  
}