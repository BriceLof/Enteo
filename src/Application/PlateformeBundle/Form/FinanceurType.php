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
                'required' => false,
                'label' => 'Type ',
                'choices' => array(
                    '...' => '',
                    'OPCA' => 'OPCA',
                    'OPACIF' => 'OPACIF',
                    'Entreprise' => 'Entreprise',
                    'Bénéficiaire' => 'Beneficiaire',
                    'Pôle Emploi' => 'Pole Emploi',
                ),
                'attr' => array(
                    'class' => 'nom_organisme organisme_input accompangment',
                )
            ))

            ->add('organisme', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'organisme_organisme organisme_input accompangment',
                    'style' => 'display:none'
                ),
                'label_attr' => array(
                    'class' => 'organisme_organisme_label',
                    'style' => 'display:none'
                )
            ))

            ->add('montant', NumberType::class, array(
                'required' => false,
                'label' => ' Montant en euros',
                'attr' => array(
                    'class' => 'montant_organisme organisme_input accompangment',
                )
            ))
            ->add('dateAccord', DateType::class, array(
                'required' => false,
                'label' => 'Accord Prise en charge',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'class' => 'accompagnementDate organisme_input accompangment',
                )
            ))
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