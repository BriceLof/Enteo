<?php
namespace Application\UsersBundle\Services\Mailer;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Services\Mailer;
use Application\UsersBundle\Entity\Mission;
use Application\UsersBundle\Entity\Users;

class MissionMailer extends Mailer
{
    public function newMission(Beneficiaire $beneficiaire, Users $consultant, $attachement){

        $ref = 'm-1';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "Proposition de mission pour l'accompagnement VAE de ". ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso());
        $template = '@Aub/Mission/mail/newMission.html.twig';
        $to = array("email_adress" => $consultant->getEmail(), "alias" => $consultant->getEmail());
        $cc = null;
        $bcc = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clementmolier@entheor.com" => "Christine Clement",
            "support.information@entheor.com" => "support info",
            "contact@entheor.com" => 'Administratif'
        );
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body, $attachement);
    }

    public function acceptedMission(Mission $mission){
        $consultant = $mission->getConsultant();
        $beneficiaire = $mission->getBeneficiaire();
        $ref = 'm-2a';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "La Proposition de mission pour l'accompagnement de ".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso()[0])." ".strtoupper($beneficiaire->getNomConso())." a été acceptée par ".ucfirst($consultant->getCivilite())." ".ucfirst($consultant->getPrenom())." ".strtoupper($consultant->getNom());
        $template = '@Aub/Mission/mail/acceptedMission.html.twig';
        $query = $this->em->getRepository('ApplicationUsersBundle:Users')->search('ROLE_GESTION');
        $gestionnaires = $query->getResult();
        foreach ($gestionnaires as $gestionnaire){
            $emails [$gestionnaire->getEmail()] = $gestionnaire->getEmail();
        }
        $emails ['f.azoulay@entheor.com'] = 'f.azoulay@entheor.com';
        $emails ['ph.rouzaud@entheor.com'] = 'ph.rouzaud@entheor.com';
        $emails ['christine.clementmolier@entheor.com'] = 'christine.clementmolier@entheor.com';
        $to = $emails;
        $cc = null;
        $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");
        $body = $this->templating->render($template, array(
            'mission' => $mission,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

    public function acceptedMissionB(Mission $mission){
        $consultant = $mission->getConsultant();
        $beneficiaire = $mission->getBeneficiaire();
        $ref = 'm-2b';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "Vous avez accepté l'accompagnement VAE de ".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso());
        $to = array("email_adress" => $consultant->getEmail(), "alias" => $consultant->getEmail());
        $cc = null;
        $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");
        $template = '@Aub/Mission/mail/acceptedMissionB.html.twig';
        $body = $this->templating->render($template, array(
            'mission' => $mission,
            'reference' => $ref,
            'consultant' => $consultant,
            'beneficiaire' => $beneficiaire
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

    public function confirmedMission(Beneficiaire $beneficiaire, Users $consultant){

        $ref = 'm-3a';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "Vous pouvez démarrer l'accompagnement de ". ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso());
        $template = '@Aub/Mission/mail/confirmedMission.html.twig';
        $to = array("email_adress" => $consultant->getEmail(), "alias" => $consultant->getEmail());
        $cc = null;
        $bcc = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clement@entheor.com" => "Christine Clement",
            "support.information@entheor.com" => "support info",
            "contact@entheor.com" => 'Administratif'
        );
        $body = $this->templating->render($template, array(
            'consultant' => $consultant,
            'beneficiaire' => $beneficiaire,
            'reference' => $ref
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

    public function declinedMission(Mission $mission, $message){
        $beneficiaire = $mission->getBeneficiaire();
        $ref = 'm-2r';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "Mission refusée par le consultant";
        $template = '@Aub/Mission/mail/declinedMission.html.twig';
        $query = $this->em->getRepository('ApplicationUsersBundle:Users')->search('ROLE_GESTION');
        $gestionnaires = $query->getResult();
        foreach ($gestionnaires as $gestionnaire){
            $emails [$gestionnaire->getEmail()] = $gestionnaire->getEmail();
        }
        $emails ['f.azoulay@entheor.com'] = 'f.azoulay@entheor.com';
        $emails ['ph.rouzaud@entheor.com'] = 'ph.rouzaud@entheor.com';
        $emails ['christine.clementmolier@entheor.com'] = 'christine.clementmolier@entheor.com';
        $to = $emails;
        $cc = null;
        $bcc = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clementmolier@entheor.com" => "Christine Clement",
            "support.information@entheor.com" => "support info",
        );
        $body = $this->templating->render($template, array(
            'mission' => $mission,
            'reference' => $ref,
            'message' => $message
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

    public function revokedMission(Mission $mission, $message){
        $beneficiaire = $mission->getBeneficiaire();
        $ref = 'm-3r';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "Accompagnement de ".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso());
        $template = '@Aub/Mission/mail/revokedMission.html.twig';
        $query = $this->em->getRepository('ApplicationUsersBundle:Users')->search('ROLE_GESTION');
        $gestionnaires = $query->getResult();
        foreach ($gestionnaires as $gestionnaire){
            $emails [$gestionnaire->getEmail()] = $gestionnaire->getEmail();
        }
        $emails ['f.azoulay@entheor.com'] = 'f.azoulay@entheor.com';
        $emails ['ph.rouzaud@entheor.com'] = 'ph.rouzaud@entheor.com';
        $emails ['christine.clementmolier@entheor.com'] = 'christine.clementmolier@entheor.com';
        $emails['support.informatique@entheor.com'] = "support.informatique@entheor.com";
        $to = array("email_adress" => $mission->getConsultant()->getEmail(), "alias" => $mission->getConsultant()->getEmail());
        $cc = null;
        $bcc = $emails;
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'mission' => $mission,
            'reference' => $ref,
            'message' => $message
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

    public function modifiedMission(Mission $mission, Beneficiaire $beneficiaire){
        $ref = 'm-3r-change';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso()).": changement de consultant en cours d'accompagnement";
        $template = '@Aub/Mission/mail/modifiedMission.html.twig';
        $query = $this->em->getRepository('ApplicationUsersBundle:Users')->search('ROLE_GESTION');
        $gestionnaires = $query->getResult();
        foreach ($gestionnaires as $gestionnaire){
            $emails [$gestionnaire->getEmail()] = $gestionnaire->getEmail();
        }
        $emails ['f.azoulay@entheor.com'] = 'f.azoulay@entheor.com';
        $emails ['ph.rouzaud@entheor.com'] = 'ph.rouzaud@entheor.com';
        $emails ['christine.clementmolier@entheor.com'] = 'christine.clementmolier@entheor.com';
        $to = $emails;
        $cc = null;
        $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");
        $body = $this->templating->render($template, array(
            'beneficiaire' => $beneficiaire,
            'mission' => $mission,
            'reference' => $ref,
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

    public function abandonnedMission(Mission $mission){
        $beneficiaire = $mission->getBeneficiaire();
        $ref = 'm-3r-abandonned';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso()).": arrêt de la VAE en cours d'accompagnement";
        $template = '@Aub/Mission/mail/abandonnedMission.html.twig';
        $query = $this->em->getRepository('ApplicationUsersBundle:Users')->search('ROLE_GESTION');
        $gestionnaires = $query->getResult();
        foreach ($gestionnaires as $gestionnaire){
            $emails [$gestionnaire->getEmail()] = $gestionnaire->getEmail();
        }
        $emails [$mission->getConsultant()->getEmail()] = $mission->getConsultant()->getEmail();
        $to = $emails;
        $cc = array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clementmolier@entheor.com" => "Christine Clement",
            "support.information@entheor.com" => "support info",
        );
        $bcc = array("email_adress" => "support.informatique@entheor.com", "alias" => "support.informatique@entheor.com");
        $body = $this->templating->render($template, array(
            'mission' => $mission,
            'reference' => $ref,
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }


    public function changeConsultant(Users $consultant, Beneficiaire $beneficiaire){
        $ref = 'm-3r-change consultant';
        $from = array("email_adress" => "christine.clementmolier@entheor.com", "alias" => "christine.clementmolier@entheor.com");
        $subject = "Suspension de la mission d'accompagnement de ".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso());
        $template = '@Aub/Mission/mail/changeConsultant.html.twig';
        $query = $this->em->getRepository('ApplicationUsersBundle:Users')->search('ROLE_GESTION');
        $gestionnaires = $query->getResult();
        foreach ($gestionnaires as $gestionnaire){
            $emails [$gestionnaire->getEmail()] = $gestionnaire->getEmail();
        }
        $to = array("email_adress" => $consultant->getEmail(), "alias" => $consultant->getEmail());
        $cc = null;
        $bcc = array_merge(array(
            "f.azoulay@entheor.com" => "Franck AZOULAY",
            "ph.rouzaud@iciformation.fr" => "Philippe ROUZAUD",
            "christine.clementmolier@entheor.com" => "Christine Clement",
            "support.information@entheor.com" => "support info",
        ), $emails);
        $body = $this->templating->render($template, array(
            'consultant' => $consultant,
            'beneficiaire' => $beneficiaire,
            'reference' => $ref,
        ));

        $this->sendMessage($from, $to,null, $cc, $bcc, $subject, $body);
    }

}