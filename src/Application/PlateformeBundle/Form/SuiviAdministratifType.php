<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SuiviAdministratifType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                'label' => 'date',
                'attr' => array(
                    'placeholder' => 'now',
                )
            ))
            ->add('quoi', ChoiceType::class, array(
                'label' => 'Quoi',
                'attr' => array(
                    'placeholder' => '',
                ),
                'choices' => array(
                    'Devis' => 'Devis',
                    'Programme' => 'Programme',
                    'Accord Devis' => 'Accord Devis',
                    'Recevabilité' => 'Recevabilité',
                    'Accord OPCA' => 'Accord OPCA',
                    'Facture d\'acompte' => 'Facture d\'acompte',
                    'Feuille de Présence OPCA' => 'Feuille de Présence OPCA',
                    'Facture solde' => 'Facture solde',
                    'Rappel Financeur' => 'Rappel Financeur',
                    'Résultat Enquête Satisfaction' => 'Résultat Enquête Satisfaction'
                )
            ))
            ->add('qui', TextType::class, array(
                'label' => 'Qui',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('submit', SubmitType::class, array('label' => 'Enregistrer')
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\SuiviAdministratif'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'application_plateformebundle_suiviAdministratifType';
    }


}
