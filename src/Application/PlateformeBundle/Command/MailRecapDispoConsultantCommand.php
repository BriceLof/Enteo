<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailRecapDispoConsultantCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail_recap_dispo_consultant')
            ->setDescription("Récap des disponibilités par consultant. Cron envoyé à 7h tout les jours pour les commerciaux")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // recuperation du service 
        $service = $this->getContainer()->get('application_plateforme.disponibilite.cron.dispo')->recapDispo();
		$output->writeln($service);
    }
}