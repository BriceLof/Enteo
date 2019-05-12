<?php

namespace Api\PlatformBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Historique;
use Api\PlatformBundle\Form\BeneficiaryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class BeneficiaryController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"beneficiary"})
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

            $offices = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll2($ville, 1, true);
            if(empty($offices)){
                $offices[0] = $this->getOfficeAction($this->getParameter('id_bureau_distantiel'), $request);
            }

            $beneficiary->setBureau($offices[0]);
            $beneficiary->setVilleMer($ville);
            $beneficiary->setVille($ville);
            $beneficiary->setPays("FR");

            $em->persist($beneficiary);

            $historique = new Historique();
            $historique->setSummary("");
            $historique->setTypeRdv("");
            $historique->setBeneficiaire($beneficiary);
            $historique->setDescription(date('d/m/Y')." | ".$beneficiary->getVille()->getNom()." | ".$beneficiary->getOrigineMer());
            $historique->setEventId("0");
            $em->persist($historique);

            $em->flush();
            return $beneficiary;
        } else {
            return $form;
        }
    }
}
