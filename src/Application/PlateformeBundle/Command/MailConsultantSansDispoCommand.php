<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailConsultantSansDispoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail_consultant_sans_dispo')
            ->setDescription("Récap des consultants n'ayant pas mis de disponibilité. Envoyé tout les lundi à 7h à la liste des consultants concernés.")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // recuperation du service 
        $service = $this->getContainer()->get('application_plateforme.disponibilite.cron.dispo')->recapConsultantSansDispo();
		$output->writeln($service);
    }
}