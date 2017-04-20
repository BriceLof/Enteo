<?php

namespace Application\PlateformeBundle\Form;

use Application\PlateformeBundle\Entity\ContactEmployeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

            ->add('civiliteConso', ChoiceType::class, array(
                'label' => 'Civilité ',
                'choices' => array(
                    'Monsieur' => 'M.',
                    'Madame' => 'Mme',
                    'Mademoiselle' => 'Mlle',
                ),
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
                'label' => 'Statut dans le poste ',
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
                'label' => 'Type Contrat ',
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
                'attr' => array("maxlength" => 5, "class" => "codePostalInputForAjaxBeneficiaire")
            ))
            ->add('codePostalHiddenBeneficiaire', HiddenType::class, array("mapped" => false))
            ->add('idVilleHiddenBeneficiaire', HiddenType::class, array("mapped" => false))
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
			
			->add('regionTravail', ChoiceType::class, array(
                'label' => 'Région du travail',
                'required' => false,
                'choices' => array(
                    '...' => '',
                    'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
                    'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
                    'Bretagne' => 'Bretagne',
                    'Centre-Val de Loire' => 'Centre-Val de Loire',
                    'Corse' => 'Corse',
                    'Grand Est' => 'Grand Est',
                    'Hauts-de-France' => 'Hauts-de-France',
					'Île-de-France' => 'Île-de-France',
                    'Normandie' => 'Normandie',
                    'Nouvelle-Aquitaine' => 'Nouvelle-Aquitaine',
                    'Occitanie' => 'Occitanie',
                    'Pays de la Loire' => 'Pays de la Loire',
					'Provence-Alpes-Côte d\'Azur' => 'Provence-Alpes-Côte d\'Azur',
                ),
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

            ->add('contactEmployeur', CollectionType::class, array(
                'entry_type' => ContactEmployeurType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
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
