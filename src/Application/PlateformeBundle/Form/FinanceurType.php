<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class FinanceurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', ChoiceType::class, array(
                'choices' => array(
                    '...' => '',
                    'OPCA' => 'OPCA',
                    'OPACIF' => 'OPACIF',
                )
            ))

            ->add('montant', NumberType::class, array(
                'label' => ' Montant en euros',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('dateAccord', DateType::class, array(
                'label' => 'Accord Prise en charge',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'class' => 'date',
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
            'data_class' => 'Application\PlateformeBundle\Entity\Financeur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'application_plateformebundle_financeurType';
    }
}