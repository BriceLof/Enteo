<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProjetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statut', TextType::class, array(
                'label' => 'Statut actuel :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('experience', TextType::class, array(
                'label' => 'Années Expérience :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('heureDif', IntegerType::class, array(
                'label' => 'Heures DIF dispo :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('heureCpf', IntegerType::class, array(
                'label' => 'Heures CPF dispo :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('formationInitiale', TextType::class, array(
                'label' => 'Formation Initiale :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('diplomeVise', TextType::class, array(
                'label' => 'Diplôme visé :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('motivation', TextareaType::class, array(
                'label' => 'Motivation :',
                'required' => false,
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
            'data_class' => 'Application\PlateformeBundle\Entity\Beneficiaire'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_projetType';
    }
}