<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Form\EspaceDocumentaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $bene = new Beneficiaire();

        $document_form = $this->createDocumentEditForm($bene);
        $document_form->add('submit',  SubmitType::class, array(
            'label' => 'Upload', 
            'attr' => array('class' => 'submit_upload_doc')
        ));
        $document_form->handleRequest($request);

        if ($document_form->isSubmitted()) {
            //var_dump($beneficiaire->getDocuments());die;
            if($document_form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);
                $em->persist($beneficiaire);
                foreach ($bene->getDocuments() as $document){
                    $document->setBeneficiaire($beneficiaire);
                    $em->persist($document);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Document enregistré avec succès');
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
                    'beneficiaire' => $bene,
                    'id' => $bene->getId(),
                ));
            }
        }


        return $this->render('ApplicationPlateformeBundle:Document:new.html.twig', array(
            'beneficiaire' => $bene,
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
    public function createDocumentEditForm(Beneficiaire $beneficiaire)
    {
        $form = $this->createForm(EspaceDocumentaireType::class, $beneficiaire);
        return $form;
    }

    /**
     * show document in a new target
     */
    public function showAction($path){
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('ApplicationPlateformeBundle:Document')->findOneBy(array(
            'path' => $path,
        ));



        $beneficiaire = $document->getBeneficiaire();

        return $this->render('ApplicationPlateformeBundle:Document:show.html.twig', array(
            'document' => $document,
            'beneficiaire' => $beneficiaire
        ));
    }

    /**
     * Deletes a Document entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('ApplicationPlateformeBundle:Document')->find($id);
        $beneficiaire = $document->getBeneficiaire();

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document.');
        }

        $em->remove($document);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'document supprimé avec succès');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }
	
	 public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $document = $em->getRepository('ApplicationPlateformeBundle:Document')->find($id);
        $beneficiaire = $document->getBeneficiaire();

        if (!$document) {
            throw $this->createNotFoundException('Unable to find Document.');
        }

		$document->setDescription($request->get("document_nom_modifier"));
		$em->persist($document);
		$em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Document modifié avec succès');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }

    public function upAction(Request $request, $id){
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $document = $em->getRepository('ApplicationPlateformeBundle:Document')->find($id);
            $beneficiaire = $document->getBeneficiaire();

            if (!$document) {
                throw $this->createNotFoundException('Unable to find Document.');
            }

            $this->get('application_plateforme.document')->supprimerDocument($beneficiaire, $document);

            $message = "success";
            return new JsonResponse($message);
        }
        else{
            throw new \Exception('erreur');
        }
    }
}