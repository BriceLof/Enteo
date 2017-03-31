<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\Document;
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

        $file = "Devis_Accompagnement_VAE_".$beneficiaire->getCiviliteConso()."_".$beneficiaire->getNomConso()."_".(new \DateTime('now'))->format('d')."_".(new \DateTime('now'))->format('m')."_".(new \DateTime('now'))->format('Y').'.pdf';
        $filename =  __DIR__."/../../../../web/uploads/beneficiaire/documents/".$beneficiaire->getId()."/".$file;

        if (file_exists($filename)){
            unlink($filename);
        }

        if ($documents = $beneficiaire->getDocuments()){
            foreach ($documents as $documentFile){
                if($documentFile->getPath() == $file){
                    $document = $documentFile;
                    $document->setBeneficiaire($beneficiaire);
                    $document->setPath($file);
                    $document->setDescription($file);
                    break;
                }
            }
        }else{
            $document = new Document();
            $document->setBeneficiaire($beneficiaire);
            $document->setPath($file);
            $document->setDescription($file);
        }


        $em->persist($document);
        $em->flush();

        $this->get('knp_snappy.pdf')->generateFromHtml($html,$filename);

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
                'Content-Disposition'   => 'inline; filename="proposition_'.$beneficiaire->getPrenomConso().'_'.$beneficiaire->getNomConso().'.pdf"',
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
