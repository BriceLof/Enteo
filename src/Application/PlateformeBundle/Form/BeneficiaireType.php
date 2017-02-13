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
                     'class' => 'fiche'
                )
            ))

            ->add('civiliteConso', TextType::class, array(
                'label' => 'Civilité ',
                 'attr' => array(
                    'placeholder' => '',
                     'class' => 'fiche'
                )
            ))

            ->add('nomConso', TextType::class, array(
                'label' => 'Nom ',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('prenomConso', TextType::class, array(
                'label' => 'Prénom ',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('poste', TextType::class, array(
                'label' => 'Poste occupé ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('csp', ChoiceType::class, array(
                'label' => 'CSP ',
                'required' => false,
                'choices' => array(
                    '...' => '',
                    'Demandeur d\'emploi' => 'demandeur d\'emploi',
                    'Ouvrier qualifié' => 'ouvrier qualifié',
                    'Employé' => 'employé',
                    'Technicien' => 'technicien',
                    'Agent de maitrise' => 'agent de maitrise',
                    'Cadre/ingenieur' => 'cadre/ingenieur',
                    'Chef d\'entreprise/PL' => 'chef d\'entreprise/PL',
                ),
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('type', ChoiceType::class, array(
                'label' => 'Type ',
                'required' => false,
                'choices' => array(
                    '...' => '',
                    'CDD' => 'cdd',
                    'CDI' => 'cdi',
                    'Interim' => 'interim',
                ),
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('telConso', TextType::class, array(
                'label' => 'Tél ',
                'attr' => array(
                    'maxlength' => 14,
                    'placeholder' => '',
                    'class' => 'telephoneConso fiche'
                )
            ))

            ->add('tel2', TextType::class, array(
                'label' => 'Tél 2 ',
                'required' => false,
                'attr' => array(
                    'maxlength' => 14,
                    'placeholder' => '',
                    'class' => 'telephoneConso fiche'
                )
            ))

            ->add('email2', TextType::class, array(
                'label' => 'Email 2',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
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
                    'class' => 'fiche'
                )
            ))

            ->add('adresseComplement', TextType::class, array(
                'label' => 'Complement ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('ville', VilleType::class, array(
                'label' => '',
                'required' => false,
                'by_reference' => true,
                'attr' => array(
                    'class' => 'fiche'
                )
            ))

            ->add('pays', TextType::class, array(
                'label' => 'Pays ',
                'data' => 'FRANCE',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('numSecu', TextType::class, array(
                'label' => 'N° de Sécu ',
                'required' => false,
                'attr' => array(
                    'maxlength' => 13,
                    'placeholder' => '13 chiffres',
                    'class' => 'fiche'
                )
            ))

            ->add('numSecuCle', TextType::class, array(
                    'label' => '',
                    'required' => false,
                    'attr' => array(
                        'maxlength' => 2,
                        'placeholder' => 'clé',
                        'class' => 'fiche'
                    )
            ))

            ->add('dateNaissance', DateType::class, array(
                'label' => 'Date de Naissance ',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'date fiche'
                )
            ))

            ->add('employeur', EmployeurType::class, array(
                'label' => '',
                'required' => false,
                'by_reference' => true,
                'attr' => array(
                    'class' => 'fiche'
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
