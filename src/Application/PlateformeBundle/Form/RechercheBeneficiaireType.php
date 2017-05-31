<?php

namespace Application\PlateformeBundle\Form;

use Application\PlateformeBundle\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RechercheBeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

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
            ->add('consultant', EntityType::class, array(
                'placeholder' => 'Consultant',
				'required' => false,
                'class' => 'ApplicationUsersBundle:Users',
                'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->where('u.roles LIKE :role ')
                                ->setParameter('role', '%ROLE_CONSULTANT%')
								->orderBy('u.nom', 'ASC')
                              ;
                },
                'attr' => array(
                    'class' => ''
                )
            ))
            ->add('villeMer', VilleType::class, array(
                'label' => 'Ville de MenR',
                'required' => false,
            ))

            ->add('refFinanceur', TextType::class, array(
                'label' => 'Réf. Financeur',
                'required' => false,
            ))

            ->add('triAlpha', HiddenType::class, array(
                'mapped' => false,
                'data' => 0
            ))

            ->add('triDate', HiddenType::class, array(
                'mapped' => false,
                'data' => 0
            ))
           
            /*->add('dateDebut', DateType::class, array(
                'label' => 'Date de Debut',
                'mapped' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'Date début',
                    'class' => 'accompagnementDate',
                    'autocomplete' => 'off',
                )
            ))
            ->add('dateFin', DateType::class, array(
                'label' => 'Date de fin',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'Date fin',
                    'class' => 'accompagnementDate',
                    'autocomplete' => 'off',
                )
            ))*/
            ->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));

            ;
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