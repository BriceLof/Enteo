<?php

namespace Api\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class BeneficiaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codePostal', TextType::class, array(
                'mapped' => false
            ))
            ->add('domaineVae')
            ->add('statut')
            ->add('diplomeVise')
            ->add('motivation')
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
            ->add('emailConso')
            ->add('heureRappel')
            ->add('origineMer')
            ->add('experience')
            ->add('poste')
            ->add('pays', CountryType::class)
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
