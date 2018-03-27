<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Facture;
use Application\PlateformeBundle\Form\RechercheBeneficiaireType;
use Application\PlateformeBundle\Form\FactureFiltreType;
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

        $complementStatut = $form->get('complementStatut')->getData();
        $detailStatut =  $form->get("detailStatut")->getData();
        $cacher =  $form->get("cacher")->getData();
        $statut =  $form->get("statut")->getData();
        $tri = (int)$form['tri']->getData();
        $ville = $form['ville']->getData();

        $codePostal = null;
        $dateDebut = null;
        $dateFin = null;

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(), $dateDebut, $dateFin, $idUtilisateur, false, $tri, $ville,$statut, $detailStatut, $complementStatut, $cacher);
        $results = $query->getResult();

        $beneficiaires = $results;

//        ici la création du fichier csv
        return $this->get('application_plateforme.csv')->getCsvFromListBeneficiaire($beneficiaires);
    }

    public function getListFactureAction(Request $request){
        $facture = new Facture();
        $form = $this->createForm(FactureFiltreType::class, $facture);
        $form->handleRequest($request);

        $statut = $form->get('statut')->getData();
        $dateDebutAccompagnement = $form->get('date_debut_accompagnement')->getData();
        $dateFinAccompagnement = $form->get('date_fin_accompagnement')->getData();
        $dateDebutAccompagnementStart = $form->get('date_debut_accompagnement_start')->getData();
        $dateDebutAccompagnementEnd = $form->get('date_debut_accompagnement_end')->getData();
        $dateFinAccompagnementStart = $form->get('date_fin_accompagnement_start')->getData();
        $dateFinAccompagnementEnd = $form->get('date_fin_accompagnement_end')->getData();
        $dateFactureStart = $form->get('date_facture_start')->getData();
        $dateFactureEnd = $form->get('date_facture_end')->getData();
        $consultant = $form->get('consultant')->getData();
        $financeur = $form->get('financeur')->getData();
        $villeMer = $form->get('ville_mer')->getData();

        $numeroFacture = $form->get('numero_facture')->getData();
        $anneeNumeroFacture = $form->get('annee_numero_facture')->getData();
        $numFactu = $numeroFacture.$anneeNumeroFacture;

        $beneficiaireGet = $request->get('beneficiaire_ajax');
        $beneficiaire = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findOneById($beneficiaireGet);

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Facture')->search($statut, $dateDebutAccompagnement, $dateFinAccompagnement, $dateDebutAccompagnementStart, $dateDebutAccompagnementEnd, $dateFinAccompagnementStart, $dateFinAccompagnementEnd, $dateFactureStart, $dateFactureEnd, $consultant, $beneficiaire, $numFactu, $financeur, $villeMer);
        $factures = $query;

//        ici la création du fichier csv
        return $this->get('application_plateforme.csv')->getCvForFacture($factures);
    }
}