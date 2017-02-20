<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class UsersController extends Controller
{
    
    public function indexAction($typeUser = null)
    {
        $em = $this->getDoctrine()->getManager();
        if(is_null($typeUser))
            $users = $em->getRepository('ApplicationUsersBundle:Users')->findBy(array(), array("id" => "DESC"));
        else
            $users = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser($typeUser);
        
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
    
    public function showAction(Users $user)
    {
        // mon service qui récupère le type d'utilisateur
        $typeUser = $this->container->get('application_users.getTypeUser');

        return $this->render('ApplicationUsersBundle:Users:show.html.twig', array(
            'user' => $user,
            'typeUser' => $typeUser->typeUser($user)
        ));
    }

    public function editAction(Request $request, Users $user)
    {
        // supression du salt dans le password avant de le mettre dans le champs du form 
        $user->setPassword(substr($user->getPassword(), 0,-45));
        
        //$user->setRoles(array("ROLE_CONSULTANT"));
        //var_dump($user->getRoles());
        
        $editForm = $this->get("form.factory")->create(UsersType::class, $user);
        $editForm->get('departement')->setData(substr($user->getVille()->getCp(),0,2));
        $editForm->get('codePostalHidden')->setData($user->getVille()->getCp());
        $editForm->get('idVilleHidden')->setData($user->getVille()->getId());

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
            $user->setEnabled(0);
            $em->flush($user);
            $request->getSession()->getFlashBag()->add('info', 'Utilisateur désactivé avec succès');
            return $this->redirectToRoute('user_type');
        }
        
        return $this->render('ApplicationUsersBundle:Users:turnOff.html.twig', array(
            'form' => $form->createView(), 
        ));  
    }

    private function createDeleteForm(Users $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_turn_off', array('id' => $user->getId())))
            ->add("annuler", \Symfony\Component\Form\Extension\Core\Type\ButtonType::class, array(
                'label' => "Annuler",
                'attr' => array('class' => 'btn btn-default', "data-dismiss" => "modal")
            ))    
            ->add("delete", \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, array(
                'label' => "Désactiver",
                'attr' => array('class' => 'btn btn-danger')
            ))
            ->getForm()
        ;
    }
    
    public function myAccountAction(Request $request)
    {
        $myself = $this->getUser();
        $typeUser = $this->container->get('application_users.getTypeUser');
        return $this->render('ApplicationUsersBundle:Users:account.html.twig', array(
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
        //var_dump($user->getRoles());
        $editForm = $this->get("form.factory")->create(UsersType::class, $user);
        $editForm->get('departement')->setData(substr($user->getVille()->getCp(),0,2));
        $editForm->get('codePostalHidden')->setData($user->getVille()->getCp());
        $editForm->get('idVilleHidden')->setData($user->getVille()->getId());

        $typeUser = $this->container->get('application_users.getTypeUser');
        $editForm->get('typeUserHidden')->setData($typeUser->typeUser($this->getUser()));
        
        if ($request->isMethod('POST') && $editForm->handleRequest($request)->isValid()) {
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
}
