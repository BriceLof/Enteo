<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * User controller.
 *
 * @Route("users")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UsersController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="users_index")
     * @Method("GET")
     */
    public function indexAction($typeUser)
    {
        //var_dump($typeUser);
        $em = $this->getDoctrine()->getManager();
        if(is_null($typeUser))
            $users = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        else
            $users = $em->getRepository('ApplicationUsersBundle:Users')->findAll();
        
       
        return $this->render('ApplicationUsersBundle:Users:index.html.twig', array(
            'users' => $users,
            
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="users_new")
     * @Method({"GET", "POST"})
     */
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
    
    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="users_show")
     * @Method("GET")
     */
    public function showAction(Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('users/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function editAction(Request $request, Users $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('Application\UsersBundle\Form\UsersType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Vos modifications ont été enregistrées');
            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }
        
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Simple example
        $breadcrumbs->addItem("Home", $this->get("router")->generate("user"));
        $breadcrumbs->addItem("Utilisateur", $this->get("router")->generate("user"));
        $breadcrumbs->addItem("Modification");  
        // Example with parameter injected into translation "user.profile"
    
        return $this->render('ApplicationUsersBundle:Users:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
