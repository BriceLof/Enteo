<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\EspaceDocumentaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Document controller.
 *
 */
class DocumentController extends Controller
{
    /**
     * Creates a new Document entity.
     *
     */
    public function newAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);


        $document_form = $this->createDocumentEditForm($beneficiaire);
        $document_form->add('submit',  SubmitType::class, array(
            'label' => 'upload'
        ));
        $document_form->handleRequest($request);


        if ($document_form->isSubmitted()) {
            //var_dump($beneficiaire->getDocuments());die;
            if($document_form->isValid()){
                $em->persist($beneficiaire);
                foreach ($beneficiaire->getDocuments() as $document){
                    $document->setBeneficiaire($beneficiaire);
                    $em->persist($document);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Document bien enregistré');
                return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'beneficiaire' => $beneficiaire,
                    'id' => $beneficiaire->getId(),

                )));
            }else{

                foreach ($document_form->getIterator() as $key => $child) {
                    foreach ($child->getErrors() as $error) {
                        $errors[$key] = $error->getMessage();
                    }
                }

                $this->get('session')->getFlashBag()->add('info', 'erreur sur l\'enregistrement du document');


                //var_dump($form->getData());die;

                return $this->redirectToRoute('application_new_document', array(
                    'erreur' => $document_form->getData(),
                    'beneficiaire' => $beneficiaire,
                    'id' => $beneficiaire->getId(),
                ));
            }
        }


        return $this->render('ApplicationPlateformeBundle:Document:new.html.twig', array(
            'beneficiaire' => $beneficiaire,
            'document_form' => $document_form->createView(),
        ));
    }

    /**
     * Creates a form to edit document in beneficiaire entity.
     *
     * @param Beneficiaire $beneficiaire The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDocumentEditForm(Beneficiaire $beneficiaire)
    {
        $form = $this->createForm(EspaceDocumentaireType::class, $beneficiaire);
        return $form;
    }
}