<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailAttenteAccordFinancementCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail_attente_accord_financement')
            ->setDescription("alerte automatique à destination des gestionnaires admn (et copie les administrateurs) 3 semaines après passage au statut adm = Financement / Attente Accord si le statut n'a pas bouger")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // recuperation du service 
        $service = $this->getContainer()->get('application_plateforme.statut.cron.rv')->rappelFinancement();
		$output->writeln($service);
    }
}