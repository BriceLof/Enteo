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
        ini_set('memory_limit',-1);
        $em = $this->getContainer()->get('doctrine')->getManager();
        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();
        $now = new \DateTime('now');
        $now->format('Y-m-d');
        $today = date_format($now,'d-m-Y');
        $i = 0;

        foreach ($beneficiaires as $beneficiaire){
            $suiviAdministratif = $beneficiaire->getSuiviAdministratif();
//            $output->writeln($beneficiaire->getId());
//            $output->writeln(!empty($suiviAdministratif->getDetail));exit;
            if (count($suiviAdministratif) != 0) {
                $lastSuiviAdministratif = $suiviAdministratif[count($suiviAdministratif) - 1];
                if ($lastSuiviAdministratif->getDetailStatut() != null && $lastSuiviAdministratif->getDetailStatut()->getDetail() == "Attente accord") {
                    $dateLastSuivi = $lastSuiviAdministratif->getDate();
                    $dateLastSuivi->modify('+21 day');
                    $date = date_format($dateLastSuivi, 'd-m-Y');
                    if ($date == $today) {
                        $i++;
                        $tabBeneficiaire[] = $beneficiaire;
                    }
                }
            }
        }
        $output->writeln($i);
        if ($i > 0){
            $this->getContainer()->get('application_plateforme.statut.mail.mail_for_statut')->alerteAttenteAccord($tabBeneficiaire);
        }
    }
}