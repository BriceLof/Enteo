<?php

namespace Application\UsersBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\UsersBundle\Entity\Facturation;
use Application\UsersBundle\Form\FacturationType;


/**
 * Document controller.
 *
 */
class FacturationController extends Controller
{

    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);
        $facturation = new Facturation();

        $form = $this->createForm(FacturationType::class, $facturation, array(
            'action' => $this->generateUrl('application_users_new_facturation', array(
                'id' => $id
            )),
            'method' => 'POST',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $facturation = $form->getData();
            $facturation->setConsultant($consultant);

            $file = $facturation->getAttestationUrssaf();
            if (!is_null($file)) {
                $name = strtolower("attestation_ursaff_" . $facturation->getConsultant()->getNom() . "_" . $facturation->getConsultant()->getPrenom() . ".pdf");
                $fileName = $this->get('app.file_uploader')->uploadAvatar($file, 'uploads/consultant/' . $facturation->getConsultant()->getId(), $name, $name);
                $facturation->setAttestationUrssaf($fileName);
            }

            $em->persist($facturation);
            $em->flush();

            return $this->redirect($this->generateUrl('my_account'));
        }

        return $this->render('ApplicationUsersBundle:Facturation:new.html.twig', array(
            'form' => $form->createView(),
            'facturation' => null
        ));
    }

    public function editAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $facturation = $em->getRepository('ApplicationUsersBundle:Facturation')->find($id);

        $form = $this->createForm(FacturationType::class, $facturation, array(
            'action' => $this->generateUrl('application_users_edit_facturation', array(
                'id' => $id
            )),
            'method' => 'POST',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $facturation = $form->getData();

            $file = $facturation->getAttestationUrssaf();
            if (!is_null($file)) {
                $name = strtolower("attestation_ursaff_" . $facturation->getConsultant()->getNom() . "_" . $facturation->getConsultant()->getPrenom() . ".pdf");
                $fileName = $this->get('app.file_uploader')->uploadAvatar($file, 'uploads/consultant/' . $facturation->getConsultant()->getId(), $name, $name);
                $facturation->setAttestationUrssaf($fileName);
            }

            $em->persist($facturation);
            $em->flush();

            return $this->redirect($this->generateUrl('my_account'));
        }

        return $this->render('ApplicationUsersBundle:Facturation:edit.html.twig', array(
            'form' => $form->createView(),
            'facturation' => $facturation
        ));
    }
}