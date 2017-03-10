<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailSuiteRvCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mailSuiteRv')
            ->setDescription("Suite R1 à faire et R2 à faire (à l'heure de démarrage du Rv +1h) : envoyer email au Consultant en charge du RV")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // recuperation du service 
        $service = $this->getContainer()->get('application_plateforme.statut.cron.rv')->alerteSuiteRvAgenda();
		$output->writeln($service);
    }
}