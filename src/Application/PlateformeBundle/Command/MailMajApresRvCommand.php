<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailMajApresRvCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail_maj_apres_rv')
            ->setDescription("Bénéficiaires dont le statut = R1 à réaliser ou R2 à réaliser mais dont le rdv de R1 ou R2 est passé depuis > ou = 4 jours")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $tab = array();
        $tab2 = array();


        $em = $this->getContainer()->get('doctrine')->getManager();
        $consultants = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        foreach ($consultants as $consultant) {

            $beneficiaires = $consultant->getBeneficiaire();
            foreach ($beneficiaires as $beneficiaire) {
                $news = $beneficiaire->getNews();
                if (!is_null($news[count($news) - 2])) {
                    $lastNews = $news[count($news) - 1];
                    $nextToNews = $news[count($news) - 2];
                    //CRON chaque jour à 21h00 et si statut bénéficiaire passe de R1 ou R2 à faire à R1 ou R2 positif +++ => Envoyer email

                    $historiques = $beneficiaire->getHistorique();
                    foreach ($historiques as $historique) {

                        $rv = $historique->getSummary();
                        //si l'historique n'est pas archivé ou annulé ou modifié
                        if ($historique->getEventarchive() == 'off' && $historique->getCanceled() == 0) {
                            //si la date de cet historique date de plus de 4jours au moment du lancement de la cron hebdomadaire
                            if ($historique->getDateDebut() <= (new \DateTime('now'))->modify('-4 day')) {
                                if (($rv == "RV1" || $rv == "RV2") && ($lastNews->getStatut()->getId() == 3 || ($lastNews->getStatut()->getId() == 5 && $nextToNews->getStatut()->getId() != 3))) {
                                    if (!in_array($beneficiaire, $tab)) {
                                        $output->writeln($beneficiaire->getNomConso());
                                        $tab[] = $beneficiaire;
                                        $tab2[] = $historique;
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
        usort($tab2, function ($a, $b) {
            if ($a->getDateDebut() == $b->getDateDebut()) {
                return 0;
            }
            return $a->getDateDebut() > $b->getDateDebut() ? -1 : 1;
        });
        $attachement1 = new \Swift_Attachment(
            $this->getContainer()->get('application_plateforme.csv')->getCsvForFicheNonMaj($tab2),
            'dossiers_non_maj_' . (new \DateTime('now'))->format('d_m_y') . '.csv',
            'application/csv'
        );
        $this->getContainer()->get('application_plateforme.statut.cron.rv')->lastMailRvFicheNonMaj($tab2, $attachement1);
    }
}