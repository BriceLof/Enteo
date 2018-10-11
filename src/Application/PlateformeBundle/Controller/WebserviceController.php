<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Entity\Mer;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\Ville;
use Application\PlateformeBundle\Form\NewsType;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class WebserviceController extends Controller
{   
    // Récupération des infos de la mer envoyé par le webservice depuis IF 
    public function indexAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();

        // Mise en relation
        $mer = new Mer;
        $mer->setMerId($request->query->get('mer_id'));
        $mer->setClientId($request->query->get('client_id'));
        $mer->setStatut($request->query->get('statut'));
        $mer->setNomConso($request->query->get('nom'));
        $mer->setCiviliteConso($request->query->get('civilite'));
        $mer->setPrenomConso($request->query->get('prenom'));
        $mer->setVilleConso($request->query->get('ville'));
        $mer->setCodePostal($request->query->get('code_postal'));
        $mer->setHeureRappel($request->query->get('heure_rappel'));
        $mer->setDateHeureMer(new \DateTime($request->query->get('date_heure_mer')));
        $mer->setDateConfMer(new \DateTime($request->query->get('date_conf_mer')));
        $mer->setTelConso($request->query->get('tel'));
        $mer->setEmailConso($request->query->get('mail'));
        $mer->setDomaineVaeConso($request->query->get('domaine_vae'));
        $mer->setDiplomeViseConso($request->query->get('diplome_vise'));
        $mer->setOrigineMer($request->query->get('origine_mer'));
        $em->persist($mer);
        
        // Ville du bénéficiaire par défaut sur la ville du centre entheor 
        $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array("nom" => $mer->getVilleConso()));
        
        /**
        if(is_null($ville))
        {
            $ville = new Ville;
            $ville->setNom($mer->getVilleConso());
            $ville->setCp($mer->getCodePostal());
            $em->persist($ville);
        }
        */
       
        // Benéficiaire 
        $beneficiaire = new Beneficiaire;
        $beneficiaire->setVille($ville);
        $beneficiaire->setVilleMer($ville);
        $beneficiaire->setStatut($mer->getStatut());
        $beneficiaire->setClientId($mer->getClientId());
        $beneficiaire->setCiviliteConso($mer->getCiviliteConso());
        $beneficiaire->setNomConso($mer->getNomConso());
        $beneficiaire->setPrenomConso($mer->getPrenomConso());
        $beneficiaire->setHeureRappel($mer->getHeureRappel());
        $beneficiaire->setDateHeureMer($mer->getDateHeureMer());
        $beneficiaire->setDateConfMer($mer->getDateConfMer());
        $beneficiaire->setTelConso($mer->getTelConso());
        $beneficiaire->setIndicatifTel('33');
        $beneficiaire->setEmailConso($mer->getEmailConso());
        $beneficiaire->setDomaineVae($mer->getDomaineVaeConso());
        $beneficiaire->setDiplomeVise($mer->getDiplomeViseConso());
        $beneficiaire->setOrigineMer($mer->getOrigineMer());
        $beneficiaire->setEmailConso($mer->getEmailConso());
        $em->persist($beneficiaire);

        // News par défaut
        $news = new News;
        $news->setBeneficiaire($beneficiaire);
        
        $repositoryDetailStatut = $em->getRepository("ApplicationPlateformeBundle:DetailStatut");
        $detailStatut = $repositoryDetailStatut->find(1);
        
        $news->setStatut($detailStatut->getStatut());
        $news->setDetailStatut($detailStatut);
        $em->persist($news);
		
        $historique = new Historique();
        $historique->setSummary("");
        $historique->setTypeRdv("");
        $historique->setBeneficiaire($beneficiaire);
        $historique->setDescription($beneficiaire->getOrigineMer());
        $historique->setEventId("0");
        $em->persist($historique);
        /**
        var_dump($mer);
        var_dump($ville);
        var_dump($beneficiaire);
        */
        
        
        $em->flush();
        
        return new Response("webservice a bien fonctionné");
    }
    
    
}

