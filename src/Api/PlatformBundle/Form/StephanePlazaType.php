<?php

namespace Api\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class StephanePlazaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codePostal', TextType::class, array(
                'mapped' => false
            ))
            ->add('city', TextType::class, array(
                'mapped' => false
            ))
            ->add('civiliteConso', ChoiceType::class, array(
                'choices' => array(
                    'Monsieur' => 'M.',
                    'Madame' => 'Mme',
                    'Mademoiselle' => 'Mlle',
                )
            ))
            ->add('nomConso')
            ->add('prenomConso')
            ->add('telConso')
            ->add('adresse')
            ->add('emailConso')
            ->add('origineMer')
            ->add('experience')
            ->add('cvFile', FileType::class, array(
                'mapped' => false
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Beneficiaire',
            'csrf_protection' => false
        ));
    }
}
