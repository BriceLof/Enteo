<?php

namespace Application\PlateformeBundle\Services;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Bureau;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Services\Statut\Mail\MailRvAgenda;
use Application\UsersBundle\Entity\Users;
use Doctrine\ORM\EntityManager;
use Fungio\GoogleCalendarBundle\Service\GoogleCalendar;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class Calendar
{
    protected $em;
    protected $calendar;
    protected $mailRv;
    protected $router;

    public function __construct(EntityManager $em, GoogleCalendar $googleCalendar, MailRvAgenda $mailRv, RouterInterface $router)
    {
        $this->em = $em;
        $this->calendar = $googleCalendar;
        $this->mailRv = $mailRv;
        $this->router = $router;
    }


    /**
     * retourne un tableau de nom des couleurs de google calendar
     *
     * @return array
     */
    public function getColorName($codeColor)
    {
        $color = array(
            '%23B1365F' => '#b1365f',
            '%235C1158' => 'Fuchsia',
            '%23711616' => 'Red',
            '%23691426' => 'Crimson',
            '%23BE6D00' => 'Orange',
            '%23B1440E' => 'Orange Red',
            '%23853104' => 'Red Orange',
            '%238C500B' => 'Burnt Orange' ,
            '%23754916' => 'Brown Orange',
            '%2388880E' => '#bfbf4d',
            '%23AB8B00' => 'Goldenrod',
            '%23856508' => 'Darker Goldenrod',
            '%2328754E' => 'Green',
            '%231B887A' => 'Lighter Green',
            '%230D7813' => 'Forest Green',
            '%23528800' => 'Olive Green',
            '%23125A12' => 'Jungle Green',
            '%232F6309' => 'Another Olive',
            '%232F6213' => 'Another Green',
            '%230F4B38' => 'Sea Green',
            '%235F6B02' => 'Golden Olive',
            '%234A716C' => 'Green Gray',
            '%236E6E41' => 'Olive Gray',
            '%2329527A' => 'Dull Navy',
            '%232952A3' => 'Standard Blue',
            '%234E5D6C' => 'Blue Gray',
            '%235A6986' => 'Blue Steel',
            '%23182C57' => 'Another blue',
            '%23060D5E' => 'Dark Blue',
            '%23113F47' => 'Sea Blue',
            '%237A367A' => 'Violet',
            '%235229A3' => '#5229a3',
            '%23865A5A' => 'Purple Gray',
            '%23705770' => 'Purple Brown',
            '%2323164E' => 'Deep Purple',
            '%235B123B' => 'Magenta',
            '%2342104A' => 'Another Purple',
            '%23875509' => 'Yellow Brown',
            '%238D6F47' => 'Brown',
            '%236B3304' => 'Nice Brown',
            '%23333333' => 'Black',
        );

        return $color[$codeColor];
    }

    public function getColor(){
        $color = array(
            '%23B1365F',
            '%2388880E',
            '%235229A3',
            '%230F4B38',
            '%23856508',
            '%235C1158',
            '%23711616',
            '%23691426',
            '%23BE6D00',
            '%23B1440E',
            '%23853104',
            '%238C500B',
            '%23754916',
            '%23AB8B00',
            '%2328754E',
            '%231B887A',
            '%2328754E',
            '%23528800',
            '%23125A12',
            '%232F6213',
            '%234E5D6C',
            '%235F6B02',
            '%234A716C',
            '%236E6E41',
            '%230D7813',
            '%2329527A',
            '%232F6309',
            '%232952A3',
            '%235A6986',
            '%23182C57',
            '%23060D5E',
            '%23113F47',
            '%237A367A',
            '%235229A3',
            '%23865A5A',
            '%23705770',
            '%2323164E',
            '%235B123B',
            '%2342104A',
            '%23875509',
            '%238D6F47',
            '%236B3304',
            '%23333333',
        );

        return $color;
    }

    public function createEvent($form, Historique $historique, Beneficiaire $beneficiaire, Users $consultant, $edit = false, $old_rdv = null){
        $location = "";

        if($form['typerdv']->getData() == 'distantiel'){
            $historique->setBureau(null);
            $eventSummary = ucfirst($beneficiaire->getPrenomConso()[0]).'. '.$beneficiaire->getNomConso().', '.$historique->getSummary();
        }

        if($historique->getSummary() == "Autre" && $historique->getAutreSummary() != ""){
            $historique->setSummary($historique->getAutreSummary());
        }

        $eventSummaryBureau = $consultant->getNom().' '.$consultant->getPrenom().', '.$beneficiaire->getNomConso().' '.$beneficiaire->getPrenomConso().', '.$historique->getSummary();
        $eventDescription = $historique->getDescription().' (<a href="https://appli.entheor.com/web/beneficiaire/show/'.$beneficiaire->getId().'">voir fiche bénéficiaire</a>)';
        $dateDebut = $historique->getHeureDebut()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));
        $dateFin = $historique->getHeureFin()->setDate($historique->getDateDebut()->format('Y'),$historique->getDateDebut()->format('m'), $historique->getDateDebut()->format('d'));

        if ($form['autreBureau']->getData() == true && $form['typerdv']->getData() != 'distantiel'){
            $ville = $this->em->getRepository('ApplicationPlateformeBundle:Ville')->findOneBy(array(
                'nom' => $form["ville"]->getData(),
            ));
            $bureau = new Bureau();
            $bureau->setTemporaire(true);
            $bureau->setVille($ville);
            $bureau->setAdresse($form['adresseBureau']->getData());
            $bureau->setNombureau($form['nomBureau']->getData());
            $this->em->persist($bureau);
            $historique->setBureau($bureau);
            $eventSummary = ucfirst($beneficiaire->getPrenomConso()[0]).'. '.$beneficiaire->getNomConso().', '.$historique->getSummary().', '.$historique->getBureau()->getVille()->getNom();
        }else{
            //ajouter l'evenement dans le calendrier du bureau seulement si c'est en presentiel
            if($historique->getBureau() != null) {
                $eventSummary = ucfirst($beneficiaire->getPrenomConso()[0]).'. '.$beneficiaire->getNomConso().', '.$historique->getSummary().', '.$historique->getBureau()->getVille()->getNom();
                if ($historique->getBureau()->getCalendrierid() != ""){
                    if ($historique->getEventIdBureau() != "" or $historique->getEventIdBureau() != null){
                        $eventBureauUpdated = $this->calendar->addEvent($historique->getBureau()->getCalendrierid(), $historique->getEventIdBureau(), $dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                    }else{
                        $eventBureau = $this->calendar->addEvent($historique->getBureau()->getCalendrierid(), $dateDebut, $dateFin, $eventSummaryBureau, $eventDescription);
                        $historique->setEventIdBureau($eventBureau['id']);
                    }
                }
            }
        }

        if ($historique->getBureau() != null ){
            $location = $historique->getBureau()->getAdresse().', '.$historique->getBureau()->getVille()->getCp();
        }

        //utiliser event pour jouer avec l'evenement
        if ($edit == true){
            $eventUpdated = $this->calendar->updateEvent($consultant->getCalendrierid(), $historique->getEventId(), $dateDebut, $dateFin, $eventSummary, $eventDescription,"",$location);
        }else{
            $event = $this->calendar->addEvent($consultant->getCalendrierid(), $dateDebut, $dateFin, $eventSummary, $eventDescription,"",$location);
        }

        if ($edit == true){
            $date = $historique->getDateDebut()->setTime($historique->getHeureDebut()->format('H'),$historique->getHeureDebut()->format('i'));
            $historique->setDateDebut($date);

            $this->mailRv->alerteRdvAgenda($beneficiaire, $historique, $old_rdv);

            if ($old_rdv > new \DateTime() && $old_rdv < (new \DateTime())->modify('+1 day')){
                $newHistorique = clone $historique;
                $this->em->refresh($historique);
                $historique->setCanceled(2);
                $this->em->persist($newHistorique);
                $this->em->flush();

                return new RedirectResponse($this->router->generate('application_show_beneficiaire', array(
                    'beneficiaire' => $beneficiaire,
                    'id' => $beneficiaire->getId(),
                )));

            }else{
                $this->em->persist($historique);
                $this->em->flush();
            }
        }else{
            $historique->setConsultant($beneficiaire->getConsultant());
            $historique->setEventId($event['id']);
            $historique->setDateFin($dateFin);
            $date = $historique->getDateDebut()->setTime($historique->getHeureDebut()->format('H'),$historique->getHeureDebut()->format('i'));
            $historique->setDateDebut($date);
        }

        return null;
    }
}