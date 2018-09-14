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
                      2) Si au soir a 21h de l'heure du Rdv la fiche bénéficiaire N'EST PAS MAJ => +++ Relance email (1b) au Consultant 
                      3) Si au lendemain soir de l'heure du Rdv la fiche bénéficiaire N'EST PAS MAJ => Relance email (1c) au Consultant ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $consultants = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        foreach ($consultants as $consultant) {
            $tab = array();
            $tab2 = array();
            $tab3 = array();

            $beneficiaires = $consultant->getBeneficiaire();
            foreach ($beneficiaires as $beneficiaire) {
                $news = $beneficiaire->getNews();
                if (!is_null($news[count($news) - 2])) {
                    $lastNews = $news[count($news) - 1];
                    $nextToNews = $news[count($news) - 2];
                    //CRON chaque jour à 21h00 et si statut bénéficiaire passe de R1 ou R2 à faire à R1 ou R2 positif +++ => Envoyer email

                    if ($lastNews->getDetailStatut()->getId() == 7 || $lastNews->getDetailStatut()->getId() == 14) {
                        //si l'avant dernier statut est : RV1 à faire ou RV2 à faire
                        $dateLastNews = $lastNews->getDateHeure();

                        //si le dernier news date de mois de 24h
                        if ($dateLastNews >= (new \DateTime('now'))->modify('-1 day')) {
                            $lastRdv = $em->getRepository('ApplicationPlateformeBundle:Historique')->getLastRv1orRv2($beneficiaire);
                            $output->writeln($beneficiaire->getNomConso());
                            $this->getContainer()->get('application_plateforme.statut.cron.rv')->mailRvRealise($beneficiaire, $lastRdv[0]);
                        }
                    }


                    $historiques = $beneficiaire->getHistorique();

                    //              boucler sur toutes les historiques
                    foreach ($historiques as $historique) {

                        $rv = $historique->getSummary();
                        //si l'historique n'est pas archivé ou annulé ou modifié
                        if ($historique->getEventarchive() == 'off' && $historique->getCanceled() == 0) {

                            //si la date de cet historique = date du jour au moment du lancement de la cron journalier
                            if ($historique->getDateDebut()->format('d-m-Y') == (new \DateTime('now'))->format('d-m-Y')) {
                                if (($rv == "RV1" || $rv == "RV2") && ($lastNews->getStatut()->getId() == 3 || ($lastNews->getStatut()->getId() == 5 && $nextToNews->getStatut()->getId() != 3))) {

                                    if (!in_array($beneficiaire, $tab)) {
                                        $tab[] = $beneficiaire;
                                    }
                                }
                            }

                            //si la date de cet historique date de mois de 24h au moment du lancement de la cron journalier
                            if ($historique->getDateDebut()->format('d-m-Y') == (new \DateTime('now'))->modify('-1 day')->format('d-m-Y')) {
                                if (($rv == "RV1" || $rv == "RV2") && ($lastNews->getStatut()->getId() == 3 || ($lastNews->getStatut()->getId() == 5 && $nextToNews->getStatut()->getId() != 3))) {

                                    if (!in_array($beneficiaire, $tab2)) {
                                        $tab2[] = $beneficiaire;
                                    }
                                }
                            }

                            //si la date de cet historique date de mois de 48h au moment du lancement de la cron journalier
                            if ($historique->getDateDebut()->format('d-m-Y') == (new \DateTime('now'))->modify('-2 day')->format('d-m-Y')) {
                                if (($rv == "RV1" || $rv == "RV2") && ($lastNews->getStatut()->getId() == 3 || ($lastNews->getStatut()->getId() == 5 && $nextToNews->getStatut()->getId() != 3))) {
                                    if (!in_array($beneficiaire, $tab3)) {
                                        $tab3[] = $beneficiaire;
                                    }
                                }
                            }
                        }
                    }
                }

            }

            if (empty($tab)) {
                $this->getContainer()->get('application_plateforme.statut.cron.rv')->mailRvFicheNonMaj($consultant, $tab);
            }

            if (empty($tab2)) {
                $this->getContainer()->get('application_plateforme.statut.cron.rv')->firstMailRvFicheNonMaj($consultant, $tab2);
            }

            if (empty($tab3)) {
                $this->getContainer()->get('application_plateforme.statut.cron.rv')->secondMailRvFicheNonMaj($consultant, $tab3);
            }
        }
    }
}