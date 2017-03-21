<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\EntityRepository;

class EmployeurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raisonSociale', TextType::class, array(
                'label' => 'Raison Sociale ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('type', ChoiceType::class, array(
                'required' => false,
                'label' => 'Type Financeur',
                'choices' => array(
                    '...' => '',
                    'OPCA' => 'OPCA',
                    'OPACIF' => 'OPACIF',
                    'Entreprise' => 'Entreprise',
                    'Bénéficiaire' => 'Beneficiaire',
                    'Pôle Emploi' => 'Pole Emploi',
                ),
                'attr' => array(
                    'class' => 'fiche type_employeur',
                )
            ))

            ->add('organisme', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'fiche',
                ),
            ))

            ->add('siret', TextType::class, array(
                'label' => 'Siret ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('conventionCollective', TextType::class, array(
                'label' => 'Convention Collective ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
                )
            ))

            ->add('apeNace', TextType::class, array(
                'label' => 'APE/NACE ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
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

            ->add('code_postal', TextType::class, array(
                'mapped' => false,
                'label' => "Code postal *",
                'required' => true,
                'attr' => array("maxlength" => 5, "class" => "codePostalInputForAjaxEmployeur")
            ))
            ->add('codePostalHiddenEmployeur', HiddenType::class, array("mapped" => false))
            ->add('idVilleHiddenEmployeur', HiddenType::class, array("mapped" => false))
            ->add('ville', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:Ville',
                'label' => 'Ville *',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.nom', 'ASC')
                        ->setMaxResults( 1 );
                },
                'choice_label' => 'nom',
            ))

            ->add('proposition_adresse', HiddenType::class, array(
                'mapped' => false,
            ))

            ->add('pays', TextType::class, array(
                'label' => 'Pays ',
                'data' => 'FRANCE',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'fiche'
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
            'data_class' => 'Application\PlateformeBundle\Entity\Employeur'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_employeurType';
    }
}