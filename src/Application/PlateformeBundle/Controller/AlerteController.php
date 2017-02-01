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
        $service = $this->container->get('application_plateforme.statut.cron.rv')->alerteSuiteRv1();
        var_dump($service);
       // var_dump($service->getDateHeure());
       // var_dump(new \DateTime);
        
        return $this->render("ApplicationPlateformeBundle:Alert:index.html.twig", array(
            
        ));
    }
}

