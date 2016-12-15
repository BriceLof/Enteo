<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace Application\PlateformeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\PlateformeBundle\Entity\Mer;

class LoadMer implements FixtureInterface
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $mers = array(
      0 => array(28, 1850, 'Mr', 'Lof', 'Brice', 'Paris', 'Entre 14h et 18h', new \DateTime, new \DateTime, '0692604477', 'support@iciformation.fr', 'Assitanat et secrétariat', 'Bac à Bac +2', 'ADW BDC' ),
      1 => array(85, 1870, 'Mme', 'Payer', 'Marie', 'Lyon', 'Entre 14h et 18h', new \DateTime, new \DateTime, '0692604477', 'support@iciformation.fr', 'Assitanat et secrétariat', 'Bac à Bac +2', 'ADW BDC' ),
      2 => array(94, 1900, 'Mr', 'Fidy', 'Ranaivoson', 'Paris', 'Entre 14h et 18h', new \DateTime, new \DateTime, '0692604477', 'support@iciformation.fr', 'Assitanat et secrétariat', 'Bac à Bac +2', 'ADW BDC' ),
      );

    foreach ($mers as $mer) {
      // On crée la mise en relation 
      $mise_en_relation = new Mer();
      $mise_en_relation->setMerId($mer[0]);
      $mise_en_relation->setClientId($mer[1]);
      $mise_en_relation->setCiviliteConso($mer[2]);
      $mise_en_relation->setNomConso($mer[3]);
      $mise_en_relation->setPrenomConso($mer[4]);
      $mise_en_relation->setVilleConso($mer[5]);
      $mise_en_relation->setHeureRappel($mer[6]);
      $mise_en_relation->setDateHeureMer($mer[7]);
      $mise_en_relation->setDateConfMer($mer[8]);
      $mise_en_relation->setTelConso($mer[9]);
      $mise_en_relation->setEmailConso($mer[10]);
      $mise_en_relation->setDomaineVaeConso($mer[11]);
      $mise_en_relation->setDiplomeViseConso($mer[12]);
      $mise_en_relation->setOrigineMer($mer[13]);
      // On la persiste
      $manager->persist($mise_en_relation);
    }

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();
  }
}