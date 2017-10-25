<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\RechercheBeneficiaireType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * csv controller.
 *
 */
class CsvController extends Controller
{

    public function getListBeneficiaireAction(Request $request){

        $idUtilisateur = null;

        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') or true === $this->get('security.authorization_checker')->isGranted('ROLE_COMMERCIAL') or true === $this->get('security.authorization_checker')->isGranted('ROLE_GESTION')) {
        }else{
            $idUtilisateur = $this->getUser()->getId();
        }

        $beneficiaire = new Beneficiaire();

        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);

        $form->handleRequest($request);

        $detailStatut =  $form->get("detailStatut")->getData();
        $tri = (int)$form['tri']->getData();
        $ville = $form['ville']->getData();

        $codePostal = null;
        $dateDebut = null;
        $dateFin = null;

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(), $dateDebut, $dateFin, $idUtilisateur, false, $tri, $ville, $detailStatut);
        $results = $query->getResult();

        $beneficiaires = $results;

//        ici la création du fichier csv
        return $this->get('application_plateforme.csv')->getCsvFromListBeneficiaire($beneficiaires);
    }
}