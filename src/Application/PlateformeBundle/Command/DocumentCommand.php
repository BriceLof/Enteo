<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class DocumentCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:document')
            ->setDescription('effacer les images temporaires')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $compteur = 0;

        $em = $this->getContainer()->get('doctrine')->getManager();

        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();

        foreach ($beneficiaires as $beneficiaire){
            $output->writeln($beneficiaire->getNomConso());
            $documents = $beneficiaire->getDocuments();
            foreach ($documents as $document){
                $this->getContainer()->get('application_plateforme.document')->supprimerDocument($beneficiaire, $document);
                $output->writeln('ok');
                $compteur++;
            }
        }
        $this->getContainer()->get('application_plateforme.mail')->mailRecapCronDocument($compteur);
    }
}