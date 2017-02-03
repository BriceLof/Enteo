<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class BeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('dateConfMer', DateTimeType::class, array(
                'label' => 'Date de Conf MenR ',
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yy à hh:mm:ss',
                 'attr' => array(
                     'placeholder' => '',
                )
            ))

            ->add('civiliteConso', TextType::class, array(
                'label' => 'Civilité ',
                 'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('nomConso', TextType::class, array(
                'label' => 'Nom ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('prenomConso', TextType::class, array(
                'label' => 'Prénom ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('poste', TextType::class, array(
                'label' => 'Poste occupé ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('encadrement', ChoiceType::class, array(
                'label' => 'Encadrement ',
                'required' => false,
                'data' => 'oui',
                'choices' => array(
                    '...' => '',
                    'oui' => 'oui',
                    'non' => 'non',
                )
            ))

            ->add('telConso', TextType::class, array(
                'label' => 'Tél ',
                'attr' => array(
                    'maxlength' => 14,
                    'placeholder' => '',
                    'class' => 'telephoneConso'
                )
            ))

            ->add('tel2', TextType::class, array(
                'label' => 'Tél 2 ',
                'required' => false,
                'attr' => array(
                    'maxlength' => 14,
                    'placeholder' => '',
                    'class' => 'telephoneConso'
                )
            ))

            ->add('email2', TextType::class, array(
                'label' => 'Email 2',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('emailConso', TextType::class, array(
                'label' => 'Email  ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('adresse', TextType::class, array(
                'label' => 'Adresse ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('adresseComplement', TextType::class, array(
                'label' => 'Complement ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('ville', VilleType::class, array(
                'label' => '',
                'required' => false,
                'by_reference' => true,
            ))

            ->add('pays', TextType::class, array(
                'label' => 'Pays ',
                'data' => 'FRANCE',
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('numSecu', TextType::class, array(
                'label' => 'N° de Sécu ',
                'required' => false,
                'attr' => array(
                    'maxlength' => 13,
                    'placeholder' => '13 chiffres',
                )
            ))

            ->add('numSecuCle', TextType::class, array(
                    'label' => '',
                    'required' => false,
                    'attr' => array(
                        'maxlength' => 2,
                        'placeholder' => 'clé',
                    )
            ))

            ->add('dateNaissance', DateType::class, array(
                'label' => 'Date de Naissance ',
                'widget' => 'single_text',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))

            ->add('submit', SubmitType::class, array('label' => 'Rechercher'));
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
        return 'application_plateformebundle_beneficiaireType';
    }
}
