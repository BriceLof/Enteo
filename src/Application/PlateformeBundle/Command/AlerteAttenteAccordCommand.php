<?php
namespace Application\PlateformeBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;
class AlerteAttenteAccordCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('plateforme:alerteAttenteAccord')
            ->setDescription("alerte automatique à destination des gestionnaires admn (et copie les administrateurs) 3 semaines après passage au statut adm = Financement / Attente Accord ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();
        $now = new \DateTime('now');
        $now->format('Y-m-d');
        $today = date_format($now,'d-m-Y');

        foreach ($beneficiaires as $beneficiaire){
            $suiviAdministratif = $beneficiaire->getSuiviAdministratif();
            $lastSuiviAdministratif = $suiviAdministratif[count($suiviAdministratif) - 1];
            if ($lastSuiviAdministratif != null) {
                if ($lastSuiviAdministratif->getDetailStatut()->getId() == 21){
                    $dateLastSuivi = $lastSuiviAdministratif->getDate();
                    $dateLastSuivi->modify('+21 day');
                    $date = date_format($dateLastSuivi, 'd-m-Y');
                    if ($date == $today){
                        $this->getContainer()->get('application_plateforme.statut.mail.mail_for_statut')->alerteAttenteAccord($beneficiaire, $lastSuiviAdministratif);
                    }
                }
            }
        }
    }
}