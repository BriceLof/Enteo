<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Image;
use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Form\ImageType;
use Application\UsersBundle\Form\PhotoDeProfileType;
use Application\UsersBundle\Form\PhotoProfileType;
use Application\UsersBundle\Form\StatutConsultantType;
use Application\UsersBundle\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class UsersController extends Controller
{
    
    public function indexAction($typeUser = null, Request $request)
    {
        $etat = $request->query->get("actif");
        $em = $this->getDoctrine()->getManager();
        if(is_null($typeUser)){
            if($etat == 'true')
                $users = $em->getRepository('ApplicationUsersBundle:Users')->findBy(array('enabled' => 1), array("nom" => "ASC"));
            elseif($etat == 'false')
                $users = $em->getRepository('ApplicationUsersBundle:Users')->findBy(array('enabled' => 0), array("nom" => "ASC"));
            else
                $users = $em->getRepository('ApplicationUsersBundle:Users')->findBy(array(), array("nom" => "ASC"));  
        }
        else{
            if($etat == 'true')
                $users = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser($typeUser, 1);
            elseif($etat == 'false')
                $users = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser($typeUser, 0);
            else
                $users = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser($typeUser);
        }
            
        return $this->render('ApplicationUsersBundle:Users:index.html.twig', array(
            'users' => $users,  
        ));
    }

    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('Application\UsersBundle\Form\UsersType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush($user);
            $_SESSION['calendrierId'] = $user->getCalendrierid(); // Id calendrier
            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }
        $_SESSION['calendrierId'] = $user->getCalendrierid(); // Id calendrier
        return $this->render('users/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
    
    public function showAction(Request $request,Users $user)
    {

        $em = $em = $this->getDoctrine()->getManager();
        // mon service qui récupère le type d'utilisateur
        $typeUser = $this->container->get('application_users.getTypeUser');
        $statut = $user->getStatut();
        $formStatut = $this->createForm(StatutConsultantType::class, $statut, array(
            'application_users_statut' => $this->get('application.users.statut')
        ));
        $formStatut->add('submit', SubmitType::class, array('label' => 'Valider'));

        $formStatut->handleRequest($request);
        if ($request->isMethod('POST')) {
            $statut = $formStatut->getData();
            $user->setStatut($statut);
            $em->persist($user);
            $em->flush();
        }

        return $this->render('ApplicationUsersBundle:Users:show.html.twig', array(
            'user' => $user,
            'typeUser' => $typeUser->typeUser($user),
            'formStatut' => $formStatut->createView()
        ));
    }

    public function editAction(Request $request, Users $user)
    {
        // supression du salt dans le password avant de le mettre dans le champs du form 
        $user->setPassword(substr($user->getPassword(), 0,-45));
        
        //$user->setRoles(array("ROLE_CONSULTANT"));
        //var_dump($user->getRoles());
        
        $editForm = $this->get("form.factory")->create(UsersType::class, $user);
        if(!is_null($user->getVille()))
        {
            $editForm->get('departement')->setData(substr($user->getVille()->getCp(),0,2));
            $editForm->get('codePostalHidden')->setData($user->getVille()->getCp());
            $editForm->get('idVilleHidden')->setData($user->getVille()->getId()); 
        }
        

        $typeUser = $this->container->get('application_users.getTypeUser');
        $editForm->get('typeUserHidden')->setData($typeUser->typeUser($this->getUser()));
        
        if ($request->isMethod('POST') && $editForm->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('user_show', array('id' => $user->getId()));  
        }
        /*
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Simple example
        $breadcrumbs->addItem("Home", $this->get("router")->generate("user_type"));
        $breadcrumbs->addItem("Utilisateur", $this->get("router")->generate("user_type"));
        $breadcrumbs->addItem("Modification");  
        // Example with parameter injected into translation "user.profile"
    */
        return $this->render('ApplicationUsersBundle:Users:edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView()
        ));
    }
    
    // Désactiver un utilisateur (pas de suppression car bénéficiaire est lié à un consultant) 
    public function turnOffAction(Request $request, Users $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($user->getId());exit;
            $em = $this->getDoctrine()->getManager();
            //$em->remove($user);
            if($user->isEnabled() == true){
                $user->setEnabled(0);
                $em->flush($user);
                $request->getSession()->getFlashBag()->add('info', 'Utilisateur désactivé avec succès');
            }
            else{
                $user->setEnabled(1);
                $em->flush($user);
                $request->getSession()->getFlashBag()->add('info', 'Utilisateur activé avec succès');
            }

            return $this->redirectToRoute('user_type');
        }

        return $this->render('ApplicationUsersBundle:Users:turnOff.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    private function createDeleteForm(Users $user)
    {
        if($user->isEnabled() == true){
            $btn = "Désactiver";
            $bootstrapClass = "btn-danger";
        }
        else{
            $btn = "Activer";
            $bootstrapClass = "btn-success";
        }

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_turn_off', array('id' => $user->getId())))
            ->add("annuler", \Symfony\Component\Form\Extension\Core\Type\ButtonType::class, array(
                'label' => "Annuler",
                'attr' => array('class' => 'btn btn-default', "data-dismiss" => "modal")
            ))
            ->add("delete", \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array(
                'label' => $btn,
                'attr' => array('class' => 'btn '.$bootstrapClass)
            ))
            ->getForm()
            ;
    }
    
    public function myAccountAction(Request $request)
    {
        $myself = $this->getUser();
        $form = $this->createForm(ImageType::class, $myself);
        $form->add('submit', SubmitType::class, array('label' => 'Valider'));
        $typeUser = $this->container->get('application_users.getTypeUser');
        $lastPicture = $myself->getImage();
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $file = $myself->getImage();

            if(!is_null($file)){
                $fileName = $this->get('app.file_uploader')->uploadAvatar($file, 'uploads/profile/'.$myself->getId(), $lastPicture);
                $myself->setImage($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($myself);
            $em->flush();
        }
        return $this->render('ApplicationUsersBundle:Users:account.html.twig', array(
            'form' => $form->createView(),
            'user' => $myself, 
            'typeUser' => $typeUser->typeUser($myself)
        ));
    }
    
    public function myAccountEditAction(Request $request)
    {
        $user = $this->getUser();
        // supression du salt dans le password avant de le mettre dans le champs du form 
        $user->setPassword(substr($user->getPassword(), 0,-45));
        //$user->setRoles(array("ROLE_CONSULTANT"));
        
        $editForm = $this->get("form.factory")->create(UsersType::class, $user);
        $editForm->get('departement')->setData(substr($user->getVille()->getCp(),0,2));
        $editForm->get('codePostalHidden')->setData($user->getVille()->getCp());
        $editForm->get('idVilleHidden')->setData($user->getVille()->getId());

        $typeUser = $this->container->get('application_users.getTypeUser');
        $editForm->get('typeUserHidden')->setData($typeUser->typeUser($this->getUser()));
        
        if ($request->isMethod('POST') && $editForm->handleRequest($request)->isValid()) {
//        	$avatarFile = $user->getAvatar();
		 	//$fileUploader = $this->container->get('app.file_uploader');
			//$fileUploader->upload($avatarFile, "uploads/avatars");
			
			// Generate a unique name for the file before saving it
//            $fileName = md5(uniqid()).'.'.$avatarFile->guessExtension();
//
//            // Move the file to the directory where brochures are stored
//            $avatarFile->move(
//                $this->getParameter('avatars_directory'),
//                $fileName
//            );
//			$user->setAvatar($fileName);
			
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Votre compte a été modifié avec succès');
            return $this->redirectToRoute('my_account');  
        }
       
        return $this->render('ApplicationUsersBundle:Users:edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView()
        ));
    }

    public function ajaxGetUserAction(Request $request, $id){
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('ApplicationUsersBundle:Users')->find($id);
            $consultant[] = array(
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'calendrieruri' => $user->getCalendrieruri()
            );
            $response = new JsonResponse();
            return $response->setData(array('data' => $consultant));
        }else{
            throw new \Exception('erreur');
        }
    }

    public function getAllCommercialAction(){
        $em = $this->getDoctrine()->getManager();
        $commercials = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser('ROLE_COMMERCIAL', true);

        return $this->render('ApplicationUsersBundle:Users:listCommercial.html.twig', array(
            'commercials' => $commercials,
        ));
    }
}
