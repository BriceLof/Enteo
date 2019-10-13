<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Mission;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Application\PlateformeBundle\Entity\Historique;
use Application\UsersBundle\Entity\MissionArchive;
use Application\UsersBundle\Entity\MissionDocument;
use Application\UsersBundle\Form\MailingType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mission controller.
 *
 */
class MissionController extends Controller
{
    /**
     * @param $idBeneficiaire
     * @param $idConsultant
     * @param $montant
     * @throws \Exception
     */
    public function newAction($idBeneficiaire, $idConsultant, $montant, $duree)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($idBeneficiaire);
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($idConsultant);
        $facturation = $consultant->getFacturation();

        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->findOneBy(array(
            "beneficiaire" => $beneficiaire
        ));

        //si le bénéficiaire a déjà une mission il faut archivé cette mission
        if (is_null($mission)) {
            $mission = new Mission();
        }

        $mission->setBeneficiaire($beneficiaire);
        $mission->setConsultant($consultant);
        $mission->setState('new');
        $mission->setTarif($montant);
        $mission->setDuree($duree);
        $mission->setDateCreation(new \DateTime('now'));

        $suiviMission = new SuiviAdministratif();
        $suiviMission->setBeneficiaire($beneficiaire);
        $suiviMission->setInfo("Mission Envoyée à " . ucfirst($consultant->getPrenom()) . " " . strtoupper($consultant->getNom()));
        $em->persist($suiviMission);

        //envoyer l'email pour le consultant
        $html = $this->renderView('ApplicationUsersBundle:Mission:newMission.html.twig', array(
            'beneficiaire' => $beneficiaire,
            'consultant' => $consultant,
            'facturation' => $facturation,
            'mission' => $mission
        ));

        $data = $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array(
            'enable-javascript' => true,
            'encoding' => 'utf-8',
            'lowquality' => false,
            'javascript-delay' => 5000,
            'images' => true,
        ));

        $name = $this->get('application_plateforme.text')->slugify(strtolower("contrat_" . $mission->getBeneficiaire()->getNomConso() . "_" . $mission->getBeneficiaire()->getPrenomConso())). ".pdf";
        $fileName = 'uploads/consultant/' . $mission->getConsultant()->getId() . '/' . $name;
        if (file_exists($fileName)) {
            unlink($fileName);
        }

        $this->get('knp_snappy.pdf')->generateFromHtml($html, $fileName);

        $mission->setDocument($name);
        $em->persist($mission);

        $attachement = array(
            "name" => $name,
            "file" => $data
        );

        $this->get('application_users.mailer.mail_for_mission')->newMission($beneficiaire,$consultant,$attachement);
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function acceptedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);
        $beneficiaire = $mission->getBeneficiaire();
        $mission->setState('accepted');
        $mission->setDateAcceptation(new \DateTime('now'));

        $suiviMission = new SuiviAdministratif();
        $suiviMission->setBeneficiaire($beneficiaire);
        $suiviMission->setInfo("Mission acceptée par " . ucfirst($beneficiaire->getConsultant()->getPrenom()) . " " . strtoupper($beneficiaire->getConsultant()->getNom()));
        $em->persist($suiviMission);

        $em->persist($mission);
        $em->flush();

//        envoi mail pour administrateur
        $this->get('application_users.mailer.mail_for_mission')->acceptedMission($mission);
        $this->get('application_users.mailer.mail_for_mission')->acceptedMissionB($mission);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function confirmedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);
        $mission->setState('confirmed');
        $mission->setDateConfirmation(new \DateTime('now'));
        $beneficiaire = $mission->getBeneficiaire();
        $beneficiaire->setConsultant($mission->getConsultant());

        $suiviMission = new SuiviAdministratif();
        $suiviMission->setBeneficiaire($beneficiaire);
        $suiviMission->setInfo("Accord pour démarrage VAE à " . ucfirst($beneficiaire->getConsultant()->getPrenom()) . " " . strtoupper($beneficiaire->getConsultant()->getNom()));
        $em->persist($suiviMission);

        $historique = new Historique();
        $historique->setHeuredebut(new \DateTime('now'));
        $historique->setHeurefin(new \DateTime('now'));
        $historique->setSummary("");
        $historique->setTypeRdv("");
        $historique->setConsultant($beneficiaire->getConsultant());
        $historique->setBeneficiaire($beneficiaire);
        $historique->setDescription("Accord pour démarrage VAE à " . ucfirst(strtolower($beneficiaire->getConsultant()->getPrenom())) . " " . ucfirst(strtolower($beneficiaire->getConsultant()->getNom())));
        $historique->setEventId("0");
        $historique->setUser($this->getUser());
        $em->persist($historique);

        $em->persist($beneficiaire);
        $em->persist($mission);
        $em->flush();

//        envoi mail consultant
        $this->get('application_users.mailer.mail_for_mission')->confirmedMission($beneficiaire, $beneficiaire->getConsultant());

        $typeUser = $this->get('application_users.getTypeUser')->typeUser($this->getUser());
        if ($typeUser == 'Administrateur') {
            return $this->redirect($this->generateUrl('application_mission_admin_index'));
        } else {
            return $this->redirectToRoute('my_account');
        }
    }

    /**
     * affichage de la vue du formulaire quand on decline une mission
     * fait par le consultant
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function declineAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);

        $form_email = $this->createForm(MailingType::class, null, array(
            'action' => $this->generateUrl('application_mission_decline', array(
                'id' => $mission->getId()
            )),
            'method' => 'POST',
        ));
        $form_email->handleRequest($request);

        if ($form_email->isValid() && $request->isMethod('POST')) {
            $message = $form_email['message']->getData();

            $beneficiaire = $mission->getBeneficiaire();
            $suiviMission = new SuiviAdministratif();
            $suiviMission->setBeneficiaire($beneficiaire);
            $suiviMission->setInfo("Mission refusée par " . ucfirst($beneficiaire->getConsultant()->getPrenom()) . " " . strtoupper($beneficiaire->getConsultant()->getNom()));
            $em->persist($suiviMission);

            $this->forward('ApplicationUsersBundle:MissionArchive:new', array(
                'mission' => $mission,
                'message' => $message,
                'state' => 'declined'
            ));

            $typeUser = $this->get('application_users.getTypeUser')->typeUser($this->getUser());
            if ($typeUser == 'Administrateur') {
                return $this->redirect($this->generateUrl('application_mission_admin_index'));
            } else {
                return $this->redirectToRoute('my_account');
            }

        }

        return $this->render('ApplicationUsersBundle:Mission:modal.html.twig', array(
            'form_email' => $form_email->createView(),
            'mission' => $mission
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function revokedAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);

        $form_email = $this->createForm(MailingType::class, null, array(
            'action' => $this->generateUrl('application_mission_revoke', array(
                'id' => $mission->getId()
            )),
            'method' => 'POST',
        ));
        $form_email->handleRequest($request);

        if ($form_email->isValid() && $request->isMethod('POST')) {

            $message = $form_email['message']->getData();
            $this->get('application_users.mailer.mail_for_mission')->revokedMission($mission, $message);

            $this->forward('ApplicationUsersBundle:MissionArchive:new', array(
                'mission' => $mission,
                'message' => $message,
                'state' => 'revoked'
            ));

            $typeUser = $this->get('application_users.getTypeUser')->typeUser($this->getUser());
            if ($typeUser == 'Administrateur') {
                return $this->redirect($this->generateUrl('application_mission_admin_index'));
            } else {
                return $this->redirectToRoute('my_account');
            }
        }

        return $this->render('ApplicationUsersBundle:Mission:modal.html.twig', array(
            'form_email' => $form_email->createView(),
            'mission' => $mission
        ));
    }

    /**
     * supprimer une mission
     *
     * @param $id
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);

        $em->remove($mission);
        $em->flush();
    }

    /**
     * cette fonction permet de modifier l'etat de la mission
     *
     * @param $state
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function stateAction($state, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);

        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION')) {
            $consultant = $mission->getConsultant();
        } elseif ($this->getUser()->getid() == $mission->getConsultant()->getId()) {
            $consultant = $this->getUser();
        } else {
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

        switch ($state) {
            case "accept" :
                $this->forward('ApplicationUsersBundle:Mission:accepted', array(
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

        $typeUser = $this->get('application_users.getTypeUser')->typeUser($this->getUser());
        if ($typeUser == 'Administrateur') {
            return $this->redirect($this->generateUrl('application_mission_admin_index'));
        } else {
            return $this->redirectToRoute('my_account');
        }
    }

    /**
     * permet d'afficher l'index mission pour un consultant donné
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction($id)
    {
        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION') || $this->getUser()->getid() == $id) {
        } else {
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

        $nbMissionNew = $em->getRepository('ApplicationUsersBundle:Mission')->countByState("new", $id);
        $nbMissionAccepted = $em->getRepository('ApplicationUsersBundle:Mission')->countByState("accepted", $id);
        $nbMissionConfirmed = $em->getRepository('ApplicationUsersBundle:Mission')->countByState("confirmed", $id);

        return $this->render('ApplicationUsersBundle:Mission:index.html.twig', array(
            'consultant' => $consultant,
            'nbNew' => $nbMissionNew,
            'nbAccepted' => $nbMissionAccepted,
            'nbConfirmed' => $nbMissionConfirmed
        ));
    }

    /**
     * affichage de tous les mission pour l'admin
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function adminIndexAction()
    {
        $authorization = $this->get('security.authorization_checker');
        if (true === $authorization->isGranted('ROLE_ADMIN') || true === $authorization->isGranted('ROLE_COMMERCIAL') || true === $authorization->isGranted('ROLE_GESTION')) {
        } else {
            return $this->redirect($this->generateUrl('application_plateforme_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $missions = $em->getRepository('ApplicationUsersBundle:Mission')->findAll();

        return $this->render('ApplicationUsersBundle:Mission:AdminIndex.html.twig', array(
            'missions' => $missions,
        ));
    }

    /**
     * cette fonction permet d'ajouter le contrat de mission quand le consultant accepte la mission
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addDocumentAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);

        $form = $this->createForm(FormType::class, $mission);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Accepter'
        ))
            ->add('document', FileType::class, array(
                'label' => false,
                'required' => false,
                'data_class' => null,
            ));

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $file = $mission->getDocument();
            if (!is_null($file)) {
                $name = strtolower("contrat_" . $mission->getBeneficiaire()->getNomConso() . "_" . $mission->getBeneficiaire()->getPrenomConso() . ".pdf");
                $fileName = $this->get('app.file_uploader')->uploadAvatar($file, 'uploads/consultant/' . $mission->getConsultant()->getId(), $name, $name);
                $mission->setDocument($fileName);
            }

            $em->persist($mission);
            $em->flush();

            return $this->forward('ApplicationUsersBundle:Mission:state', array(
                'state' => 'accept',
                'id' => $mission->getId(),
            ));
        }

        return $this->render('ApplicationUsersBundle:Mission:addDocument.html.twig', array(
            'form' => $form->createView(),
            'mission' => $mission,
        ));
    }

    /**
     * cette fonction permet le téléchargement d'un contrat de mission si on en a besoin
     *
     * @param $id
     * @return Response
     */
    public function downloadContratMissionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $mission = $em->getRepository('ApplicationUsersBundle:Mission')->find($id);
        $beneficiaire = $mission->getBeneficiaire();
        $consultant = $mission->getConsultant();
        $facturation = $consultant->getFacturation();

        $html = $this->renderView('ApplicationUsersBundle:Mission:newMission.html.twig', array(
            'beneficiaire' => $beneficiaire,
            'consultant' => $consultant,
            'facturation' => $facturation,
            'mission' => $mission
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array(
                'enable-javascript' => true,
                'encoding' => 'utf-8',
                'lowquality' => false,
                'javascript-delay' => 5000,
                'images' => true,
            )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="contrat_' . $beneficiaire->getPrenomConso() . '_' . $beneficiaire->getNomConso() . '.pdf"',
            )
        );
    }

    public function getListAction($state, $page = 0, $idConsultant = null)
    {
        $limit = 10;
        if ($page == 0) {
            $offset = 0;
        } else {
            $offset = $limit + (($page) * $limit);
        }

        $em = $this->getDoctrine()->getManager();
        $missions = $em->getRepository('ApplicationUsersBundle:Mission')->getLastestFromToByState($limit, $offset, $state, $idConsultant);

        $next = count($missions) <= 50;

        return $this->render('ApplicationUsersBundle:Mission:list.html.twig', array(
            'missions' => $missions,
            'state' => $state,
            "next" => $next,
        ));
    }
}