<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Form\NewsType;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class AlerteController extends Controller
{
    public function indexAction()
    {
        $subject = "Test authentification mail";
        $from = array("email" => "audrey.azoulay@entheor.com", "name" => "Audre Azoulay");
        $to = array(array("email" => "brice.lof@gmail.com", "name" => "Brice Lof"));
        $cc = array(array("email" => "b.lof@iciformation.fr", "name" => "Brice Lof"), array("email" => "brice.lof@stepstone.fr", "name" => "Brice Lof"));
        $body = "<b>Contrairement</b><br> à une opinion répandue, le Lorem Ipsum n'est pas simplement du texte aléatoire. Il trouve ses racines dans une oeuvre de la littérature latine classique datant de 45 av. J.-C., le rendant vieux de 2000 ans. Un professeur du Hampden-Sydney College, en Virginie, s'est intéressé à un des mots latins les plus obscurs, consectetur, extrait d'un passage du Lorem Ipsum, et en étudiant tous les usages de ce mot dans la littérature classique, découvrit la source incontestable du Lorem Ipsum. Il provient en fait des sections 1.10.32 et 1.10.33 du \"De Finibus Bonorum et Malorum\" (Des Suprêmes Biens et des Suprêmes Maux) de Cicéron. Cet ouvrage, très populaire pendant la Renaissance, est un traité sur la théorie de l'éthique. Les premières lignes du Lorem Ipsum, \"Lorem ipsum dolor sit amet...\", proviennent de la section 1.10.32.
L'extrait standard de Lorem Ipsum utilisé depuis le XVIè siècle est reproduit ci-dessous pour les curieux. Les sections 1.10.32 et 1.10.33 du \"De Finibus Bonorum et Malorum\" de Cicéron sont aussi reproduites dans leur version originale, accompagnée de la traduction anglaise de H. Rackham (1914).";

        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find(6686);

        $sms = array();
        $telephone = $beneficiaire->getTelConso();
        // Si c'est un telephone mobile francais
        if(substr($telephone, 0, 2) == '06' || substr($telephone, 0, 2) == '07'){
            $telephone = "33".substr($telephone, 1);
            $sms['recipient'] = $telephone;
            $sms['content'] = "Mon second sms. Es-tu prêt pour les sms façon Doctolib ?";
            $sms['type'] = "transactional";
            $sms['tag'] = "rv_sms";
        }

		$service = $this->get('application_plateforme.mail')->sendMessage($from, $to, null,  $cc, null, $subject, $body,  null, $sms);
		exit;

        //$test = $this->get('application_plateforme.statut.cron.cron_beneficiaire')->beneficiairePreviousWeekNoContact();
        
        return $this->render("ApplicationPlateformeBundle:Alert:index.html.twig", array(
            
        ));
    }
}

