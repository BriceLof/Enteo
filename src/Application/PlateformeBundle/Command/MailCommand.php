<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail')
            ->setDescription('effacer les images temporaires')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();

        foreach ($beneficiaires as $beneficiaire){
            $news = $beneficiaire->getNews();
            if( !is_null($news[count($news)-2])){
                $lastNews = $news[count($news)-1];
                $nextToNews = $news[count($news)-2];
                $now = new \DateTime();
                $slugLastNewsStatus = $news[count($news)-1]->getStatut()->getSlug();
                $slugNextToNewsStatus = $news[count($news)-2]->getStatut()->getSlug();

                //si le statut est : RV1 réalisé ou RV2 réalisé

                if( $slugLastNewsStatus == "rv1-realise" || $slugLastNewsStatus == "rv2-realise"){

                    //si l'avant dernier statut est : RV1 à faire ou RV2 à faire
                    if( $slugNextToNewsStatus == "rv1-a-faire" || $slugNextToNewsStatus == "rv2-a-faire" ){

                        //si le dernier news date de mois de 24h
                        if( $lastNews->getDateHeure() < $now && $lastNews->getDateHeure() >= $now->modify('-1 day') ){
                            //envoi du mail
                            $this->getContainer()->get('application_plateforme.statut.cron.rv')->mailRvRealise($beneficiaire, $lastNews);

                        }
                    }
                }
            }

            //la date du jour doit être comprise entre la date de la modification et la la date de modification + 1 jour
            /**
            if($beneficiaire->getUpdatedAt() < new \DateTime('now') && new \DateTime('now') < $beneficiaire->getUpdatedAt()->modify('+1 day')){
                //envoi du mail

            }
             */
        }
    }
}