<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BureauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'nom ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('adresse', TextType::class, array(
                'label' => 'adresse ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('ville', VilleType::class, array(
                'label' => '',
                'required' => false,
            ));
    }

            /**
             * @param OptionsResolver $resolver
             */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Bureau'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_bureauType';
    }
}
