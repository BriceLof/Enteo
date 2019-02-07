<?php

namespace Api\PlatformBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Api\PlatformBundle\Form\BeneficiaryType;
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
        $beneficiary = $this->get('application.beneficiary');

        $form = $this->createForm(BeneficiaryType::class, $beneficiary);

        $form->submit($request->request->all());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $ville = $em->getRepository("ApplicationPlateformeBundle:Ville")->findOneBy(array('cp' => $form['codePostal']->getData()));

            $beneficiary->setVilleMer($ville);
            $beneficiary->setVille($ville);

            $em->persist($beneficiary);
            $em->flush();
            return $beneficiary;
        } else {
            return $form;
        }
    }
}
