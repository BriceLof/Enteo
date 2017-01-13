<?php

namespace Application\UsersBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
                        'Basic'             =>  "ROLE_USER",
                        'Gestionnaire'      =>  "ROLE_GESTION",
                        'Consultant'        =>  "ROLE_CONSULTANT",
                        'Commercial'        =>  "ROLE_COMMERCIAL",
                        'Administrateur'    =>  "ROLE_ADMIN",
                    ),
                    'label' => "Type Utilisateur *",
                    'multiple' => true,
                    'expanded' => true,
                    'data' => array("ROLE_USER"),
                    'choice_attr' => function($val, $key, $index) {
                       // adds a class like attending_yes, attending_no, etc
                       return ['class' => 'role_user'];
                    },
                    
                ))
                ->add('calendrierid', TextType::class, array(
                    'label' => 'Calendrier ID',
                    'label_attr' => array('style' => 'display:none;', 'class' => "calendar_id"),
                    'required' => false,
                    'attr'  => array('style' => 'display:none;', 'class' => "calendar_id")
                ))
                ->add('calendrieruri', TextareaType::class, array(
                    'label' => 'Calendrier URI',
                    'label_attr' => array('style' => 'display:none;', 'class' => "calendar_uri"),
                    'required' => false,
                    'attr'  => array('style' => 'display:none;', 'class' => "calendar_uri")
                ))
                ->add('civilite', ChoiceType::class, array(
                    'choices' => array(
                        'Mr'                =>  "mr",
                        'Mme'               =>  "mme",
                    ),
                    'label' => "Civilité",
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true,
                    'data'     => "mr",
                    
                ))
                ->add('nom', null, array(
                    'required'  => true
                ))
                ->add('prenom', null, array(
                    'required'  => true
                ))
                ->add('tel1', TextType::class, array(
                    'label'     => 'Tél 1 *', 
                    'required'  => true
                ))
                ->add('tel2', TextType::class, array(
                    'label'     => 'Tél 2', 
                    'required'  => false
                ))
                ->add('email2', EmailType::class, array(
                    'label'     => 'Email 2', 
                    'required'  => false
                ))
                ->add('distanciel', ChoiceType::class, array(
                    'choices' => array('Présentiel'    =>  0, 'Distanciel'    =>  1 ),
                    'expanded' => true,
                    'multiple' => false,
                    'data' => 0,
                    'label_attr' => array('class' => 'format_label', 'style' => 'display:none;'),
                    'choice_attr' => function($val, $key, $index) {
                       return ['class' => 'format_input'];
                    },
                ))
                          
                            
                ->add('ville', EntityType::class, array(
                    'class' => 'ApplicationPlateformeBundle:Ville',
                    'label' => 'Ville *',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('v')
                            ->orderBy('v.nom', 'ASC')
                            ->setMaxResults( 2 );
                    },
                    'choice_label' => 'nom',
                ))
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
