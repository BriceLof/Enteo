<?php

namespace Application\UsersBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;

class FacturationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $facturation = $options['data'];

        $builder
            ->add('type', ChoiceType::class, array(
                'required' => true,
                'label' => 'type',
                'choices' => array(
                    '...' => '',
                    'Société' => 'entreprise',
                    'Auto/Micro-Entrepreneur' => 'entrepreneur',
//                    'Société de portage' => 'portage',
                    'Indépendant/Personne physique' => 'personne',
                ),
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))

            ->add('raisonSociale', TextType::class, array(
                'required' => false,
                'label' => 'Raison Sociale',
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))

            ->add('formeSociete', ChoiceType::class, array(
                'label' => 'forme de la société',
                'required' => false,
                'choices' => array(
                    '...' => '',
                    'Association' => 'association',
                    'EIRL' => 'eirl',
                    'EURL' => 'eurl',
                    'SARL' => 'sarl',
                    'SAS' => 'sas',
                    'SA' => 'sa',
                    'SEC' => 'sec',
                    'SNC' => 'snc'
                ),
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('adresse', TextType::class, array(
                'required' => true,
                'label' => 'N° et Voie',
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('adresseComplement', TextType::class, array(
                'label' => 'Complement',
                'attr' => array(
                    'class' => 'input-sm not_required'
                )
            ))
            ->add('code_postal', TextType::class, array(
                'mapped' => false,
                'label' => "Code postal",
                'required' => true,
                'attr' => array(
                    "maxlength" => 5,
                    "class" => "input-sm"
                )
            ))
            ->add('ville', EntityType::class, array(
                'required' => true,
                'class' => 'ApplicationPlateformeBundle:Ville',
                'label' => 'Ville',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.nom', 'ASC')
                        ->setMaxResults( 1 );
                },
                'choice_label' => 'nom',
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('representantLegalCivilite', ChoiceType::class, array(
                'label' => 'Civilité',
                'required' => false,
                'choices' => array(
                    '...' => '',
                    'Monsieur' => 'm.',
                    'Madame' => 'mme',
                ),
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('representantLegalNom', TextType::class, array(
                'required' => false,
                'label' => 'Nom',
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('representantLegalPrenom', TextType::class, array(
                'required' => false,
                'label' => 'Prenom',
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('representantLegalFonction', ChoiceType::class, array(
                'label' => 'fonction',
                'required' => false,
                'choices' => array(
                    '...' => '',
                    'Gérant' => 'gerant',
                    'Président' => 'president',
                    'Directeur Associé' => 'associe',
                    'Directeur Général' => 'directeur',
                    'Autre' => 'autre',
                ),
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('intitule', TextType::class, array(
                'required' => false,
                'label' => 'Intitule',
                'attr' => array(
                    'class' => 'input-sm not_required'
                )
            ))
            ->add('siret', TextType::class, array(
                'required' => false,
                'label' => 'Siret',
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('attestationUrssaf', FileType::class, array(
                'label' => 'Attestation de vigilance urssaf',
                'required' => false,
                'data_class' => null,
                'attr' => array(
                    'class' => 'input-sm'
                )
            ))
            ->add('referencePortage', TextType::class, array(
                'required' => false,
                'label' => 'Votre référence dans la société de portage',
                'attr' => array(
                    'class' => 'input-sm'
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
            'data_class' => 'Application\UsersBundle\Entity\Facturation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'application_usersBundle_facturationType';
    }
}
