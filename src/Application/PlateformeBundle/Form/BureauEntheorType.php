<?php

namespace Application\PlateformeBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class BureauEntheorType extends AbstractType
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
            ->add('codePostal', TextType::class, array(
                'mapped' => false,
                'label' => "Code postal *",
                'required' => true,
                'attr' => array("maxlength" => 5, "class" => "codePostalInputForAjax")
            ))
            ->add('acces', TextareaType::class, array(
                'label' => 'Accès ',
                'required' => false
            ))
            ->add('commentaire', TextareaType::class, array(
                'label' => 'Commentaires ',
                'required' => false
            ))
            ->add('metaTitle')
            ->add('metaDescription')
            ->add('intro', TextareaType::class)
            ->add('content', CKEditorType::class)

            ->add('submit', SubmitType::class, array(
                'label' => "Enregistrer"
            ));
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
        return 'application_plateforme bureau_entheor_type';
    }
}
