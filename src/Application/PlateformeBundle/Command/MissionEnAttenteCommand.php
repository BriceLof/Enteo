<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Application\PlateformeBundle\Entity\News;
use Doctrine\ORM\Mapping as ORM;

class MissionEnAttenteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:missionEnAttente')
            ->setDescription("  [x] Missions en attente validation Consultants et [Y] en attente traitement Enthéor
                                Réf : Mission 10    
                                Fréquence envoi : Tous les lundi 08H30")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $missions = $em->getRepository('ApplicationUsersBundle:Mission')->findAll();
        $new = array();
        $accepted = array();
        $i = 0;
        $j = 0;

        foreach ($missions as $mission) {
            switch ($mission->getState()) {
                case 'new':
                    $i++;
                    $new[] = $mission;
                    break;
                case 'accepted' :
                    $j++;
                    $accepted[] = $mission;
            }
        }

        $this->getContainer()->get('application_users.mailer.mail_for_mission')->alerteMissionEnAttente($new, $accepted);
    }
}