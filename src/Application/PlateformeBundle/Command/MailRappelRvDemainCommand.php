<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailRappelRvDemainCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail_rappel_rv_demain')
            ->setDescription("Mail de rappel au beneficiaire + consltant, qu'il a un rdv demain")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // recuperation du service 
        $service = $this->getContainer()->get('application_plateforme.statut.cron.rv')->alerteRappelRdvAgenda();
		$output->writeln($service);
    }
}