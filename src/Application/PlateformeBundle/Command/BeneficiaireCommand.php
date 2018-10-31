<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\PlateformeBundle\Entity\News;
use Doctrine\ORM\Mapping as ORM;

class BeneficiaireCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:beneficiaire')
            ->setDescription("Cette commade a été créer pour mettre a jour les infos des bénéficiaire dans la base de données")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();

        $tab = array();

        foreach ($beneficiaires as $beneficiaire){
            if ($beneficiaire->getDeleted() == true) {
                $tab[] = $beneficiaire->getId();
            }
        }

        $suivis = $em->getRepository('ApplicationPlateformeBundle:SuiviAdministratif')->findAll();
        foreach ($suivis as $suivi){
            if (in_array($suivi->getBeneficiaire()->getId(), $tab)){
                $em->remove($suivi);
            }
        }

        $em->flush();


    }
}