<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AvisType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('beneficiaire', TextType::class, array(
                'label' => 'Bénéficiaire',
                'required' => false,
                'mapped' => false,
                'attr' => array('class' => 'beneficiaireSearchField',
                    'placeholder' => 'Entrer nom bénéficiaire'),
            ))
            ->add('noteGlobale', ChoiceType::class, array(
                'label' => 'Note globale /5',
                'choices'  => array(
                    '' => null,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ),
                'expanded' => false,
                'multiple' => false,
                'required' => true
            ))

            ->add('commentaireGeneral', TextareaType::class, array(
                'label' => 'Commentaire général',
                'required' => true
            ))
            ->add('recommendationAmi', ChoiceType::class, array(
                'label' => 'Recommandation à un ami',
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false
                ),
                'expanded' => true,
                'multiple' => false,
                'required' => true
            ))
            ->add('autorisationPublication', ChoiceType::class, array(
                'label' => 'Autorisation de publication',
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false
                ),
                'expanded' => true,
                'multiple' => false,
                'required' => true
            ))
            ->add('autorisationPublicationEntheor', ChoiceType::class, array(
                'label' => 'Publication Entheor',
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false
                ),
                'expanded' => true,
                'multiple' => false,
                'required' => true
            ))
            ->add('submit', SubmitType::class, array(
                'label' => "Enregistrer"
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Avis'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_plateformebundle_avis';
    }


}
