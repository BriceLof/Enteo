<?php

namespace Api\PlatformBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\BeneficiaryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class BeneficiaryController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Post("/beneficiaries")
     */
    public function postBeneficiariesAction(Request $request)
    {
        $beneficiary = new Beneficiaire();
        $form = $this->createForm(BeneficiaryType::class, $beneficiary);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($beneficiary);
//        $em->flush();
            return $beneficiary;
        }else{
            return $form;
        }
    }
}
