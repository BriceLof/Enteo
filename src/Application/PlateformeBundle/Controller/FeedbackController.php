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

        $from = array("email_adress" => "audrey.azoulay@entheor.com", "alias" => "Audrey");
        $to = array("email_adress" => "aurelie@gmail.com", "alias" => "brice");
        $subject = "test dkim";
        $cc =  array("email_adress" => "brice.lof@gmail.com", "alias" => "brice");
        $bcc = array(
            "support.informatique@entheor.com" => "Support",
            "b.lof@iciformation.fr" => "brice"
           );

        $this->get('application_plateforme.mail')->sendMessage($from, $to, null, $cc, $bcc, $subject, "Test brice mail");
        //$this->get('application_plateforme.statut.cron.cron_beneficiaire')->beneficiairePreviousWeekNoContact();

        
        $feedback = new Feedback();
         
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $feedback->setType($type);
            $feedback->setUser($this->getUser());
            
            $file = $feedback->getImage();
            if(!is_null($file)){
                $filename = $this->get('app.file_uploader')->upload($file, 'uploads/feedback/img');
                $feedback->setImage($filename);
            }
            
            $em->persist($feedback);
            $em->flush();
            
            //envoi mail aux admin
            $this->get('application_plateforme.mail')->mailFeedback($feedback);
            
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

