<?php

namespace Application\UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

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
                    'Gestionnaire' => "ROLE_GESTION",
                    'Consultant Référent' => "ROLE_REFERENT",
                    'Consultant' => "ROLE_CONSULTANT",
                    'Commercial' => "ROLE_COMMERCIAL",
                    'Administrateur' => "ROLE_ADMIN",
                ),
                'label' => "Type Utilisateur *",
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                'choice_attr' => function ($val, $key, $index) {
                    return ['class' => 'role_user'];
                },

            ))
            ->add('calendrierid', TextType::class, array(
                'label' => 'Calendrier ID',
                'required' => false,
            ))
            ->add('calendrieruri', TextareaType::class, array(
                'label' => 'Calendrier URI',
                'required' => false,
            ))
            ->add('civilite', ChoiceType::class, array(
                'choices' => array(
                    'M.' => "m.",
                    'Mme' => "mme",
                ),
                'label' => "Civilité",
                'multiple' => false,
                'expanded' => true,
                'required' => true,
            ))
            ->add('nom', null, array(
                'required' => true
            ))
            ->add('prenom', null, array(
                'required' => true
            ))
            ->add('tel1', TextType::class, array(
                'label' => 'Tél 1 *',
                'required' => true
            ))
            ->add('tel2', TextType::class, array(
                'label' => 'Tél 2',
                'required' => false
            ))
            ->add('email2', EmailType::class, array(
                'label' => 'Email 2',
                'required' => false
            ))
            ->add('distanciel', ChoiceType::class, array(
                'choices' => array('Présentiel' => 'presentiel', 'Distanciel' => 'distanciel'),
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'choice_attr' => function ($val, $key, $index) {
                    return ['class' => 'format_input'];
                },
            ))
            ->add('departement', TextType::class, array(
                'mapped' => false,
                'label' => "Département *",
                'required' => true,
                'attr' => array("maxlength" => 2, "class" => "departementInputForAjax")
            ))
            ->add('codePostalHidden', HiddenType::class, array("mapped" => false))
            ->add('typeUserHidden', HiddenType::class, array("mapped" => false))
            ->add('idVilleHidden', HiddenType::class, array("mapped" => false))
            ->add('description', CKEditorType::class, array(
                'required' => false,
            ))
            ->add('bureau', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:Bureau',
                'label' => 'Bureau *',
                'placeholder' => '...',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->where('b.enabledEntheor = 1');
                },
                'required' => false
            ))
        ;

        $builder->add('ville', EntityType::class, array(
            'class' => 'ApplicationPlateformeBundle:Ville',
            'label' => 'Ville *',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('v')
                    ->orderBy('v.nom', 'ASC')
                    ->setMaxResults(1);
            },
            'choice_label' => 'nom',
        ));
        $builder->add('adresse', TextType::class, array(
            'label' => 'Adresse ',
            'required' => false,
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
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
