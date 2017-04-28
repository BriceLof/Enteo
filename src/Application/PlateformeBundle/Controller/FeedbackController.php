<?php

namespace Application\PlateformeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Application\PlateformeBundle\Entity\Feedback;
use Application\PlateformeBundle\Form\FeedbackType;

class FeedbackController extends Controller
{
    public function postAction(Request $request, $type){
       
        $feedback = new Feedback();
         
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $feedback->setType($type);
            $feedback->setUser($this->getUser());
            
            $file = $feedback->getImage();
            if(!is_null($file)){
                $filename = $this->get('app.file_uploader')->upload($file, 'upload/feedback/img');
                $feedback->setImage($filename);
            }
            
            $em->persist($feedback);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Votre message a été envoyé. Nous le traitons sans délai. L\'Equipe ENTHEOR');
            return $this->redirectToRoute('application_plateforme_homepage');
        }
        return $this->render("ApplicationPlateformeBundle:Feedback:post.html.twig", array(
            'type' => $type, 
            'form' => $form->createView()
        ));
        
    }
    
    public function listAction(){
        $em = $this->getDoctrine()->getManager();

        $feedbacks = $em->getRepository('ApplicationPlateformeBundle:Feedback')->findBy(
            array(),
            array("type" => "ASC", "id" => "DESC")
        );
        return $this->render("ApplicationPlateformeBundle:Feedback:list.html.twig", array(
            'feedbacks' => $feedbacks, 
        )); 
    }
}

