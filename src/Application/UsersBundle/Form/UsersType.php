<?php

namespace Application\UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UsersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder
                ->add('roles', ChoiceType::class, array(
                    'choices' => array(
                        'Administrateur'    =>  "ROLE_ADMIN",
                        'Gestionnaire'      =>  "ROLE_GESTION",
                        'Consultant'        =>  "ROLE_CONSULTANT",
                        'Commercial'        =>  "ROLE_COMMERCIAL",
                    ),
                    'label' => "Type Utilisateur",
                    'multiple' => true,
                    'expanded' => true
                    
                ))
                ->add('calendrierid', TextType::class, array(
                    'label' => 'Calendrier ID',
                ))
                ->add('calendrieruri', TextareaType::class, array(
                    'label' => 'Calendrier URI',
                ))
                ->add('nom')
                ->add('prenom')
                ->add('ville')
                ->add('distanciel')
                /*->add('Modifier', SubmitType::class, array(
                'attr' => array('class' => 'btn  btn-primary'),
            )) */ 
            ;
    }
    
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }
    
   
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UsersBundle\Entity\Users'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_usersbundle_users';
    }


}
