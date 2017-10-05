<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Document;
use Application\UsersBundle\Entity\Mission;
use Application\UsersBundle\Entity\MissionDocument;
use Application\UsersBundle\Form\DocumentType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Document controller.
 *
 */
class MissionController extends Controller
{
    /**
     * @param $idBeneficiaire
     * @param $idConsultant
     */
    public function newAction($idBeneficiaire , $idConsultant)
    {

        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($idBeneficiaire);
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($idConsultant);
        $mission = new Mission();


        $mission->setBeneficiaire($beneficiaire);
        $mission->setConsultant($consultant);
        $mission->setState('new');

        $em->persist($mission);

        //envoyer l'email pour le consultant
        $html = $this->renderView('ApplicationUsersBundle:Mission:newMission.html.twig');

        $data = $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array(
            'enable-javascript' => true,
            'encoding' => 'utf-8',
            'lowquality' => false,
            'javascript-delay' => 5000,
            'images' => true,
        ));

        $attachement = new \Swift_Attachment($data, 'contrat_'.$beneficiaire->getNomConso().'_'.$beneficiaire->getPrenomConso(), 'application/pdf');

        $this->get('application_users.mailer.mail_for_mission')->newMission($beneficiaire,$consultant,$attachement);

    }

    public function acceptedAction($id){
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);
        $mission->setState('accepted');

        $em->persist($mission);
        $em->flush();

//        envoi mail pour administrateur
    }

    public function rejectedAction($id){
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);
        $mission->setState('rejected');

        $em->persist($mission);
        $em->flush();

//        envoi mail pour administrateur
    }

    /**
     * cette fonction permet de confirmer une mission et ajouter le consultant comme le consultant du bénéficiaire
     *
     * @param $id
     */
    public function confirmedAction($id){
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);
        $mission->setState('confirmed');
        $beneficiaire = $mission->getBeneficiaire();
        $beneficiaire->setConsultant($mission->getConsultant());

        $em->persist($beneficiaire);
        $em->persist($mission);
        $em->flush();

//        envoi mail consultant
    }

    public function revokedAction($id){
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);
        $mission->setState('new');

        $em->persist($mission);
        $em->flush();

        //envoi email consultant
    }

    public function stateAction($state, $id){

        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);

        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION') || $this->getUser()->getid() == $id ) {
            $consultant = $mission->getConsultant();
        }elseif ($this->getUser()->getid() == $mission->getConsultant()->getId()) {
            $consultant = $this->getUser();
        }else{
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

        switch ($state) {
            case "accept" :
                $this->forward('ApplicationUsersBundle:Mission:accepted', array(
                    'id' => $id
                ));
                break;
            case "decline" :
                $this->forward('ApplicationUsersBundle:Mission:rejected', array(
                    'id' => $id
                ));
                break;
            case "confirm" :
                $this->forward('ApplicationUsersBundle:Mission:confirmed', array(
                    'id' => $id
                ));
                break;
            case "revoke" :
                $this->forward('ApplicationUsersBundle:Mission:revoked', array(
                    'id' => $id
                ));
                break;
        }

        return $this->redirect($this->generateUrl('application_mission_index', array(
            'id' => $consultant->getId(),
        )));
    }

    public function indexAction($id){
        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION') || $this->getUser()->getid() == $id ) {
        }else{
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

        return $this->render('ApplicationUsersBundle:Mission:index.html.twig', array(
            'missions' => $consultant->getMission()
        ));
    }

    public function adminIndexAction(){
        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION')) {
        }else{
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $missions = $em->getRepository('ApplicationUsersBundle:Mission')->findAll();

        return $this->render('ApplicationUsersBundle:Mission:index.html.twig', array(
            'missions' => $missions
        ));
    }

    public function addDocumentAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);

        $form = $this->createForm(FormType::class, $mission);
        $form->add('submit',  SubmitType::class, array(
            'label' => 'Accepter'
            ))
            ->add('document',FileType::class, array(
                'label' => false,
                'required' => true,
                'data_class' => null,
            ))
        ;

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $file = $mission->getDocument();
            if(!is_null($file)){
                $name = "contrat_".$mission->getBeneficiaire()->getNomConso()."_".$mission->getBeneficiaire()->getPrenomConso();
                $fileName = $this->get('app.file_uploader')->uploadAvatar($file, 'uploads/contrat/'.$mission->getConsultant()->getId(), $name, $name);
                $mission->setDocument($fileName);
            }

            $em->persist($mission);
            $em->flush();

            return $this->forward('ApplicationUsersBundle:Mission:state', array(
                'state' => 'accept',
                'id' => $mission->getId()
            ));
        }

        return $this->render('ApplicationUsersBundle:Mission:addDocument.html.twig', array(
            'form' => $form->createView(),
            'mission' => $mission
        ));
    }
}