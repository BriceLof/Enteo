<?php

namespace Application\UsersBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Application\UsersBundle\Entity\Users;
/**
 * Description of AppExtension
 *
 * @author brice_000
 */
class AppExtension extends \Twig_Extension{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('nbDossierEnCours', array($this, 'nombreDossierEnCoursFilter')),
            new \Twig_SimpleFilter('nbDossierRealises', array($this, 'nombreDossierRealisesFilter')),
        );
    }

    protected $doctrine;
    // Retrieve doctrine from the constructor
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function nombreDossierEnCoursFilter(Users $user)
    {
        $em = $this->doctrine->getManager();
        // Récuperation des beneficiaires du consultant
        $beneficiaires = $em->getRepository("ApplicationPlateformeBundle:Beneficiaire")->findByConsultant($user);
        
        $nbDossier = 0 ;
        //$statut = new Statut
        if(count($beneficiaires) > 0){
            foreach($beneficiaires as $beneficiaire)
            {
                // Récuperation des news du bénéficiaire
                $news = $em->getRepository("ApplicationPlateformeBundle:News")->findBy(
                    array('beneficiaire'  => $beneficiaire)
                );
                if(count($news) > 0){
                    $done = false;
                    foreach($news as $newsBeneficiaire)
                    {
                        // nombre dossier en cours = nombre de dossier total en enlevant les dossiers en abandon et terminé
                        if($newsBeneficiaire->getStatut()->getSlug() == 'termine' || $newsBeneficiaire->getStatut()->getSlug() == 'abandon') $done = true;
                    }
                }
                
                if($done == false)
                    $nbDossier++; 
            }
        }
        return $nbDossier;
    }
    
    public function nombreDossierRealisesFilter(Users $user)
    {
        $em = $this->doctrine->getManager();
        // Récuperation des beneficiaires du consultant
        $beneficiaires = $em->getRepository("ApplicationPlateformeBundle:Beneficiaire")->findByConsultant($user);
        
        $nbDossier = 0 ;
        //$statut = new Statut
        if(count($beneficiaires) > 0){
            foreach($beneficiaires as $beneficiaire)
            {
                // Récuperation des news du bénéficiaire
                $news = $em->getRepository("ApplicationPlateformeBundle:News")->findBy(
                    array('beneficiaire'  => $beneficiaire)
                );
                if(count($news) > 0){
                    $done = false;
                    foreach($news as $newsBeneficiaire)
                    {
                        // nombre dossier en cours = nombre de dossier total en enlevant les dossiers en abandon et terminé
                        if($newsBeneficiaire->getStatut()->getSlug() == 'termine' || $newsBeneficiaire->getStatut()->getSlug() == 'abandon') $done = true;
                    }
                }
                
                if($done == true)
                    $nbDossier++; 
            }
        }
        return $nbDossier;
    }
}
