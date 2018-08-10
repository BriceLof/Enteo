<?php

namespace Application\UsersBundle\Controller;

use Application\UsersBundle\Entity\Image;
use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Form\ImageType;
use Application\UsersBundle\Form\PhotoDeProfileType;
use Application\UsersBundle\Form\PhotoProfileType;
use Application\UsersBundle\Form\StatutConsultantType;
use Application\UsersBundle\Form\UsersType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ReferentController extends Controller
{

    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $referents = $em->getRepository('ApplicationUsersBundle:Users')->findByTypeUser('ROLE_REFERENT', true);

        return $this->render('ApplicationUsersBundle:Referent:index.html.twig', array(
            'referents' => $referents,
        ));
    }

    public function addConsultantAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $referent = $em->getRepository('ApplicationUsersBundle:Users')->find($id);
        $ids[] = $referent->getId();
        foreach ($referent->getConsultants() as $consultant ){
            $ids[] = $consultant->getId();
        }

        $builder = $this->createFormBuilder($ids)
            ->add('consultants', EntityType::class, array(
                'class' => 'ApplicationUsersBundle:Users',
                'label' => 'Consultant :',
                'placeholder' => 'choisissez votre consultant',
                'query_builder' => function (EntityRepository $er) use ($ids) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :type')
                        ->setParameter('type', '%consultant%')
                        ->andWhere($er->createQueryBuilder('v')->expr()->notIn('u.id', $ids))
                        ->orderBy('u.nom', 'ASC');
                },
                'mapped' => false
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Enregister',
                'attr' => array(
                    'class' => 'btn btn-primary',
                )
            ))
            ->setAction($this->generateUrl('referent_add', array('id' => $id)))
            ->setMethod('POST')
        ;

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $consultant = $form['consultants']->getData();

            $referent->addConsultant($consultant);

            $em->persist($referent);
            $em->flush();

            $json = json_encode($referent->getId());
            $response = new Response($json, 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        return $this->render('ApplicationUsersBundle:Referent:addConsultant.html.twig', array(
            'referent' => $referent,
            'form' => $form->createView()
        ));
    }

    public function showAction($id){
        $em = $this->getDoctrine()->getManager();
        $referent = $em->getRepository('ApplicationUsersBundle:Users')->find($id);

        $template = $this->render('ApplicationUsersBundle:Referent:show.html.twig', array(
            'referent' => $referent,
        ))->getContent();

        $json = json_encode($template);
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function removeConsultantAction($idReferent, $idConsultant){
        $em = $this->getDoctrine()->getManager();
        $referent = $em->getRepository('ApplicationUsersBundle:Users')->find($idReferent);
        $consultant = $em->getRepository('ApplicationUsersBundle:Users')->find($idConsultant);

        if (!$referent) {
            throw $this->createNotFoundException('référent non trouvé');
        }

        if (!$consultant) {
            throw $this->createNotFoundException('consultant non trouvé');
        }

        $referent->removeConsultant($consultant);
        $em->persist($referent);
        $em->flush();

        $json = json_encode($referent->getId());
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}