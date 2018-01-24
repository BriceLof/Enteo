<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\MissionArchive;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Application\PlateformeBundle\Entity\Historique;
use Application\UsersBundle\Form\DocumentType;
use Application\UsersBundle\Form\MailingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * MissionArchive controller.
 *
 */
class MissionArchiveController extends Controller
{

    /**
     * cette fonction permet d'archiver et supprimer une mission
     *
     * @param $mission
     * @param $message
     * @param $state
     * @param $beneficiare
     */
    public function newAction($mission, $message, $state, $beneficiaire = null){
        $em = $this->getDoctrine()->getManager();
        $missionArchive = new MissionArchive();

        $file = realpath($this->get('kernel')->getRootDir())."/../web/uploads/consultant/".$mission->getConsultant()->getId()."/".$mission->getDocument();
        $destination = realpath($this->get('kernel')->getRootDir())."/../web/uploads/consultant/".$mission->getConsultant()->getId()."/archives/";
        $name = strtolower("contrat_".$mission->getBeneficiaire()->getNomConso()."_".$mission->getBeneficiaire()->getPrenomConso()."_".(new \DateTime('now'))->getTimestamp().".pdf");


        if (!file_exists($destination)){
            mkdir($destination);
        }
        rename($file,$destination.$name);

        $missionArchive->setBeneficiaire($mission->getBeneficiaire());
        $missionArchive->setConsultant($mission->getConsultant());
        $missionArchive->setDocument($name);
        $missionArchive->setState($state);
        $missionArchive->setInformation($message);
        $missionArchive->setDate(new \DateTime('now'));
        $missionArchive->setTarif($mission->getTarif());
        $em->persist($missionArchive);

        $this->forward('ApplicationUsersBundle:Mission:delete', array(
            'id' => $mission->getId()
        ));

        if ($state == 'declined'){
            $this->get('application_users.mailer.mail_for_mission')->declinedMission($mission,$message);
        }elseif ($state == 'declined'){
            $this->get('application_users.mailer.mail_for_mission')->revokedMission($mission,$message);
        }elseif ($state == 'modified'){
            $this->get('application_users.mailer.mail_for_mission')->modifiedMission($mission,$beneficiaire);
            $this->get('application_users.mailer.mail_for_mission')->changeConsultant($mission->getConsultant(), $mission->getBeneficiaire());
        }elseif ($state == 'abandonned'){
            $this->get('application_users.mailer.mail_for_mission')->abandonnedMission($mission);

            $beneficiaire = $mission->getBeneficiaire();
            $suiviMission = new SuiviAdministratif();
            $suiviMission->setBeneficiaire($beneficiaire);
            $suiviMission->setInfo("Abandon du bénéficiaire en cours de VAE");
            $em->persist($suiviMission);

            $historique = new Historique();
            $historique->setHeuredebut(new \DateTime('now'));
            $historique->setHeurefin(new \DateTime('now'));
            $historique->setSummary("");
            $historique->setTypeRdv("");
            $historique->setConsultant($beneficiaire->getConsultant());
            $historique->setBeneficiaire($beneficiaire);
            $historique->setDescription("Abandon du bénéficiaire en cours de VAE");
            $historique->setEventId("0");
            $historique->setUser($this->getUser());
            $em->persist($historique);

        }
        $em->flush();
    }

    /**
     * afficher tous les missions archivés
     *
     * @return mixed
     */
    public function showAllAction(){
        $em = $this->getDoctrine()->getManager();
        $missionArchives = $em->getRepository('ApplicationUsersBundle:MissionArchive')->findAll();

        return $this->render('ApplicationUsersBundle:MissionArchive:showAll.html.twig', array(
            'missionArchives' => $missionArchives,
        ));
    }

}