<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailRvAReporterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mailRvAReporter')
            ->setDescription(" CRON chaque dimanche à 22h00 et si statut bénéficiaire passe RV1 à reporter à RV2 à reporter ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $consultants = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        foreach ($consultants as $consultant) {
            $tab = array();

            $beneficiaires = $consultant->getBeneficiaire();
            foreach ($beneficiaires as $beneficiaire) {
                $news = $beneficiaire->getNews();
                if (!is_null($news[count($news) - 2])) {
                    $lastNews = $news[count($news) - 1];
                    //si le statut est : RV1 à reporter ou RV2 à reporter
                    if ($lastNews->getDateHeure()->setTime(0,0,0) <= (new \DateTime('now'))->setTime(0,0,0)) {
                        if (($lastNews->getDateHeure()->setTime(0,0,0) > (new \DateTime('now'))->modify('-7 day')->setTime(0,0,0))){
                            if ($lastNews->getDetailStatut()->getId() == 10 || $lastNews->getDetailStatut()->getId() == 17 || $lastNews->getDetailStatut()->getId() == 11 || $lastNews->getDetailStatut()->getId() == 18 ) {
                                $tab[] = $lastNews;
                            }
                        }
                    }
                }
            }

            if (!empty($tab)){
                usort($tab, function($a, $b) {
                    if ($a->getDate() == $b->getDate()) {
                        return 0;
                    }
                    return $a->getDate() > $b->getDate() ? -1 : 1;
                });
                $attachement = new \Swift_Attachment(
                    $this->getContainer()->get('application_plateforme.csv')->getCvsForMailNews($tab),
                    'dossiers_rv_a_reporter_'.(new \DateTime('now'))->format('d_m_y').'.csv',
                    'application/csv'
                );
                $this->getContainer()->get('application_plateforme.statut.cron.rv')->mailRvAReporter($tab,  $attachement);
            }
        }
    }
}