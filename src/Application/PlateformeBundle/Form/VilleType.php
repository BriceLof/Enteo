<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'Ville ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('zip', TextType::class, array(
                'label' => 'Code Postal ',
                'attr' => array(
                    'placeholder' => '',
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