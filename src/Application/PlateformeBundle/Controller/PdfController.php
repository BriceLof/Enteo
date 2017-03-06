<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\BeneficiaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * Announce controller.
 *
 */
class PdfController extends Controller
{
    public function newAction($id){
        set_time_limit(0);
        ini_set('memory_limit','256M');

        $em = $this->getDoctrine()->getManager();

        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        $accompagnement = $beneficiaire->getAccompagnement();
        $montantTotal = 0;
        if($accompagnement != null){
            foreach ($accompagnement->getFinanceur() as $financeur){
                $montantTotal += $financeur->getMontant();
            }
        }

        $heure = (int)($accompagnement->getHeure()/3);
        $reste = $accompagnement->getHeure()%3;


        $html = $this->renderView('ApplicationPlateformeBundle:Pdf:ficheBeneficiaire.html.twig', array(
            'id' => $beneficiaire->getId(),
            'beneficiaire' => $beneficiaire,
            'montantTotal' => $montantTotal,
            'heure' => $heure,
            'reste' => $reste,
        ));

        $this->get('knp_snappy.pdf')->getInternalGenerator()->setTimeout(300);

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
                'Content-Disposition'   => 'attachment; filename="proposition_'.$beneficiaire->getPrenomConso().'_'.$beneficiaire->getNomConso().'.pdf"',
            )
        );
    }


    /**
     * cette fonction a juste pour but de faire des tests pour l'affichage des pdf
     * ça évite de regenerer le pdf a chaque fois, il suffit ici de changer
     * la vue de retour de cette fonction pour tester votre rendu de pdf
     *
     * @param $id
     * @return Response
     */
    public function testAction($id){
        $em = $this->getDoctrine()->getManager();

        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
        $accompagnement = $beneficiaire->getAccompagnement();
        $montantTotal = 0;
        if($accompagnement != null){
            foreach ($accompagnement->getFinanceur() as $financeur){
                $montantTotal += $financeur->getMontant();
            }
        }

        $heure = (int)($accompagnement->getHeure()/3);
        $reste = $accompagnement->getHeure()%3;

        return $this->render('ApplicationPlateformeBundle:Pdf:ficheBeneficiaire.html.twig', array(
            'beneficiaire'      => $beneficiaire,
            'montantTotal' => $montantTotal,
            'heure' => $heure,
            'reste' => $reste,
        ));
    }
}
