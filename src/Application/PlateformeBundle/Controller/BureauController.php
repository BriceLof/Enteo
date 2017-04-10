<?php

namespace Application\PlateformeBundle\Controller;

use Application\PlateformeBundle\Entity\Bureau;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Application\PlateformeBundle\Form\BureauType;

/**
 * Bureau controller.
 *
 */
class BureauController extends Controller
{
    /**
     * Lists all bureau entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bureaux = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findAll();

        return $this->render('ApplicationPlateformeBundle:Bureau:index.html.twig', array(
            'bureaux' => $bureaux,
        ));
    }

    /**
     * Creates a new bureau entity.
     *
     */
    public function newAction(Request $request)
    {
        $bureau = new Bureau();
        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bureau);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Bureau ajouté avec succès');

            return $this->redirect($this->generateUrl('application_index_bureau'));
        }

        return $this->render('ApplicationPlateformeBundle:Bureau:new.html.twig', array(
            'bureau' => $bureau,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a bureau entity.
     *
     */
    public function showAction(Bureau $bureau)
    {
        $deleteForm = $this->createDeleteForm($bureau);

        return $this->render('bureau/show.html.twig', array(
            'bureau' => $bureau,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing bureau entity.
     *
     */
    public function editAction(Request $request, Bureau $bureau)
    {
        if (!$bureau) {
            throw $this->createNotFoundException('Bureau introuvable.');
        }

        $editForm = $this->createForm(BureauType::class, $bureau);
        $editForm->get('codePostalHidden')->setData($bureau->getVille()->getCp());
        $editForm->get('idVilleHidden')->setData($bureau->getVille()->getId());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bureau);
            $em->flush();

            $this->get('session')->getFlashBag()->add('info', 'Bureau modifié avec succès');

            return $this->redirect($this->generateUrl('application_index_bureau'));
        }

        return $this->render('ApplicationPlateformeBundle:Bureau:edit.html.twig', array(
            'bureau' => $bureau,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a bureau entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);
        if (!$bureau) {
            throw $this->createNotFoundException('Unable to find Bureau.');
        }
        $bureau->setSupprimer(true);
        $bureau->setActifInactif(false);
        $em->persist($bureau);
        $em->flush();

        $this->get('session')->getFlashBag()->add('info', 'Bureau supprimé avec succès');

        return $this->redirect($this->generateUrl('application_index_bureau'));
    }

    /**
     * activer ou desactiver un bureau
     */
    public function actifInactifAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $bureau = $em->getRepository('ApplicationPlateformeBundle:Bureau')->find($id);
        if (!$bureau) {
            throw $this->createNotFoundException('Unable to find Bureau.');
        }
        if ($bureau->getActifInactif() == true) {
            $bureau->setActifInactif(false);
            $em->persist($bureau);
            $em->flush($bureau);

            $this->get('session')->getFlashBag()->add('info', 'Bureau desactivé');
        } else {
            $bureau->setActifInactif(true);
            $em->persist($bureau);
            $em->flush($bureau);

            $this->get('session')->getFlashBag()->add('info', 'Bureau activé');
        }

        return $this->redirect($this->generateUrl('application_index_bureau'));
    }

    public function ajaxSearchAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $list = [];
            $listDpt = [];
            $nomVille = $request->query->get('nomVille');

            $test = $request->query->get('isBureauCalendar');

            $em = $this->getDoctrine()->getManager();
            $bureaux = $em->getRepository('ApplicationPlateformeBundle:Bureau')->findBy(array(
                'actifInactif' => true,
            ));

            foreach ($bureaux as $bureau) {
                if (stristr($bureau->getVille()->getNom(), $nomVille) === false) {
                } else {
                    if($test != null){
                        if (count($list) == 0){
                            $list[] = array(
                                'idVille' => $bureau->getVille()->getId(),
                                'ville' => $bureau->getVille()->getNom(),
                                'dpt' => $bureau->getVille()->getDpt(),
                            );
                            $listDpt[] = $bureau->getVille()->getDpt();
                        }else{
                            if (!in_array($bureau->getVille()->getDpt(),$listDpt)){
                                $list[] = array(
                                    'idVille' => $bureau->getVille()->getId(),
                                    'ville' => $bureau->getVille()->getNom(),
                                    'dpt' => $bureau->getVille()->getDpt(),
                                );
                            }
                        }
                    }else{
                        $list[] = array(
                            'id' => $bureau->getId(),
                            'nom' => $bureau->getNombureau(),
                            'adresse' => $bureau->getAdresse(),
                            'cp' => $bureau->getVille()->getCp(),
                            'ville' => $bureau->getVille()->getNom(),
                        );
                    }
                }
            }

            $resultats = new JsonResponse(json_encode($list));
            return $resultats;

        } else {
            throw new \Exception('erreur');
        }
    }

    public function showCalendarAction(Request $request)
    {

        //on récupere la ville
        $em = $this->getDoctrine()->getManager();
        $bureau = new Bureau();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $bureau);
        $formBuilder
            ->add('villeBureau', TextType::class, array(
                'mapped' => false,
                'attr' => array(
                    'placeholder' => 'Veuillez saisir la ville',
                    'autocomplete' => 'off'
                )
            ))
        ;

        $form = $formBuilder->getForm();

        $iframe = '<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;mode=WEEK&amp;height=600&amp;wkst=2&amp;hl=fr&amp;bgcolor=%23FFFFFF&amp;';

        //requete ajax
        if ($request->isXmlHttpRequest()) {
            $id = $request->query->get('id');

            $ville = $em->getRepository('ApplicationPlateformeBundle:Ville')->find($id);

            $results = $em->getRepository('ApplicationPlateformeBundle:Bureau')->getBureauByDpt($ville->getDpt());

            $color = $this->get('application_plateforme.calendar')->getColor();
            $i = 0;
            $tab = [];
            foreach ($results as $bureau) {
                if ($bureau->getCalendrierid() != null) {
                    $sentinel = 0;
                    if ($ville == $bureau->getVille()){
                        $sentinel = 1;
                        $iframe .= 'src=' . $bureau->getCalendrierid() . '&amp;color=' . $color[$i] . '&amp;';
                    }
                    $tab[] = array(
                        'nomBureau' => $bureau->getNombureau(),
                        'calendrierId' => $bureau->getCalendrierid(),
                        'color' => $this->get('application_plateforme.calendar')->getColorName($color[$i]),
                        'googleColor' => $color[$i],
                        'sentinel' => $sentinel
                    );
                    $i++;
                }
            }

            $iframe .= 'ctz=Europe%2FParis"style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>';

            $list = array(
                'tabs' => $tab,
                'iframe' => $iframe
            );

            $resultats = new JsonResponse(json_encode($list));
            return $resultats;
        }

        $iframe .= 'ctz=Europe%2FParis"style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>';

        return $this->render('ApplicationPlateformeBundle:Bureau:showCalendar.html.twig', array(
            'iframe' => $iframe,
            'form' => $form->createView(),
        ));
    }
}
