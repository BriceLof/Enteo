<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Entity\Ville;

/**
 * Ville controller.
 *
 */
class VilleController extends Controller
{
    /**
     * Ajax form for Ville entity
     *
     */
    public function ajaxSearchAction(Request $request)
    {
    }
}