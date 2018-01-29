<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailRvCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail')
            ->setDescription("
                      1) CRON chaque jour à 21h00 et si statut bénéficiaire passe de R1 ou R2 à faire à R1 ou R2 positif
                      2) Si à h+24 de l'heure du Rdv la fiche bénéficiaire N'EST PAS MAJ => +++ Relance email (1b) au Consultant 
                      3) Si à h+48 de l'heure du Rdv la fiche bénéficiaire N'EST PAS MAJ => Relance email (1c) au Consultant ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $consultants = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        foreach ($consultants as $consultant) {
            $tab2 = array();
            $tab3 = array();
            $tab4 = array();

            $beneficiaires = $consultant->getBeneficiaire();
            foreach ($beneficiaires as $beneficiaire) {
                $news = $beneficiaire->getNews();
                //CRON chaque jour à 21h00 et si statut bénéficiaire passe de R1 ou R2 à faire à R1 ou R2 positif +++ => Envoyer email
                if (!is_null($news[count($news) - 2])) {
                    $now = new \DateTime();
                    $lastNews = $news[count($news) - 1];
                    $nextToNews = $news[count($news) - 2];
                    $slugLastNewsStatus = $lastNews->getStatut()->getSlug();
                    $slugNextToNewsStatus = $nextToNews->getStatut()->getSlug();
                    //si le statut est : RV1 réalisé ou RV2 réalisé
//                    if ($slugLastNewsStatus == "rv1-realise" || $slugLastNewsStatus == "rv2-realise") {
                    if ($lastNews->getDetailStatut()->getDetail() == "RV1 Positif" || $lastNews->getDetailStatut()->getDetail() == "RV2 Positif") {
                        //si l'avant dernier statut est : RV1 à faire ou RV2 à faire
                        if ($slugNextToNewsStatus == "rv1-a-faire" || $slugNextToNewsStatus == "rv2-a-faire") {
                            $dateLastNews = $lastNews->getDateHeure();
                            //$dateLastNews->modify('-1 day');
                            //si le dernier news date de mois de 24h
                            if ($dateLastNews >= $now->modify('-1 day')) {
                                $now->modify('+1 day');
                                if ($dateLastNews < $now) {
                                    $lastRdv = $em->getRepository('ApplicationPlateformeBundle:Historique')->getLastRv1orRv2($beneficiaire);
                                    $this->getContainer()->get('application_plateforme.statut.cron.rv')->mailRvRealise($beneficiaire, $lastRdv[0]);
                                }
                            } else {
                                $now->modify('+1 day');
                            }
                        }
                    }
                }

                $historiques = $beneficiaire->getHistorique();

//              boucler sur toutes les historiques
                foreach ($historiques as $historique) {

                    //$rv = substr($historique->getSummary(), 0, 2);
                    $rv = $historique->getSummary();
                    //si l'historique n'est pas archivé ou annulé ou modifié
                    if ($historique->getEventarchive() == 'off' && $historique->getCanceled() == 0) {
                        //si la date de cet historique date de mois de 24h au moment du lancement de la cron journalier
                        if ($historique->getDateDebut() > (new \DateTime('now'))->modify('-1 day') && $historique->getDateDebut() < (new \DateTime('now'))) {

                            if (($rv == "RV1" || $rv == "RV2" )&& $beneficiaire->getUpdatedAt() < (new \DateTime('now'))->modify('-1 day')) {
                                if (!in_array($beneficiaire, $tab2)){
                                    $tab2[] = $beneficiaire;
                                }
                            }
                        }

                        //si la date de cet historique date de mois de 48h au moment du lancement de la cron journalier
                        if ($historique->getDateDebut() > (new \DateTime('now'))->modify('-2 day') && $historique->getDateDebut() < (new \DateTime('now'))->modify('-1 day')) {
                            if (($rv == "RV1" || $rv == "RV2" ) && $beneficiaire->getUpdatedAt() < (new \DateTime('now'))->modify('-2 day')) {
                                if (!in_array($beneficiaire, $tab3)) {
                                    $tab3[] = $beneficiaire;
                                }
                            }
                        }
                    }
                }

            }

            if ($tab2 != null) {
                $this->getContainer()->get('application_plateforme.statut.cron.rv')->firstMailRvFicheNonMaj($consultant, $tab2);
            }

            if ($tab3 != null) {
                $this->getContainer()->get('application_plateforme.statut.cron.rv')->secondMailRvFicheNonMaj($consultant, $tab3);
            }
        }
    }
}