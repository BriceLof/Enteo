<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Choice;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'Ville ',
                'attr' => array(
                    'class' => 'ville fiche',
                    'autocomplete' => 'off',
                    'readonly' => 'readonly',
                )
            ))

            ->add('cp', TextType::class, array(
                'label' => 'Code Postal ',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'cp fiche',
                    'maxlength' => 5,
                    'autocomplete' => 'off',
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
            'data_class' => 'Application\PlateformeBundle\Entity\Ville'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_villeType';
    }
}