<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('summary',     TextType::class, array(
                'label' => 'Titre evenement',
                'attr' => array(
                    'placeholder' => '')))
            ->add('description',     TextType::class, array(
                'label' => 'Description evenement',
                'attr' => array(
                    'placeholder' => '')))
            ->add('dateDebut', DateType::class, array(
                'label' => 'Debut evenement',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('dateFin', DateType::class, array(
                'label' => 'Fin evenement',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('heureDebut', TimeType::class, array(
                'label' => 'Heure Debut',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('heureFin', TimeType::class, array(
                'label' => 'Heure Fin',
                'attr' => array(
                    'placeholder' => '',
                )
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