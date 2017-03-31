<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Beneficiaire;
use Application\PlateformeBundle\Entity\News;
use Application\PlateformeBundle\Entity\Historique;
use Application\PlateformeBundle\Entity\SuiviAdministratif;
use Application\PlateformeBundle\Entity\DetailStatut;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Application\PlateformeBundle\Form\NewsType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * News controller.
 *
 */
class NewsController extends Controller
{
    /**
     * Creates a new News entity.
     *
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $beneficiaire = $em->getRepository('ApplicationPlateformeBundle:Beneficiaire')->find($id);

        $news = new News();
        /*$news = $em->getRepository("ApplicationPlateformeBundle:News")->findOneBy(
            array("beneficiaire"    => $beneficiaire), 
            array("id"              => "DESC")
        );*/
        //var_dump($news);
        $form = $this->createForm(NewsType::class, $news);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $news = $form->getData();
            $news->setBeneficiaire($beneficiaire);
            $em = $this->getDoctrine()->getManager();
            
            // ajouter un historique
            if($news->getDetailStatut()->getDetail() == "Email suite No Contact" OR ($news->getStatut()->getSlug() == "rv1-realise" OR $news->getStatut()->getSlug() == "rv2-realise"))
            {
                $historique = new Historique();
                $historique->setHeuredebut(new \DateTime('now'));
                $historique->setHeurefin(new \DateTime('now'));
                $historique->setDateFin(new \DateTime('now'));
                $historique->setSummary("");
                $historique->setTypeRdv("");
                $historique->setBeneficiaire($news->getBeneficiaire());
                $historique->setDescription($news->getDetailStatut()->getDetail());
                $historique->setEventId("0");
                $em->persist($historique);  
            }
            
            // ajouter un suivi administratif 
            if($news->getDetailStatut()->getDetail() == "RV1 Positif" OR $news->getDetailStatut()->getDetail() == "RV2 Positif")
            {
                $statutRepo = $em->getRepository('ApplicationPlateformeBundle:Statut')->findOneBySlug("dossier-en-cours");
                $detailStatutRepo = $em->getRepository('ApplicationPlateformeBundle:DetailStatut')->findOneByStatut($statutRepo->getId());

                $suiviAdministraif = new SuiviAdministratif();
                $suiviAdministraif->setBeneficiaire($news->getBeneficiaire());
                $suiviAdministraif->setStatut($statutRepo);
                $suiviAdministraif->setDetailStatut($detailStatutRepo);
                $em->persist($suiviAdministraif);
            }
            
            $em->persist($news);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'News bien ajouté');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                'beneficiaire' => $beneficiaire,
                'id' => $beneficiaire->getId(),
            )));
        }

        return $this->render('ApplicationPlateformeBundle:News:new.html.twig', array(
            'news' => $news,
            'beneficiaire' => $beneficiaire,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays all News entity.
     *
     */
    public function showAllAction(Beneficiaire $beneficiaire)
    {
        return $this->render('ApplicationPlateformeBundle:News:showAll.html.twig', array(
            'beneficiaire' => $beneficiaire,
        ));
    }

    /**
     * Deletes a News entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('ApplicationPlateformeBundle:News')->find($id);
        $beneficiaire = $news->getBeneficiaire();

        if (!$news) {
            throw $this->createNotFoundException('Unable to find News.');
        }

        $em->remove($news);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'News supprimé avec succès');

        return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
            'id' => $beneficiaire->getId(),
        )));
    }

    /**
     * Edits an existing News entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $news = $em->getRepository('ApplicationPlateformeBundle:News')->find($id);
        $beneficiaire = $news->getBeneficiaire();

        if (!$news) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm = $this->createEditForm($news);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'News modifié avec succès');

            return $this->redirect($this->generateUrl('application_show_beneficiaire', array(
                    'id' => $beneficiaire->getId(),
                )
            ));
        }

        return $this->render('ApplicationPlateformeBundle:News:edit.html.twig', array(
            'news'      => $news,
            'beneficiaire' => $beneficiaire,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a News entity.
     *
     * @param News $news The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(News $news)
    {
        $form = $this->createForm(NewsType::class, $news);

        return $form;
    }
}