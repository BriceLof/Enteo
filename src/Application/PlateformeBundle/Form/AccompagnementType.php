<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AccompagnementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('financeur', CollectionType::class, array(
                'entry_type' => FinanceurType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'attr' => array(
                    'class' => 'financeur_accompagnement',
                )
            ))

            ->add('opcaOpacif', TextType::class, array(
                'label' => 'nom OPCA/OPACIF',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('heure', TextType::class, array(
                'label' => 'Accompagnement en heure',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',

                )
            ))

            ->add('tarif', NumberType::class, array(
                'label' => 'Tarif Accompagnement',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('dateDebut', DateType::class, array(
                'label' => 'date de début',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'class' => 'accompagnementDate',
                    'style' => 'width: 60%; margin-top:-5px; background-color: rgba(228, 228, 228, 0.45)',
                    'autocomplete' => 'off',
                )
            ))
            ->add('dateFin', DateType::class, array(
                'label' => 'date de fin',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'class' => 'accompagnementDate',
                    'style' => 'width: 60%; margin-top:-5px; background-color: rgba(228, 228, 228, 0.45)',
                    'autocomplete' => 'off',
                )
            ))

            ->add('submit', SubmitType::class, array('label' => 'Valider')
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Accompagnement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'application_plateformebundle_accompagnementType';
    }
}
