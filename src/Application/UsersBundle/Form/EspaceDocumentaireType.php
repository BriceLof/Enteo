<?php

namespace Application\UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class EspaceDocumentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userDocuments', CollectionType::class, array(
                'entry_type'   => UserDocumentType::class,
                'label' => false,
                'allow_add' => true,
                'prototype' => true,
                'data_class' => null,
                'prototype_name' => 'b'
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UsersBundle\Entity\Users'
        ));
    }

    public function getName()
    {
        return 'application_usersbundle_espaceDocumentaireType';
    }
}
