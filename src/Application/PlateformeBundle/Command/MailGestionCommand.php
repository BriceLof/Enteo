<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailGestionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:mailGestion')
            ->setDescription("alerte automatique à destination des gestionnaires admn (et copie les administrateurs) 3 semaines après passage au statut adm = Financement / Attente Accord ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit',-1);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();
        $i = 0;
        $j = 0;

        foreach ($beneficiaires as $beneficiaire) {
            $result = $em->getRepository('ApplicationPlateformeBundle:SuiviAdministratif')->getLast($beneficiaire);

            if (!empty($result)) {
                $news = $beneficiaire->getNews();
                $lastNews = $news[count($news) - 1];
                $lastSuiviAdministratif = $result[0];
                if ($lastNews->getDetailStatut()->getId() == 7 || $lastNews->getDetailStatut()->getId() == 14) {
                    if ( $lastSuiviAdministratif->getDetailStatut()->getId() == 20 ) {
                        $dateLastSuivi = $lastSuiviAdministratif->getDate();
                        if ($dateLastSuivi->setTime(0,0,0) <= (new \DateTime('now'))->setTime(0,0,0)) {
                            $i++;
                            $tab1[] = $lastSuiviAdministratif;
                        }
                    }
                    if ( $lastSuiviAdministratif->getDetailStatut()->getId() == 19 ) {
                        $dateLastSuivi = $lastSuiviAdministratif->getDate();
                        if ($dateLastSuivi <= (new \DateTime('now'))->modify("-8 day")->setTime(0,0,0)) {
                            $j++;
                            $tab2[] = $lastSuiviAdministratif;
                        }
                    }
                }
            }
        }

        if ($i > 0) {
            usort($tab1, function ($a, $b) {
                if ($a->getDate() == $b->getDate()) {
                    return 0;
                }
                return $a->getDate() > $b->getDate() ? -1 : 1;
            });

            $csv = $this->getContainer()->get('application_plateforme.csv')->getCvsForMailSuivi($tab1, "Financement - Attente accord");
            $attachement1 = array("name" => 'dossiers_attente_accord_' . (new \DateTime('now'))->format('d_m_y') . '.csv', "file" => $csv);
            $this->getContainer()->get('application_plateforme.statut.mail.mail_for_statut')->alerteAttenteAccord($tab1, $attachement1);
        }

        if ($j > 0) {
            usort($tab2, function ($a, $b) {
                if ($a->getDate() == $b->getDate()) {
                    return 0;
                }
                return $a->getDate() > $b->getDate() ? -1 : 1;
            });

            $csv2 = $this->getContainer()->get('application_plateforme.csv')->getCvsForMailSuivi($tab2, "Dossier en cours - En attente de traitement Entheor");
            $attachement2 = array("name" => 'dossiers_attente_traitement_' . (new \DateTime('now'))->format('d_m_y') . '.csv', "file" => $csv2);
            $this->getContainer()->get('application_plateforme.statut.mail.mail_for_statut')->alerteAttenteTraitement($tab2, $attachement2);
        }
    }
}