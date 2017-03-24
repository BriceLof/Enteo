<?php

namespace Application\PlateformeBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminCalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('consultant', EntityType::class, array(
                'class' => 'ApplicationUsersBundle:Users',
                'label' => 'Consultant ',
                'placeholder' => 'choisissez',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :type')
                        ->setParameter('type', '%consultant%');
                },
            ))

            ->add('beneficiaire', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:Beneficiaire',
                'label' => 'beneficiaire',
                'choice_label' => 'nomConso',
            ))

            ->add('nom', TextType::class, array(
                'label' => 'Nom bénéficiaire',
                "mapped" => false
            ))

            ->add('prenom', TextType::class, array(
                'label' => 'Prenom bénéficiaire',
                "mapped" => false
            ))

            ->add('ville', TextType::class, array(
                "mapped" => false
            ))

            ->add('nomBureau', TextType::class, array(
                "mapped" => false,
                'label' => 'Nom du Bureau',
            ))

            ->add('adresseBureau', TextType::class, array(
                "mapped" => false,
                 'label' => 'Adresse',
            ))

            ->add('cpBureau', TextType::class, array(
                "mapped" => false,
                "label" => 'Code postal'
            ))

            ->add('typerdv', ChoiceType::class, array(
                'choices' => array(
                    'presentiel' => 'presentiel',
                    'distantiel' => 'distantiel',
                ),
                'expanded' => true,
                'multiple' => false,
                'label' => 'Type de Rendez-vous'
            ))

            ->add('bureau', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:Bureau',
                'label' => 'Bureau',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.nombureau', 'ASC')
                        ;
                },
                'choice_label' => 'nombureau',
            ))

            ->add('summary', ChoiceType::class, array(
                'choices' => array(
                    'Selectionner un rendez-vous' => '',
                    'RV1'=>'RV1',
                    'RV2'=>'RV2',
                    'RV Livret1'=>'RV Livret1',
                    'RV Livret2'=>'RV Livret2',
                    'RV Preparation jury'=>'RV Preparation jury',
                    'Autre'=>'Autre'
                ),
                'label' => 'Titre evenement',
                'attr'=>array('class'=>'Titre Evenement')
            ))

            ->add('dateDebut', DateType::class, array(
                'attr'=>array('class'=>'dateDebut'),
                'label' => 'Date',
            ))

            ->add('heureDebut', TimeType::class, array(
                'attr'=>array('class'=>''),
                'label' => 'Heure Debut',
            ))

            ->add('heureFin', TimeType::class, array(
                'attr'=>array('class'=>''),
                'label' => 'Heure Fin'
            ))

            ->add('description', TextType::class, array(
                'attr'=>array('class'=>''),
                'label' => 'Observation',
                'required' => false
            ))

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Historique',
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_admin_calendar_type';
    }
}