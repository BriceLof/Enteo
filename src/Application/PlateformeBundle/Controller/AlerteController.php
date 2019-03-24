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
//        $sms = array('recipient' => '33785727803',
//            'subject' => "test",
//            'content' => 'Voici le contenu');
//
//        $this->get('application_plateforme.mail')->sendMessage(null, null,null, null, null, null, null, null, $sms);

        
        return $this->render("ApplicationPlateformeBundle:Alert:index.html.twig", array(
            
        ));
    }
}

