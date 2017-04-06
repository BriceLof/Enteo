<?php
namespace Application\PlateformeBundle\Services\Statut;

class AddStatutBdd
{
    private $em = null;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    public function addStatutAndDetail()
    {
        /* insertion statut et detail statut */
        $statuts = array(
            "MenR" => array("En attente Contact Tél"),
            "Téléphone" => array("Tentative 1", "Tentative 2", "Tentative 3", "Email suite No Contact"),
            "RV1 à faire" => array(""),
            "RV1 réalisé" => array("RV1 Positif", "RV1 Négatif", "Indécis", "RV1 à reporter", "No show RV1", "RV2 à faire"),
            "RV2 à faire" => array(""),
            "RV2 réalisé" => array("RV2 Positif", "RV2 Négatif", "Indécis", "RV2 à reporter", "No show RV2"),
            "Dossier en cours" => array(""),
            "Financement" => array("Attente accord", "OK accord financeur", "OK financement partiel", "Refus financement"),
            "Recevabilité" => array("Accord recevabilité", "Refus recevabilité"),
            "Facturation" => array("Facture acompte", "Facture solde", "Facture totale", "Avoir"),
            "Reporté" => array("Pas le moment", "Pas de Consultants"),
            "Abandon" => array("Faux N°", "Pas de financement", "Pas éligible", "Incompatibilité", "Renonce VAE", "Concurrent", "Distance", "Disponibilté", "No Show", "Doublon"),
            "Terminé" => array("Validation totale", "Validation partielle", "Rejeté"),
        );
        
        foreach($statuts as $key => $value)
        {
            $statut = new \Application\PlateformeBundle\Entity\Statut();
            $statut->setNom($key);
            $this->em->persist($statut);
            foreach($value as $detail)
            {
                $detailStatut = new \Application\PlateformeBundle\Entity\DetailStatut();
                $detailStatut->setDetail($detail);
                $detailStatut->setStatut($statut);
                $this->em->persist($detailStatut);  
            }
            $this->em->flush();
        }
        
        // code à placer dans le controlleur pour appeler le service 
        // $this->container->get('application_plateforme.statut.add_statut')->addStatutAndDetail();
    }
    
    
    
    
    
}