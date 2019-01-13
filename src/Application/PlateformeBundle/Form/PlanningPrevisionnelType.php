<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PlanningPrevisionnelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateLivret1', DateType::class, array(
                'label' => 'Date Livret 1',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'accompagnementDate'
                )
            ))
            ->add('dateLivret2', DateType::class, array(
                'label' => 'Date Livret 2',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'accompagnementDate'
                )
            ))
            ->add('dateJury', DateType::class, array(
                'label' => 'Date Jury',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'accompagnementDate'
                )
            ))
            ->add('statutLivret1', ChoiceType::class, array(
                'required' => false,
                'label' => 'Statut',
                'choices' => array(
                    '...' => '',
                    'Prévu' => 'prevu',
                    'Réalisé' => 'realise',
                ),
                'attr' => array(
                    'class' => '',
                )
            ))
            ->add('statutLivret2', ChoiceType::class, array(
                'required' => false,
                'label' => 'Statut',
                'choices' => array(
                    '...' => '',
                    'Prévu' => 'prevu',
                    'Réalisé' => 'realise',
                ),
                'attr' => array(
                    'class' => '',
                )
            ))
            ->add('statutJury', ChoiceType::class, array(
                'required' => false,
                'label' => 'Statut',
                'choices' => array(
                    '...' => '',
                    'Prévu' => 'prevu',
                    'Réalisé' => 'realise',
                ),
                'attr' => array(
                    'class' => '',
                )
            ))
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
        return 'application_plateformebundle_projetType';
    }
}