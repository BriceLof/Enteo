<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Document;
use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Form\EspaceDocumentaireType;
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
     * cette fonction permet d'ajouter des documents dans la mon compte
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, $id)
    {

        $user = new Users();

        $document_form = $this->createDocumentEditForm($user);
        $document_form->add('submit', SubmitType::class, array(
            'label' => 'Upload',
            'attr' => array('class' => 'submit_upload_doc')
        ));
        $document_form->handleRequest($request);
        if ($document_form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

            foreach ($user->getUserDocuments() as $userDocument){
                $userDocument->setUser($consultant);
                foreach ($document_form['userDocuments']['b']['documents']['f']['files']->getData() as $file) {
                    if (!is_null($file)){
                        $document = new Document();
                        $document->setFile($file);
                        $document->setUserDocument($userDocument);
                        $userDocument->addDocument($document);
                    }
                }
            }
            $em->persist($userDocument);
            $em->persist($consultant);
            $em->flush();
            $em->detach($user);
            $this->get('session')->getFlashBag()->add('info', 'Document enregistré avec succès');
            return $this->redirect($this->generateUrl('user_show', array(
                'consultant' => $consultant,
                'id' => $consultant->getId(),
            )));
        }


        return $this->render('ApplicationUsersBundle:Document:new.html.twig', array(
            'document_form' => $document_form->createView(),
        ));
    }

    /**
     * Creates a form to edit document in userDocument entity.
     *
     * @param Users $consultant The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDocumentEditForm(Users $consultant)
    {
        $form = $this->createForm(EspaceDocumentaireType::class, $consultant);
        return $form;
    }

    /**
     * show document in a new target
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userDocument = $em->getRepository('ApplicationUsersBundle:UserDocument')->find($id);

        $uploadDir = __DIR__.'/../../../../web/uploads/'.$userDocument->getUser()->getId().'/'.$userDocument->getName();

        $zip = new \ZipArchive();
        $zip_path= $uploadDir.'/download.zip';

        if($zip->open($zip_path) === TRUE){
            $zip->extractTo($uploadDir);
            $zip->close();
        }
        else{
            echo 'echec';
        }

        foreach ($userDocument->getDocuments() as $document){
            if (!preg_match('/pdf$/i',$document->getPath())){
                $html = $this->renderView('ApplicationUsersBundle:Document/pdf:new.html.twig', array(
                    'document' => $document,
                    'userDocument' => $userDocument,
                ));
                $this->get('knp_snappy.pdf')->getInternalGenerator()->setTimeout(300);
                $file = preg_replace('/.[^.]*$/', '', $document->getPath()).'.pdf';

                $filename =  $uploadDir."/".$file;

                if (file_exists($filename)){

                }else{
                    $this->get('knp_snappy.pdf')->generateFromHtml($html,$filename);
                    unlink($uploadDir."/".$document->getPath());
                }

                $document->setPath($file);
            }
        }

        return $this->render('ApplicationUsersBundle:Document:show.html.twig', array(
            'userDocument' => $userDocument,
        ));
    }


}