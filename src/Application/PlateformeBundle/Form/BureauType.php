<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;


class BureauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombureau', TextType::class, array(
                'label' => 'nom ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('adresse', TextType::class, array(
                'label' => 'adresse ',
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('code_postal', TextType::class, array(
                'mapped' => false,
                'label' => "Code postal *",
                'required' => true,
                'attr' => array(
                    "maxlength" => 5,
                    "autocomplete" => 'disabled',
                    "onkeyup" => 'getVille(this, $("#bureau_ville_select"))'
                )
            ))
            ->add('ville_select', ChoiceType::class, array(
                "mapped" => false,
            ))
            ->add('acces', TextareaType::class, array(
                'label' => 'Accès ',
                'required' => false
            ))
            ->add('commentaire', TextareaType::class, array(
                'label' => 'Commentaires ',
                'required' => false
            ))
            ->add('metaTitle', TextareaType::class)
            ->add('metaDescription', TextareaType::class)
            ->add('intro', CKEditorType::class)
            ->add('content', CKEditorType::class)
            ->add('enabledEntheor', ChoiceType::class, array(
                'choices' => array(
                    'Non' => 0,
                    'Oui' => 1
                ),
                'attr' => array(
                    "onchange" => 'showEntheorField(this)'
                )
            ))
            ->add('banner_image', FileType::class, array(
                'required' => false,
                'mapped' => false
            ))
            ->add('first_image', FileType::class, array(
                'required' => false, 'mapped' => false
            ))
            ->add('second_image', FileType::class, array(
                'required' => false, 'mapped' => false
            ))
            ->add('third_image', FileType::class, array(
                'required' => false, 'mapped' => false
            ));
        $builder->get('ville_select')->resetViewTransformers();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Bureau'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_bureauType';
    }
}
