<?php

namespace Application\UsersBundle\Form;

use Application\UsersBundle\Form\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class UserDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documents', CollectionType::class, array(
                'entry_type'   => DocumentType::class,
                'label' => false,
                'allow_add' => true,
                'prototype' => true,
                'data_class' => null,
                'prototype_name' => 'f',
                'mapped' => false
            ))
            ->add('name', TextType::class, array(

            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UsersBundle\Entity\UserDocument'
        ));
    }

    public function getName()
    {
        return 'application_users_user_documentType';
    }
}
