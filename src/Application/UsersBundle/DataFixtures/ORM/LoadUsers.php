<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace Application\UsersBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Entity\TypeUser;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadUsers extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
 
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        /* insertion des types utilisateurs */
        $type = array("administrateurs", "gestionnaires", "consultants", "commerciaux", "bénéficiaires");
        for($i = 0 ;  $i < count($type); $i++) {
          // On crée la mise en relation 
          $typeUser = new TypeUser();
          $typeUser->setName($type[$i]);
          // On la persiste
          $manager->persist($typeUser);
        }
        $manager->flush();
        
        // dans utilisateur on a un une relation manyToOne avec typeUser, donc pour l'ajout d'un user il nous faut un objet type_utilisateur
        $users = $manager->getRepository('ApplicationUsersBundle:TypeUser')->findOneBy(array('name' => "consultants"));
        
        /* insertion des utilisateurs */
        $prenomTab = array("Brice", "Aurélie", "Solène", "Fidy", "Mamadou", "Franck", "Philippe", "Nassera");
        $nomTab = array("Lof", "Dagonet", "Balde", "Jouhari", "Azoulay", "Rouzaud", "Payet", "Ranaivoson"); 

        $userManager = $this->container->get('fos_user.user_manager');
        
        for($i = 0 ; $i <8; $i++) {
          // On crée la mise en relation 
          $user = $userManager->createUser();
          $user->setEnabled(1);
          $user->setTypeUser($users);
          $user->setUsername($prenomTab[$i]."_".$i);
          $user->setEmail($prenomTab[$i]."@iciformation.fr");
          $user->setPlainPassword("brice".$i);
          $user->setNom($nomTab[$i]);
          $user->setPrenom($prenomTab[$i]);
          $user->setVille("Paris");
          $user->setDistanciel(1);
          // On la persiste
          $userManager->updateUser($user);
        }
            
    } 
  
    /**
    * Sets the container.
    * @param ContainerInterface|null $container A ContainerInterface instance or null
    */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
    * Get the order of this fixture
    * @return integer
    */
    public function getOrder()
    {
        return 2;
    }
}
