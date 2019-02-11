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
        $this->get('application_plateforme.facture.cron.status')->listBeneficiareWithInvoice();
        exit;
        
        return $this->render("ApplicationPlateformeBundle:Alert:index.html.twig", array(
            
        ));
    }
}

