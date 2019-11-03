<?php

namespace Api\PlatformBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\Document;
use Api\PlatformBundle\Form\BeneficiaryType;
use Api\PlatformBundle\Form\StephanePlazaType;
use Application\PlateformeBundle\Entity\Ville;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
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

            if ($form['bureauId']->getData() == 290) {
                $ville = $em->getRepository("ApplicationPlateformeBundle:Ville")->findOneBy(array(
                    'nom' => $form['city']->getData(),
                    'pays' => $form['pays']->getData()
                ));
                if (is_null($ville)) {
                    $ville = new Ville();
                    $ville->setNom($form['city']->getData());
                    $ville->setPays($form['pays']->getData());
                    $ville->setDepartementId(0);
                    $em->persist($ville);
                }
                $beneficiary->setOrigineMer('Entheor.com_Rappel Expatrié_naturel');
            }else{
                $ville = $em->getRepository("ApplicationPlateformeBundle:Ville")->findOneBy(array('cp' => $form['codePostal']->getData()));
            }

            if (is_null($form['bureauId']->getData())) {
                $offices = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll2($ville, 1, true);
            }else{
                $offices = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findBy(array(
                    'id' => $this->getParameter('id_bureau_distantiel')
                ));
            }

            $beneficiary->setBureau($offices[0]);
            $beneficiary->setVilleMer($ville);
            $beneficiary->setVille($ville);

            if (is_null($beneficiary->getHeureRappel())) {
                $beneficiary->setHeureRappel("Indifférent");
            }

            $em->persist($beneficiary);

            $historique = new Historique();
            $historique->setSummary("");
            $historique->setTypeRdv("");
            $historique->setBeneficiaire($beneficiary);
            $historique->setDescription(date('d/m/Y') . " | " . $beneficiary->getVille()->getNom() . " | " . $beneficiary->getOrigineMer());
            $historique->setEventId("0");
            $em->persist($historique);

            $em->flush();
            return $beneficiary;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"beneficiary"})
     * @Rest\Post("/beneficiary/stephane-plaza")
     */
    public function postBeneficiaryStephanePlazaAction(Request $request)
    {

        $beneficiary = $this->get('application.beneficiary');

        $form = $this->createForm(StephanePlazaType::class, $beneficiary);

        $form->submit($request->request->all());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $ville = $em->getRepository("ApplicationPlateformeBundle:Ville")->findOneBy(array('cp' => $form['codePostal']->getData()));
            $villeMer = $em->getRepository("ApplicationPlateformeBundle:Ville")->findOneBy(array('cp' => 92110));

            $offices = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll2($ville, 1, true);

            $beneficiary->setBureau($offices[0]);
            $beneficiary->setVilleMer($villeMer);
            $beneficiary->setVille($ville);

            $beneficiary->setDomaineVae("Urbanisme, BTP et Immobilier");

            if (is_null($beneficiary->getHeureRappel())) {
                $beneficiary->setHeureRappel("Indifférent");
            }

            $document = new Document();
            $document->setFile($request->files->get('cvFile'));
            $document->setDescription('CV');

            $beneficiary->addDocument($document);

            $em->persist($beneficiary);

            $historique = new Historique();
            $historique->setSummary("");
            $historique->setTypeRdv("");
            $historique->setBeneficiaire($beneficiary);
            $historique->setDescription(date('d/m/Y') . " | " . $beneficiary->getVille()->getNom() . " | " . $beneficiary->getOrigineMer());
            $historique->setEventId("0");
            $em->persist($historique);

            var_dump($em->flush());die;

            $em->flush();
            return $beneficiary;
        } else {
            return $form;
        }
    }
}
