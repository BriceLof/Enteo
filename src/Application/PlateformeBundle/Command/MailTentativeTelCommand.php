<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\PlateformeBundle\Entity\News;
use Doctrine\ORM\Mapping as ORM;

class MailTentativeTelCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mailTentativeTel')
            ->setDescription("alerte automatique à destination des beneficiaires Si Statut commercial = Téléphone
                                Et que Détail Statut = Tentative 1 depuis >= 5 jours
                                Ou que Détail Statut = Tentative 2 ou 3 depuis >= 2 jours")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();
        $abandonDetailStatut = $em->getRepository('ApplicationPlateformeBundle:DetailStatut')->find(5);

        foreach ($beneficiaires as $beneficiaire) {
            $news = $beneficiaire->getNews();
            $lastNews = $news[count($news) - 1];
            if ($lastNews->getDetailStatut()->getId() == 2) {
                if ($lastNews->getDateHeure() <= (new \DateTime('now'))->modify("-5 day")->setTime(0, 0, 0)) {
                    //envoyer mail 1
                    $this->getContainer()->get('application_plateforme.statut.mail.mail_for_statut')->EmailSuiteNoContactBeneficiaire($beneficiaire);
                }
            }
            if ($lastNews->getDetailStatut()->getId() == 3 || $lastNews->getDetailStatut()->getId() == 4) {
                if ($lastNews->getDateHeure() <= (new \DateTime('now'))->modify("-2 day")->setTime(0, 0, 0)) {
                    $statut = new News();
                    $statut->setStatut($abandonDetailStatut->getStatut());
                    $statut->setDetailStatut($abandonDetailStatut);
                    $beneficiaire->addNews($statut);
                    $em->persist($beneficiaire);
                    $em->flush();
                }
            }
        }
    }
}