<?php

namespace Application\PlateformeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Mapping as ORM;

class MailRvCommand extends ContainerAwareCommand{
    protected function configure()
    {
        $this
            ->setName('plateforme:mail')
            ->setDescription("
                      1) CRON chaque jour à 21h00 et si statut bénéficiaire passe de R1 ou R2 à faire à R1 ou R2 positif
                      2) Si à h+24 de l'heure du Rdv la fiche bénéficiaire N'EST PAS MAJ => +++ Relance email (1b) au Consultant 
                      3) Si à h+48 de l'heure du Rdv la fiche bénéficiaire N'EST PAS MAJ => Relance email (1c) au Consultant ")
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Si définie, la tâche criera en majuscules')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findAll();
        $now = new \DateTime();

        foreach ($beneficiaires as $beneficiaire){
            $news = $beneficiaire->getNews();

            //CRON chaque jour à 21h00 et si statut bénéficiaire passe de R1 ou R2 à faire à R1 ou R2 positif +++ => Envoyer email
            if( !is_null($news[count($news)-2])){
                $lastNews = $news[count($news)-1];
                $nextToNews = $news[count($news)-2];
                $slugLastNewsStatus = $lastNews->getStatut()->getSlug();
                $slugNextToNewsStatus = $nextToNews->getStatut()->getSlug();

                //si le statut est : RV1 réalisé ou RV2 réalisé

                if( $slugLastNewsStatus == "rv1-realise" || $slugLastNewsStatus == "rv2-realise"){

                    //si l'avant dernier statut est : RV1 à faire ou RV2 à faire
                    if( $slugNextToNewsStatus == "rv1-a-faire" || $slugNextToNewsStatus == "rv2-a-faire" ){

                        $dateLastNews = $lastNews->getDateHeure();
                        //$dateLastNews->modify('-1 day');
                        //si le dernier news date de mois de 24h
                        if( $dateLastNews >= $now->modify('-1 day') && $dateLastNews < $now->modify('+1 day') ){
                            //envoi du mail
                            $this->getContainer()->get('application_plateforme.statut.cron.rv')->mailRvRealise($beneficiaire, $lastNews);
                        }
                        $now->modify('+1 day');
                    }
                }
            }

            //Si à h+24 de l'heure du Rdv la fiche bénéficiaire N'EST PAS MAJ => +++ Relance email (1b) au Consultant
            $majBene = $beneficiaire->getUpdatedAt();
            $historique = $beneficiaire->getHistorique();
            $lastHistorique = $historique[count($historique)-1];

            //si le dernier historique existe?
            if(!is_null($lastHistorique)){
                $rv = substr($lastHistorique->getSummary(),0,2);

                //si le dernier historique, son summary commence par "RV" car les autres historique n'a pas de summary
                if($rv == "RV"){
                    $lastHistorique->getDateDebut()->modify('+1 day');
                    //si la date de ce dernier historique date de mois de 24h au moment du lancement de la cron journalier
                    //et que la fiche bénéficiaire n'est pas mis a jour
                    //envoi un mail au consultant
                    if( $lastHistorique->getDateDebut() > $now->modify('-1 day') ){
                        if( $lastHistorique->getDateDebut() <= $now->modify('+1 day')){
                            //si la date du dernier historique n'est pas anterieure a la date du dernier mis a jour
                            if($lastHistorique->getDateDebut() > $beneficiaire->getUpdatedAt()->modify('+1 day') ){
                                $this->getContainer()->get('application_plateforme.statut.cron.rv')->firstMailRvFicheNonMaj($beneficiaire);
                                $beneficiaire->getUpdatedAt()->modify('-1 day');
                            }else{
                                $beneficiaire->getUpdatedAt()->modify('-1 day');
                            }
                        }
                    }else{
                        $now->modify('+1 day');
                    }

                    $lastHistorique->getDateDebut()->modify('+1 day');
                    //si la date de ce dernier historique date de mois de 48h au moment du lancement de la cron journalier
                    //et que la fiche bénéficiaire n'est pas mis a jour
                    //envoi un mail au consultant
                    if( $lastHistorique->getDateDebut() > $now->modify('-1 day') ){
                        if( $lastHistorique->getDateDebut() <= $now->modify('+1 day')){
                            //si la date du dernier historique n'est pas anterieure a la date du dernier mis a jour
                            if($lastHistorique->getDateDebut() > $beneficiaire->getUpdatedAt()->modify('+2 day') ){
                                $this->getContainer()->get('application_plateforme.statut.cron.rv')->secondMailRvFicheNonMaj($beneficiaire);
                                $beneficiaire->getUpdatedAt()->modify('-2 day');
                            }else{
                                $beneficiaire->getUpdatedAt()->modify('-2 day');
                            }
                        }
                    }else{
                        $now->modify('+2 day');
                    }

                    //mis a jour de la date du dernier historique
                    $lastHistorique->getDateDebut()->modify('-2 day');
                }
            }
        }
    }
}