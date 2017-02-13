<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class HistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typerdv', HiddenType::class, array(
                'attr' => array('class' => 'letyperdv allchampH', 'value' => 'presenciel')
            ))
            ->add('summary',     ChoiceType::class, array(
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
                'attr'=>array('class'=>'allchampH')
                ))
            ->add('autreSummary', TextType::class, array(
                'attr'=>array('class'=>'allchampH', 'placeholder'=>'Titre de rendez-vous')
            )
            )
            ->add('description',     TextType::class, array(
                'attr'=>array('class'=>'allchampH'),
                'label' => 'Observation',
                'required' => false))
            ->add('dateDebut', DateType::class, array(
                'attr'=>array('class'=>'allchampH'),
                'label' => 'Date', 
                'attr' => array('class' => 'datedebut')
            ))
            ->add('dateFin', DateType::class, array(
                'attr' => array('class' => 'datefin allchampH')))
            ->add('heureDebut', TimeType::class, array(
                'attr'=>array('class'=>'allchampH'),
                'label' => 'Heure Debut',
            ))
            ->add('heureFin', TimeType::class, array(
                'attr'=>array('class'=>'allchampH'),
                'label' => 'Heure Fin'
            ))
            ->add('Enregistrer',  SubmitType::class, array(
                'label' => 'Enregistrer'
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
        return 'application_plateformebundle_historiqueType';
    }
}