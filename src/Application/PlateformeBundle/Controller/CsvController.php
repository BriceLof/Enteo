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
        $ids = null;

        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') or true === $this->get('security.authorization_checker')->isGranted('ROLE_COMMERCIAL') or true === $this->get('security.authorization_checker')->isGranted('ROLE_GESTION')) {
        }else{
            $idUtilisateur = $this->getUser()->getId();
            $ids[] = $this->getUser()->getId();
            foreach ($this->getUser()->getConsultants() as $consultant){
                $ids[] = $consultant->getId();
            }
        }

        $beneficiaire = new Beneficiaire();

        $form = $this->createForm(RechercheBeneficiaireType::class, $beneficiaire);

        $form->handleRequest($request);

        $complementStatut = $form->get('complementStatut')->getData();
        $complementDetailStatut = $form->get('complementDetailStatut')->getData();
        if (is_null($complementDetailStatut)){
            $complementDetailStatut = '>=';
        }
        $detailStatut =  $form->get("detailStatut")->getData();
        $cacher =  $form->get("cacher")->getData();
        $statut =  $form->get("statut")->getData();
        $tri = (int)$form['tri']->getData();
        $ville = $form['ville']->getData();

        $codePostal = null;
        $dateDebut = null;
        $dateFin = null;

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->search($form->getData(), $dateDebut, $dateFin, $idUtilisateur, false, $tri, $ville,$statut, $detailStatut, $complementStatut, $cacher, $complementDetailStatut, $ids);
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
        $dateDebutAccompagnement = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_debut_accompagnement')->getData());
        $dateFinAccompagnement = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_fin_accompagnement')->getData());
        $dateDebutAccompagnementStart = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_debut_accompagnement_start')->getData());
        $dateDebutAccompagnementEnd = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_debut_accompagnement_end')->getData());
        $dateFinAccompagnementStart = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_fin_accompagnement_start')->getData());
        $dateFinAccompagnementEnd = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_fin_accompagnement_end')->getData());
        $dateFactureStart = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_facture_start')->getData());
        $dateFactureEnd = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_facture_end')->getData());
        $consultant = $form->get('consultant')->getData();
        $financeur = $form->get('financeur')->getData();
        $villeMer = $form->get('ville_mer')->getData();
        $datePaiementStart = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_paiement_start')->getData());
        $datePaiementEnd = $this->get('application_plateforme.date')->transformDateFrtoEn($form->get('date_paiement_end')->getData());

        $numeroFacture = $form->get('numero_facture')->getData();
        $anneeNumeroFacture = $form->get('annee_numero_facture')->getData();
        $numFactu = $numeroFacture.$anneeNumeroFacture;

        $beneficiaireGet = $request->get('beneficiaire_ajax');
        $beneficiaire = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findOneById($beneficiaireGet);

        $query = $this->getDoctrine()->getRepository('ApplicationPlateformeBundle:Facture')->search($statut, $dateDebutAccompagnement, $dateFinAccompagnement, $dateDebutAccompagnementStart, $dateDebutAccompagnementEnd,
            $dateFinAccompagnementStart, $dateFinAccompagnementEnd, $dateFactureStart,
            $dateFactureEnd, $consultant, $beneficiaire, $numFactu, $financeur, $villeMer,
            $datePaiementStart, $datePaiementEnd);
        $factures = $query;

//        ici la création du fichier csv
        return $this->get('application_plateforme.csv')->getCsvForFacture($factures);
    }



    public function getFileEmployeurAction(Request $request){
        ini_set('memory_limit', -1);
        $em = $this->getDoctrine()->getManager();
        $employeurs = $em->getRepository('ApplicationPlateformeBundle:Employeur')->findAll();

        foreach ($employeurs as $employeur){
            $beneficiaires = $employeur->getBeneficiaire();
            foreach ($beneficiaires as $beneficiaire){
                $list[] = $beneficiaire;
            }
        }

        return $this->get('application_plateforme.csv')->getCsvFileEmployeur($list);
    }
}