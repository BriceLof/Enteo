<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class FactureStatutSendPartielCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:factureStatut')
            ->setDescription("Envoi la liste des facture toujours en statut sent ou partiel depuis plus de 15 jours")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$csv2 = $this->getContainer()->get('application_plateforme.csv')->getCvsForMailSuivi($tab2, "Dossier en cours - En attente de traitement Entheor");
        //$attachement2 = array("name" => 'dossiers_attente_traitement_' . (new \DateTime('now'))->format('d_m_y') . '.csv', "file" => $csv2);
        $this->getContainer()->get('application_plateforme.facture.cron.status')->listBeneficiareWithInvoice();
    }
}