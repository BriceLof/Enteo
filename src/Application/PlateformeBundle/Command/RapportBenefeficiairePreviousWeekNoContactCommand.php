<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class RapportBenefeficiairePreviousWeekNoContactCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail_rapport_beneficiaire_previous_week_no_contact')
            ->setDescription("Un rapport csv des bénéficiaires ajoutés la semaine passé et qui sont encore en detail statut 'en attente contact tel' ou 'tentative 1' ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // recuperation du service
        $service = $this->getContainer()->get('application_plateforme.statut.cron.cron_beneficiaire')->beneficiairePreviousWeekNoContact();
        $output->writeln($service);
    }
}