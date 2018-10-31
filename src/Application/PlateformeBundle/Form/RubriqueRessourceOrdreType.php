<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RubriqueRessourceOrdreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rubrique', CollectionType::class, array(
                    'entry_type' => TextType::class,
                    'mapped' => false
                )
            )

            ->add('submit', SubmitType::class, array(
                'label' => "Enregistrer",
                'attr' => array("class" => "btn btn-default")
            ));;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\RessourceRubrique'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_plateformebundle_rubrique_ressource_ordre';
    }


}
