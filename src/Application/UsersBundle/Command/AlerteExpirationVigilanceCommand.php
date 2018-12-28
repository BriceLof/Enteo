<?php

namespace Application\UsersBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class AlerteExpirationVigilanceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('users:alerteExpirationVigilance')
            ->setDescription("une alerte CRON sur la fin de validité des Attestations de vigilance 
                                - Uiliser la date de téléchargement + 6 mois pour déterminer la fin de validité.
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
            if (is_null($consultant->getFacturation())){
                $tabConsultant[] = $consultant;
                $i++;
            }else{
                if ($consultant->getFacturation()->getDate() < (new \DateTime('now'))){
                    $tabConsultant[] = $consultant;
                    $i++;
                }
            }
        }


        if ($i > 0) {

            usort($tabConsultant, function ($a, $b) {
                return strcmp($a->getNom(), $b->getNom());
            });

            $csv = $this->getContainer()->get('application_users.csv')->getCsvExpirationVigilance($tabConsultant);
            $attachement1 = array("name" => 'expiration_vigilance_ursaff_' . (new \DateTime('now'))->format('d_m_y') . '.csv', "file" => $csv);
            $this->getContainer()->get('application_users.mailer.mail_users')->alerteExpirationVigilance($tabConsultant, $attachement1);
        }
    }
}