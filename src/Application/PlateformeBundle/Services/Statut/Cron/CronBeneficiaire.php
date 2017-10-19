<?php

namespace Application\PlateformeBundle\Services\Statut\Cron;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CronBeneficiaire
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function beneficiairePreviousWeekNoContact()
    {
        $this->mailer = $this->container->get('application_plateforme.mail');
        $this->em = $this->container->get('doctrine.orm.entity_manager');

        $Jour = array("Sunday" => "Dimanche", "Monday" => "Lundi", "Tuesday" => "Mardi" , "Wednesday" => "Mercredi" , "Thursday" => "Jeudi" , "Friday" => "Vendredi" ,"Saturday" => "Samedi");
        $Mois = array("January" => "Janvier", "February" => "Février", "March" => "Mars", "April" => "Avril", "May" => "Mai", "June" => "Juin", "July" => "Juillet", "August" => "Août", "September" => "Septembre", "October" => "Octobre", "November" => "Novembre", "December" => "Décembre");

        $beneficiareRepo = $this->em->getRepository("ApplicationPlateformeBundle:Beneficiaire");

        // Bénéficiaires ayant des details statut autre que [Arguments]
        $beneficiairesWithDetailStatusMore = $beneficiareRepo->getBeneficiaireWithDetailStatut(array("En attente Contact Tél","Tentative 1"));

        $idBeneficiaireExclu = array();
        foreach($beneficiairesWithDetailStatusMore as $beneficiaire)
        {
            $idBeneficiaireExclu[] = $beneficiaire->getId();
        }

        $dateDebut = (new \DateTime())->modify('-7 days');
        $dateFin = (new \DateTime())->modify('-1 days');
        $dateDebutSql = (new \DateTime())->modify('-7 days')->format("Y-m-d");
        $dateFinSql = (new \DateTime())->modify('-1 days')->format("Y-m-d");
        $dateDebutFr = $Jour[$dateDebut->format('l')]." ".$dateDebut->format('j')." ".$Mois[$dateDebut->format('F')];
        $dateFinFr = $Jour[$dateFin->format('l')]." ".$dateFin->format('j')." ".$Mois[$dateFin->format('F')];

        $beneficiairesAddLastWeek = $beneficiareRepo->getBeneficiaireWithDate($dateDebutSql, $dateFinSql);
        // Beneficiaire autres que ceux de la liste d'exclusion
        $beneficiaires = $beneficiareRepo->getBeneficiaireWithIdsAndDate($idBeneficiaireExclu, $dateDebutSql, $dateFinSql);

        $path = $this->container->getParameter('export_csv_directory');
        $handle = fopen($path.'/export_benef_pevious_week_no_contact.csv', 'w+');
        // Nom des colonnes du CSV
        fputcsv($handle, array('Conso',
            'Date conf mer',
            utf8_decode('Détail statut'),
            'Ville mer',
            utf8_decode('Téléphone'),
            utf8_decode('Dernière news')
        ),';');

        $statutRepo = $this->em->getRepository("ApplicationPlateformeBundle:News");
        $nouvelleRepo = $this->em->getRepository("ApplicationPlateformeBundle:Nouvelle");
        $totalBeneficiaireNoContact = count($beneficiaires);
        $totalBeneficiaireEnAttente = 0;
        $totalBeneficiaireTentative = 0;
        foreach($beneficiaires as $beneficiaire){
            $lastStatut = $statutRepo->findOneBy(
                array('beneficiaire' => $beneficiaire),
                array('id' => "DESC")
            );
            $lastNews = $nouvelleRepo->findOneBy(
                array('beneficiaire' => $beneficiaire),
                array('id' => "DESC")
            );
            if(count($lastNews) > 0)
                $news = utf8_decode($lastNews->getMessage());
            else
                $news = "";

            fputcsv($handle, array(utf8_decode($beneficiaire->getCiviliteConso()." ".$beneficiaire->getNomConso()."  ".$beneficiaire->getPrenomConso()),
                $beneficiaire->getDateConfMer()->format('d/m/Y'),
                utf8_decode($lastStatut->getDetailStatut()->getDetail()),
                utf8_decode($beneficiaire->getVilleMer()->getNom()),
                "\t".$beneficiaire->getTelConso(),
                $news,
            ),';');


            if($lastStatut->getDetailStatut()->getDetail() == "En attente Contact Tél") {
                $totalBeneficiaireEnAttente++;
            }
            elseif($lastStatut->getDetailStatut()->getDetail() == "Tentative 1"){
                $totalBeneficiaireTentative++;
            }

        }
        fclose($handle);

        $message = "
                Bonjour, <br><br>                
                Veuillez trouver ci-joint la liste des ".$totalBeneficiaireNoContact." bénéficiaires (sur un total de ".count($beneficiairesAddLastWeek).") qui n'ont pu être joints la semaine du ".$dateDebutFr." au ".$dateFinFr.", dont ".$totalBeneficiaireEnAttente."
                en statut 'En attente Contact Tél' et ".$totalBeneficiaireTentative." en statut 'Tentative 1'.
                ";
        $this->mailer->listeBeneficiairesPreviousWeekNoContact($message, \Swift_Attachment::fromPath($path.'/export_benef_pevious_week_no_contact.csv') );


        /*
        // Création d'un csv téléchargeable directement dans le navigateur web
        $response = new StreamedResponse();

        $response->setCallback(function() use(){
            // construire le csv
        });
        */
    }
}
?>