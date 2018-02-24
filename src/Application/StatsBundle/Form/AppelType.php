<?php

namespace Application\StatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AppelType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('totalAm', IntegerType::class, array(
                'label' => false,
                'required' => false,
                'mapped' => false,
                'attr' => array(
                    'readonly' => true
                )
            ))

            ->add('nbRdvAm', IntegerType::class, array(
                'label' => false,
                'required' => false,
            ))

            ->add('nbReponseAm', IntegerType::class, array(
                'label' => false,
                'required' => false,
            ))

            ->add('nbContactAm', IntegerType::class, array(
                'label' => false,
                'required' => false,
            ))

            ->add('nbRdvPm', IntegerType::class, array(
                'label' => false,
                'required' => false,
            ))

            ->add('totalPm', IntegerType::class, array(
                'label' => false,
                'required' => false,
                'mapped' => false,
                'attr' => array(
                    'readonly' => true
                )
            ))

            ->add('nbReponsePm', IntegerType::class, array(
                'label' => false,
                'required' => false,
            ))

            ->add('nbContactPm', IntegerType::class, array(
                'label' => false,
                'required' => false,
            ))

            ->add('commentaire', TextareaType::class, array(
                'label' => false,
                'required' => false,
            ))
            ->add('horaireAm', TextType::class, array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'horaire'
                )
            ))
            ->add('horairePm', TextType::class, array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'horaire'
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
            'data_class' => 'Application\StatsBundle\Entity\Appel'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_statsbundle_appel';
    }


}
