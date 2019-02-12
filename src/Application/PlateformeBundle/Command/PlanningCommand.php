<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class PlanningCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:planning')
            ->setDescription("")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $tab1 = array();
        $tab2 = array();
        $tab3 = array();

        $em = $this->getContainer()->get('doctrine')->getManager();
        $consultants = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        foreach ($consultants as $consultant) {
            $beneficiaires = $consultant->getBeneficiaire();
            foreach ($beneficiaires as $beneficiaire){

                if (!is_null($beneficiaire->getDateLivret1())) {
                    if ($beneficiaire->getDateLivret1() <= (new \DateTime("now"))->setTime(0,0,0)){
                        $tab1[] = $beneficiaire;
                    }
                }

                if (!is_null($beneficiaire->getDateLivret2())) {
                    if ($beneficiaire->getDateLivret2() <= (new \DateTime("now"))->setTime(0,0,0)){
                        $tab2[] = $beneficiaire;
                    }
                }

                if (!is_null($beneficiaire->getDateJury())) {
                    if ($beneficiaire->getDateJury() <= (new \DateTime("now"))->setTime(0,0,0)){
                        $tab1[] = $beneficiaire;
                    }
                }
            }
        }

        $this->getContainer()->get('application_plateforme.statut.mail.mail_for_statut')->planning($tab1, $tab2, $tab3);
    }
}