<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 * @Security("has_role('ROLE_ADMIN')")
 */
class UsersController extends Controller
{
    
    public function indexAction($typeUser = null)
    {
        //var_dump($typeUser);exit;
        $em = $this->getDoctrine()->getManager();
        if(is_null($typeUser))
            $users = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
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
        //var_dump($user);exit;
        return $this->render('ApplicationUsersBundle:Users:show.html.twig', array(
            'user' => $user,
            
        ));
    }

    public function editAction(Request $request, Users $user)
    {
        //var_dump($user);exit;
        $editForm = $this->get("form.factory")->create(UsersType::class, $user);
       /* $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('Application\UsersBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Vos modifications ont été enregistrées');
            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }
        
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

    public function deleteAction(Request $request, Users $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush($user);
            
            return $this->redirectToRoute('user');
        }
        
        return $this->render('ApplicationUsersBundle:Users:delete.html.twig', array(
            'form' => $form->createView(),
        ));
        
    }

    private function createDeleteForm(Users $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
