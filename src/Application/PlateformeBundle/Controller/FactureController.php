<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Application\PlateformeBundle\Entity\Facture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Form\FactureType;
use Application\PlateformeBundle\Form\FactureFermetureType;
use Application\PlateformeBundle\Form\FacturePaiementType;
use Symfony\Component\HttpFoundation\Response;

class FactureController extends Controller
{

    public function listAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        $em = $this->getDoctrine()->getManager();
        $nbPerPage = 20;
        $factures = $em->getRepository('ApplicationPlateformeBundle:Facture')->getAllFacture($page, $nbPerPage);
        $nbPages = ceil(count($factures) / $nbPerPage);

        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('ApplicationPlateformeBundle:Facture:list.html.twig', array(
            'factures' => $factures,
            'nbPages' => $nbPages,
            'page' => $page
        ));
    }

    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        $accompagnement = $beneficiaire->getAccompagnement();

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
            $facture->setBeneficiaire($beneficiaire);

            $nomFinanceur = $form->get('nom_financeur')->getData();
            $rueFinanceur = $form->get('rue_financeur')->getData();
            $cpVilleFinanceur = $form->get('code_postal_financeur')->getData();
            $facture->setFinanceur($nomFinanceur.' | '.$rueFinanceur.' | '.$cpVilleFinanceur);

            $facture->setDateDebutAccompagnement($accompagnement->getDateDebut());
            $facture->setDateFinAccompagnement($accompagnement->getDateFin());
            $facture->setNumero($lastNumFactu);
            $facture->setStatut('sent');
            $em->persist($facture);

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

            $suiviAdministratif = new SuiviAdministratif();
            $suiviAdministratif->setBeneficiaire($beneficiaire);
            $suiviAdministratif->setInfo("Facture ouverte N° ".$lastNumFactu);
            $em->persist($suiviAdministratif);

            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Facture correctement généré');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'id' => $beneficiaire->getId(),
            )));
        }
        return $this->render('ApplicationPlateformeBundle:Facture:create.html.twig', array(
            'form' => $form->createView(),
            'beneficiaire' => $beneficiaire
        ));
    }

    public function updateAction(Request $request, $numero)
    {
        $em = $this->getDoctrine()->getManager();
        $facture = $em->getRepository('ApplicationPlateformeBundle:Facture')->findOneByNumero($numero);
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($facture->getBeneficiaire()->getId());

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

                    $nomFinanceur = $formUpdate->get('nom_financeur')->getData();
                    $rueFinanceur = $formUpdate->get('rue_financeur')->getData();
                    $cpVilleFinanceur = $formUpdate->get('code_postal_financeur')->getData();
                    $facture->setFinanceur($nomFinanceur.' | '.$rueFinanceur.' | '.$cpVilleFinanceur);
                    $em->persist($facture);
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
            'cpVilleFinanceur' =>$cpVilleFinanceur
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
            $em->persist($facture);
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
}
