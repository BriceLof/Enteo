<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Application\PlateformeBundle\Entity\Facture;
use Application\PlateformeBundle\Entity\HistoriquePaiementFacture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Form\FactureType;
use Application\PlateformeBundle\Form\FactureFermetureType;
use Application\PlateformeBundle\Form\FacturePaiementType;
use Application\PlateformeBundle\Form\FactureFiltreType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class FactureController extends Controller
{

    public function listAction(Request $request, $page)
    {
        $session = $request->getSession();
        if($session->has('facture_search'))
            $session->remove('facture_search');

        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        $em = $this->getDoctrine()->getManager();
        if($this->container->get("kernel")->getEnvironment() == 'dev')
            $nbPerPage = 20;
        else
            $nbPerPage = 100;
        $factures = $em->getRepository('ApplicationPlateformeBundle:Facture')->getAllFacture($page, $nbPerPage);
        $nbPages = ceil(count($factures) / $nbPerPage);

        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('ApplicationPlateformeBundle:Facture:list.html.twig', array(
            'factures' => $factures,
            'nbPages' => $nbPages,
            'page' => $page,
        ));
    }

    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        $accompagnement = $beneficiaire->getAccompagnement();
        $montantTotalAccompagnement = 0;
        if($accompagnement != null){
            foreach ($accompagnement->getFinanceur() as $financeur){
                $montantTotalAccompagnement += $financeur->getMontant();
            }
        }

        $lastFacture = $em->getRepository('ApplicationPlateformeBundle:Facture')->findOneBy(
            array(),
            array('id' => 'desc')
        );

        if(!is_null($lastFacture)){
            $numFactuExplode = explode("-",$lastFacture->getNumero());
            $valeur = (int) $numFactuExplode[0]+1;
            $lastNumFactu = $valeur."-".date('Y');
        }
        else   $lastNumFactu = "1-".date('Y');

        $facture = new Facture();
        $form = $this->get('form.factory')->create(FactureType::class, $facture);
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $facture = $form->getData();
            $facture->setMontant(str_replace(',', '.', $facture->getMontant()));
            $facture->setBeneficiaire($beneficiaire);

            $nomFinanceur = $form->get('nom_financeur')->getData();
            $rueFinanceur = $form->get('rue_financeur')->getData();
            $cpVilleFinanceur = $form->get('code_postal_financeur')->getData();
            $facture->setFinanceur($nomFinanceur.' | '.$rueFinanceur.' | '.$cpVilleFinanceur);

            $facture->setNumero($lastNumFactu);
            $facture->setStatut('sent');
            $em->persist($facture);

            $accompagnement->setDateDebut($facture->getDateDebutAccompagnement());
            $accompagnement->setDateFin($facture->getDateFinAccompagnement());
            $em->persist($accompagnement);

            $historique = new Historique();
            $historique->setHeuredebut(new \DateTime('now'));
            $historique->setHeurefin(new \DateTime('now'));
            $historique->setDateFin(new \DateTime('now'));
            $historique->setSummary("");
            $historique->setTypeRdv("");
            $historique->setBeneficiaire($beneficiaire);
            $historique->setDescription("Facture générée");
            $historique->setEventId("0");
            $historique->setUser($this->getUser());
            $em->persist($historique);
            
            $suiviAdministratifTypePaiement = new SuiviAdministratif();
            $typePaiement = $form->get('type_paiement')->getData();
            if($typePaiement == 'partiel'){
                $detailStatutRepo = $em->getRepository('ApplicationPlateformeBundle:DetailStatut')->findOneByDetail("Facture acompte");
            }
            elseif($typePaiement == 'total'){
                $detailStatutRepo = $em->getRepository('ApplicationPlateformeBundle:DetailStatut')->findOneByDetail("Facture totale");
            }
            $suiviAdministratifTypePaiement->setBeneficiaire($beneficiaire);
            $suiviAdministratifTypePaiement->setStatut($detailStatutRepo->getStatut());
            $suiviAdministratifTypePaiement->setDetailStatut($detailStatutRepo);
            $em->persist($suiviAdministratifTypePaiement);

            $suiviAdministratifFactureOpen = new SuiviAdministratif();
            $suiviAdministratifFactureOpen->setBeneficiaire($beneficiaire);
            $suiviAdministratifFactureOpen->setInfo("Facture ouverte N° ".$lastNumFactu);
            $em->persist($suiviAdministratifFactureOpen);

            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Facture correctement généré');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'id' => $beneficiaire->getId(),
            )));
        }
        return $this->render('ApplicationPlateformeBundle:Facture:create.html.twig', array(
            'form' => $form->createView(),
            'beneficiaire' => $beneficiaire,
            'montantTotalAccompagnement' => $montantTotalAccompagnement
        ));
    }

    public function updateAction(Request $request, $numero)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('ApplicationPlateformeBundle:Facture')->findOneByNumero($numero);
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($facture->getBeneficiaire()->getId());
        $accompagnement = $beneficiaire->getAccompagnement();
        $montantTotalAccompagnement = 0;
        if($accompagnement != null){
            foreach ($accompagnement->getFinanceur() as $financeur){
                $montantTotalAccompagnement += $financeur->getMontant();
            }
        }
        $loopIndex = $request->get('loopIndex');

        $financeur = $facture->getFinanceur();
        $explodeFinanceur = explode(' | ', $financeur);
        $nomFinanceur = $explodeFinanceur[0];
        $rueFinanceur =  $explodeFinanceur[1];
        $cpVilleFinanceur =  $explodeFinanceur[2];

        $formUpdate = $this->get('form.factory')->create(FactureType::class, $facture);
        $formFermeture = $this->get('form.factory')->create(FactureFermetureType::class, $facture);

        if($request->isMethod('POST')){

            // Traitement formulaire update facture ou celui de la fermeture d'une facture
            if($formUpdate->handleRequest($request)->isValid() || $formFermeture->handleRequest($request)->isValid()){

                // check lequel des formulaires a été submit
                if($request->request->has($formUpdate->getName())){
                    $facture = $formUpdate->getData();
                    $facture->setMontant(str_replace(',', '.', $facture->getMontant()));
                    $nomFinanceur = $formUpdate->get('nom_financeur')->getData();
                    $rueFinanceur = $formUpdate->get('rue_financeur')->getData();
                    $cpVilleFinanceur = $formUpdate->get('code_postal_financeur')->getData();
                    $facture->setFinanceur($nomFinanceur.' | '.$rueFinanceur.' | '.$cpVilleFinanceur);
                    $em->persist($facture);

                    $accompagnement->setDateDebut($facture->getDateDebutAccompagnement());
                    $accompagnement->setDateFin($facture->getDateFinAccompagnement());
                    $em->persist($accompagnement);
                }
                elseif($request->request->has($formFermeture->getName())){
                    $facture->setOuvert(false);
                    $em->persist($facture);
                }

                if($request->request->has($formFermeture->getName())){
                    $suiviAdministratif = new SuiviAdministratif();
                    $suiviAdministratif->setBeneficiaire($beneficiaire);
                    $suiviAdministratif->setInfo("Facture fermée N° ".$facture->getNumero());
                    $em->persist($suiviAdministratif);
                }

                $em->flush();

                if($request->request->has($formUpdate->getName())) $this->get('session')->getFlashBag()->add('info', 'Facture correctement modifiée');
                elseif($request->request->has($formFermeture->getName())) $this->get('session')->getFlashBag()->add('info', 'Facture correctement fermée');

                return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )));
            }
        }

        return $this->render('ApplicationPlateformeBundle:Facture:update.html.twig', array(
            'form' => $formUpdate->createView(),
            'formFactureFermer' => $formFermeture->createView(),
            'facture' => $facture,
            'beneficiaire' => $beneficiaire,
            'loopIndex' => $loopIndex,
            'nomFinanceur' => $nomFinanceur,
            'rueFinanceur' =>$rueFinanceur,
            'cpVilleFinanceur' =>$cpVilleFinanceur,
            'montantTotalAccompagnement' => $montantTotalAccompagnement
        ));
    }

    public function showAction($numero)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('ApplicationPlateformeBundle:Facture')->findOneByNumero($numero);

        $financeur = $facture->getFinanceur();
        $financeurExplode = explode(' | ', $financeur);
        $nomFinanceur = $financeurExplode[0];
        $rueFinanceur = $financeurExplode[1];
        $cpVilleFinanceur = $financeurExplode[2];

        $html = $this->renderView('ApplicationPlateformeBundle:Pdf:facture.html.twig', array(
            'facture' => $facture,
            'nomFinanceur' => $nomFinanceur,
            'rueFinanceur' => $rueFinanceur,
            'cpVilleFinanceur' => $cpVilleFinanceur,
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array(
                'enable-javascript' => true,
                'encoding' => 'utf-8',
                'lowquality' => false,
                'javascript-delay' => 5000,
                'images' => true,
            )),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="facture_'.$facture->getNumero().'.pdf"',
            )
        );

    }

    public function paiementAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $facture = $em->getRepository('ApplicationPlateformeBundle:Facture')->find($id);

        $form = $this->get('form.factory')->create(FacturePaiementType::class, $facture);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $facture = $form->getData();
            $montantRegler = $facture->getMontantPayer();
            $montantReglerTotal = ( $form->get("montantFactureDejaReglerHidden")->getData() + ( $facture->getMontantPayer() ) );
            $facture->setMontantPayer($montantReglerTotal);
            $em->persist($facture);

            $historique = new HistoriquePaiementFacture();
            $historique->setFacture($facture);
            $historique->setStatut($facture->getStatut());
            $historique->setMontant($montantRegler);
            $historique->setModePaiement($facture->getModePaiement());
            $datePaiement = new \DateTime($form->get("date_paiement")->getData());
            $historique->setDatePaiement($datePaiement);
            $historique->setCommentaire($facture->getCommentaire());
            $em->persist($historique);

            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Facture mise à jour');

            return $this->redirect($this->generateUrl('application_list_facture_home', array(

            )));
        }
        return $this->render('ApplicationPlateformeBundle:Facture:paiement.html.twig', array(
            'form' => $form->createView(),
            'facture' => $facture
        ));
    }

    public function searchAction(Request $request)
    {
        $session = $request->getSession();

        if(!$session->has('facture_search'))
            $session->set('facture_search', array());

        $recherche = $session->get('facture_search');

        $facture = new Facture();

        $form = $this->createForm(FactureFiltreType::class, $facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $statut = $form->get('statut')->getData();
            if($statut != '' && !is_null($statut))
                $recherche['statut'] = $statut;
            elseif(is_null($statut))
                unset($recherche['statut']);

            $dateDebutAccompagnement = $form->get('date_debut_accompagnement')->getData();
            if($dateDebutAccompagnement != '' && !is_null($dateDebutAccompagnement))
                $recherche['date_debut_accompagnement'] = $dateDebutAccompagnement;
            elseif(is_null($dateDebutAccompagnement))
                unset($recherche['date_debut_accompagnement']);

            $dateFinAccompagnement = $form->get('date_fin_accompagnement')->getData();
            if($dateFinAccompagnement != '' && !is_null($dateFinAccompagnement))
                $recherche['date_fin_accompagnement'] = $dateFinAccompagnement;
            elseif(is_null($dateFinAccompagnement))
                unset($recherche['date_fin_accompagnement']);

            $dateDebutAccompagnementStart = $form->get('date_debut_accompagnement_start')->getData();
            if($dateDebutAccompagnementStart != '' && !is_null($dateDebutAccompagnementStart))
                $recherche['date_debut_accompagnement_start'] = $dateDebutAccompagnementStart;
            elseif(is_null($dateDebutAccompagnementStart))
                unset($recherche['date_debut_accompagnement_start']);

            $dateDebutAccompagnementEnd = $form->get('date_debut_accompagnement_end')->getData();
            if($dateDebutAccompagnementEnd != '' && !is_null($dateDebutAccompagnementEnd))
                $recherche['date_debut_accompagnement_end'] = $dateDebutAccompagnementEnd;
            elseif(is_null($dateDebutAccompagnementEnd))
                unset($recherche['date_debut_accompagnement_end']);

            $dateFinAccompagnementStart = $form->get('date_fin_accompagnement_start')->getData();
            if($dateFinAccompagnementStart != '' && !is_null($dateFinAccompagnementStart))
                $recherche['date_fin_accompagnement_start'] = $dateFinAccompagnementStart;
            elseif(is_null($dateFinAccompagnementStart))
                unset($recherche['date_fin_accompagnement_start']);

            $dateFinAccompagnementEnd = $form->get('date_fin_accompagnement_end')->getData();
            if($dateFinAccompagnementEnd != '' && !is_null($dateFinAccompagnementEnd))
                $recherche['date_fin_accompagnement_end'] = $dateFinAccompagnementEnd;
            elseif(is_null($dateFinAccompagnementEnd))
                unset($recherche['date_fin_accompagnement_end']);

            $dateFactureStart = $form->get('date_facture_start')->getData();
            if($dateFactureStart != '' && !is_null($dateFactureStart))
                $recherche['date_facture_start'] = $dateFactureStart;
            elseif(is_null($dateFactureStart))
                unset($recherche['date_facture_start']);

            $dateFactureEnd = $form->get('date_facture_end')->getData();
            if($dateFactureEnd != '' && !is_null($dateFactureEnd))
                $recherche['date_facture_end'] = $dateFactureEnd;
            elseif(is_null($dateFactureEnd))
                unset($recherche['date_facture_end']);

            $consultant = $form->get('consultant')->getData();
            if($consultant != '' && !is_null($consultant))
                $recherche['consultant'] = $consultant;
            elseif(is_null($consultant))
                unset($recherche['consultant']);

            $financeur = $form->get('financeur')->getData();
            if($financeur != '' && !is_null($financeur))
                $recherche['financeur'] = $financeur;
            elseif(is_null($financeur))
                unset($recherche['financeur']);

            $villeMer = $form->get('ville_mer')->getData();
            if($villeMer != '' && !is_null($villeMer))
                $recherche['ville_mer'] = $villeMer;
            elseif(is_null($villeMer))
                unset($recherche['ville_mer']);

            $numFactu = '';
            $numeroFacture = $form->get('numero_facture')->getData();
            $anneeNumeroFacture = $form->get('annee_numero_facture')->getData();
            if(($numeroFacture != '' && !is_null($numeroFacture)) || ($anneeNumeroFacture != '' && !is_null($anneeNumeroFacture))){
                $recherche['numero_facture'] = $numeroFacture.$anneeNumeroFacture;
                $numFactu = $numeroFacture.$anneeNumeroFacture;
            }
            elseif(is_null($numeroFacture) && is_null($anneeNumeroFacture)){
                unset($recherche['numero_facture']);
            }

            $beneficiaire = null;
            $beneficiaireGet = $request->get('beneficiaire_ajax');
            if($beneficiaireGet != '' && !is_null($beneficiaireGet)){
                $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->findOneById($beneficiaireGet);
                $recherche['beneficiaire'] = $beneficiaire;
            }
            elseif(is_null($beneficiaireGet))
                unset($recherche['beneficiaire']);

            $session->set('facture_search', $recherche);

            $factures = $em->getRepository('ApplicationPlateformeBundle:Facture')->search($statut, $dateDebutAccompagnement, $dateFinAccompagnement, $dateDebutAccompagnementStart, $dateDebutAccompagnementEnd, $dateFinAccompagnementStart, $dateFinAccompagnementEnd, $dateFactureStart, $dateFactureEnd, $consultant, $beneficiaire, $numFactu, $financeur, $villeMer);
            

            return $this->render('ApplicationPlateformeBundle:Facture:search.html.twig', array(
                'factures' => $factures,
                'form' => $form->createView()
            ));
            //return $this->redirectToRoute('task_success');
        }

        return $this->render('ApplicationPlateformeBundle:Facture:filtreForm.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function searchAjaxAction($string)
    {
        $em = $this->getDoctrine()->getManager();
        $nom = $string;
        $beneficiaires = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->searchBeneficiaireByNom($nom);

        $arrayBeneciaires = array();
        foreach($beneficiaires as $beneficiaire){
            $arrayBeneciaires[] = array(
                'id' => $beneficiaire->getId(),
                'nom' => $beneficiaire->getNomConso(),
                'prenom' => $beneficiaire->getPrenomConso(),
            );
        }

        return new JsonResponse(json_encode($arrayBeneciaires));

    }
}
