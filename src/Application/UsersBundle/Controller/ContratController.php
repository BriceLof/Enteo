<?php

namespace Application\UsersBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\UsersBundle\Entity\Contrat;
use Application\UsersBundle\Form\ContratType;


/**
 * Contrat controller.
 *
 */
class ContratController extends Controller
{

    public function newAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

        $contrat = new Contrat();

        $form = $this->createForm(ContratType::class, $contrat, array(
            'action' => $this->generateUrl('application_users_new_contrat', array(
                'id' => $id
            )),
            'method' => 'POST',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Ajouter'));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $contrat = $form->getData();
            $contrat->setConsultant($consultant);

            foreach ($consultant->getContrats() as $c){
                $c->setEnabled(false);
                $em->persist($c);
            }

            $file = $contrat->getFile();
            if (!is_null($file)) {
                $name = strtolower("contrat_cadre_" . $contrat->getConsultant()->getNom() . "_" . $contrat->getConsultant()->getPrenom() . time() . ".pdf");
                $fileName = $this->get('app.file_uploader')->uploadAvatar($file, 'uploads/consultant/' . $contrat->getConsultant()->getId(), $name, $name);
                $contrat->setFile($fileName);
                $contrat->setEnabled(true);
            }

            $em->persist($contrat);
            $em->flush();

            return $this->redirect($this->generateUrl('user_show' , array(
                'id' => $consultant->getId()
            )));
        }

        return $this->render('ApplicationUsersBundle:Contrat:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}