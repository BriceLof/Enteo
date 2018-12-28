<?php

namespace Application\UsersBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class AlerteNumDeclarationActiviteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('users:alerteNumDeclarationActivite')
            ->setDescription("- Si champ n° de déclaration d'activité  = null ou différent de 9 caractères numériques => le nom du consultant sera liste  dans cette Cron.
                                - Cron envoyée le 1er de chaque mois. 
                                - Le mail présente les consultants concernés en liste + excel en PJ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', -1);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $consultants = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser('ROLE_CONSULTANT', true);

        $i = 0;
        foreach ($consultants as $consultant){
            if (is_null($consultant->getNumDeclarationActivite())){
                $tabConsultant[] = $consultant;
                $i++;
            }
        }

        if ($i > 0) {

            usort($tabConsultant, function ($a, $b) {
                return strcmp($a->getNom(), $b->getNom());
            });
            
            $csv = $this->getContainer()->get('application_users.csv')->getCsvNumDeclarationActivite($tabConsultant);
            $attachement1 = array("name" => 'consultant_sans_numero_declaration_activite_' . (new \DateTime('now'))->format('d_m_y') . '.csv', "file" => $csv);
            $this->getContainer()->get('application_users.mailer.mail_users')->alerteNumDeclarationActivite($tabConsultant, $attachement1);
        }
    }
}