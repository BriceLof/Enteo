<?php

namespace Application\StatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EcoleUniversiteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, array(
                'label' => 'Début',
                'required' => false,
                'mapped' => false,
                'html5' => false,
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'accompagnementDate'
                ),
            ))

            ->add('dateTo', DateType::class, array(
                'label' => 'Fin',
                'required' => false,
                'mapped' => false,
                'html5' => false,
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'accompagnementDate'
                ),
            ))

            ->add('submit', SubmitType::class, array(
                'label' => 'Rechercher',
                'attr' => array('class' => 'btn btn-primary')
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'application_stasbundle_ecole_universite_Type';
    }
}
