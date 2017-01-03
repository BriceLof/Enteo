<?php

namespace Application\PlateformeBundle\Form;

use Application\PlateformeBundle\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RechercheBeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nomConso', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'nom',
                )
            ))
            ->add('prenomConso', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'prénom',
                )
            ))
            ->add('emailConso', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Email',
                )
            ))
            ->add('ville', VilleType::class, array(
                'label' => '',
                'required' => false,
            ))
            ->add('pays', TextType::class, array(
                'label' => 'Pays ',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Pays',
                )
            ))
            ->add('dateDebut', DateType::class, array(
                'label' => 'date de Debut',
                'mapped' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'date de début',
                    'class' => 'date',
                    'autocomplete' => 'off',
                )
            ))
            ->add('dateFin', DateType::class, array(
                'label' => 'date de fin',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'date de fin',
                    'class' => 'date',
                    'autocomplete' => 'off',
                )
            ))
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