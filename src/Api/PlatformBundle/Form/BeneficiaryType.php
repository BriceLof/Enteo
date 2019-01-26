<?php

namespace Application\PlateformeBundle\Form;

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
            ->add('civiliteConso', ChoiceType::class, array(
                'choices' => array(
                    'Monsieur' => 'M.',
                    'Madame' => 'Mme',
                    'Mademoiselle' => 'Mlle',
                )
            ))
            ->add('nomConso')
            ->add('prenomConso')
            ->add('poste')
            ->add('csp', ChoiceType::class, array(
                'choices' => array(
                    '...' => '',
                    'Demandeur d\'emploi' => 'demandeur d\'emploi',
                    'Ouvrier qualifié' => 'ouvrier qualifié',
                    'Employé' => 'employé',
                    'Technicien' => 'technicien',
                    'Agent de maitrise' => 'agent de maitrise',
                    'Cadre/ingenieur' => 'cadre/ingenieur',
                    'Chef d\'entreprise/PL' => 'chef d\'entreprise/PL',
                )
            ))
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    '...' => '',
                    'CDD' => 'cdd',
                    'CDI' => 'cdi',
                    'Interim' => 'interim',
                )
            ))
            ->add('telConso')
            ->add('tel2')
            ->add('email2')
            ->add('emailConso')
            ->add('adresse')
            ->add('adresseComplement')
            ->add('code_postal')
            ->add('regionTravail', ChoiceType::class, array(
                'choices' => array(
                    '...' => '',
                    'AUVERGNE-RHÔNE-ALPES' => 'Auvergne-Rhône-Alpes',
                    'BOURGOGNE-FRANCHE-COMPTÉ' => 'Bourgogne-Franche-Comté',
                    'BRETAGNE' => 'Bretagne',
                    'CENTRE-VAL DE LOIRE' => 'Centre-Val de Loire',
                    'CORSE' => 'Corse',
                    'GRAND EST' => 'Grand Est',
                    'HAUTS-DE-FRANCE' => 'Hauts-de-France',
                    'ÎLE-DE-FRANCE' => 'Île-de-France',
                    'NORMANDIE' => 'Normandie',
                    'NOUVELLE-AQUITAINE' => 'Nouvelle-Aquitaine',
                    'OCCITANIE' => 'Occitanie',
                    'PAYS DE LA LOIRE' => 'Pays de la Loire',
                    'PROVENCE-ALPES-CÔTE D\'AZUR' => 'Provence-Alpes-Côte d\'Azur',
                    'RÉGION DOM TOM' => 'Région Dom-Tom'
                )
            ))
            ->add('dptTravail')
            ->add('numSecu')
            ->add('numSecuCle')
            ->add('dateNaissance', DateType::class)
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
