<?php

namespace Application\PlateformeBundle\Form;

use Application\PlateformeBundle\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class RechercheBeneficiaireType extends AbstractType
{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user) {
            throw new \LogicException(
                'The FriendMessageFormType cannot be used without an authenticated user!'
            );
        }

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
                $form = $event->getForm();
                if ($user->getRoles()[0] == 'ROLE_CONSULTANT') {
                    $form
                        ->add('statut', EntityType::class, array(
                            "mapped" => false,
                            'required' => false,
                            'placeholder' => 'Statut',
                            'class' => 'ApplicationPlateformeBundle:Statut',
                            'choice_label' => 'nom',
                            'query_builder' => function (EntityRepository $er) {
                                return $er->createQueryBuilder('s')
                                    ->where('s.accesConsultant = (:slug1)')
                                    ->orderBy('s.ordre', 'ASC')
                                    ->setParameters(array(
                                        'slug1' => true
                                    ));
                            },
                        ));
                } else {
                    $form
                        ->add('statut', EntityType::class, array(
                            "mapped" => false,
                            'required' => false,
                            'placeholder' => 'Statut',
                            'class' => 'ApplicationPlateformeBundle:Statut',
                            'choice_label' => 'nom',
                            'query_builder' => function (EntityRepository $er) {
                                return $er->createQueryBuilder('s')
                                    ->where('s.slug NOT IN (:slug1)')
                                    ->orderBy('s.ordre', 'ASC')
                                    ->setParameters(array(
                                        'slug1' => array('recevabilite', 'reporte')
                                    ));
                            },
                        ));
                }
                if (in_array('ROLE_REFERENT', $user->getRoles())) {
                    $ids[] = $user->getId();
                    foreach ($user->getConsultants() as $consultant){
                        $ids[] = $consultant->getId();
                    }
                    $form
                        ->add('consultant', EntityType::class, array(
                            'placeholder' => 'Consultant',
                            'required' => false,
                            'class' => 'ApplicationUsersBundle:Users',
                            'query_builder' => function (EntityRepository $er) use ($ids) {
                                return $er->createQueryBuilder('u')
                                    ->where('u.roles LIKE :role ')
                                    ->setParameter('role', '%ROLE_CONSULTANT%')
                                    ->andWhere($er->createQueryBuilder('v')->expr()->in('u.id', $ids))
                                    ->orderBy('u.nom', 'ASC');
                            },
                            'attr' => array(
                                'class' => ''
                            )
                        ));
                }else{
                    $form
                        ->add('consultant', EntityType::class, array(
                            'placeholder' => 'Consultant',
                            'required' => false,
                            'class' => 'ApplicationUsersBundle:Users',
                            'query_builder' => function (EntityRepository $er) {
                                return $er->createQueryBuilder('u')
                                    ->where('u.roles LIKE :role ')
                                    ->setParameter('role', '%ROLE_CONSULTANT%')
                                    ->orderBy('u.nom', 'ASC');
                            },
                            'attr' => array(
                                'class' => ''
                            )
                        ));
                }
            })
            ->add('telConso', TextType::class, array(
                'label' => 'téléphone',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Téléphone',
                    'maxlength' => 10,
                )
            ))
            ->add('refFinanceur', TextType::class, array(
                'label' => 'Réf. Financeur',
                'required' => false,
            ))
            ->add('nomConso', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nom',
                )
            ))
            ->add('prenomConso', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Prénom',
                )
            ))
            ->add('emailConso', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Email',
                )
            ))
            ->add('complementStatut', ChoiceType::class, array(
                "mapped" => false,
                'required' => false,
                'placeholder' => false,
                'choices' => array(
                    '>=' => '>=',
                    '=' => '=',
                ),
                'attr' => array(
                    'class' => 'complement'
                )
            ))
            ->add('detailStatut', EntityType::class, array(
                "mapped" => false,
                'required' => false,
                'placeholder' => 'Détail Statut',
                'class' => 'ApplicationPlateformeBundle:DetailStatut',
                'choice_label' => 'detail',
            ))
            ->add('complementDetailStatut', ChoiceType::class, array(
                "mapped" => false,
                'required' => false,
                'placeholder' => false,
                'choices' => array(
                    '>=' => '>=',
                    '=' => '=',
                ),
                'attr' => array(
                    'class' => 'complement',
                    'disabled' => 'disabled'
                )
            ))
            ->add('ville', TextType::class, array(
                "mapped" => false,
                "required" => false,
                'attr' => array(
                    'placeholder' => 'Veuillez saisir la ville',
                    'autocomplete' => 'off'
                )
            ))
            ->add('cacher', CheckboxType::class, array(
                "mapped" => false,
                'label' => 'Ne pas afficher les Bénéficiaires en Statut Abandon, Terminé',
                'required' => false,
            ))
            ->add('tri', HiddenType::class, array(
                'mapped' => false,
                'data' => 0
            ))
            ->add('page', HiddenType::class, array(
                'mapped' => false,
                'data' => 0
            ))
            ->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Beneficiaire'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_rechercheBeneficiaireType';
    }
}