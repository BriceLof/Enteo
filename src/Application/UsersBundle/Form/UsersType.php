<?php

namespace Application\UsersBundle\Form;

//use Application\UsersBundle\Form\DataTransformer\VilleToNumberTransformer;
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

class UsersType extends AbstractType
{
    /*private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }*/

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    //'Basic'             =>  "ROLE_USER",
                    'Gestionnaire' => "ROLE_GESTION",
                    'Consultant Référent' => "ROLE_REFERENT",
                    'Consultant' => "ROLE_CONSULTANT",
                    'Commercial' => "ROLE_COMMERCIAL",
                    'Administrateur' => "ROLE_ADMIN",
                ),
                //'mapped' => false,
                'label' => "Type Utilisateur *",
                'required' => true,
                'expanded' => true,
                'multiple' => true,
                //'data' => array("ROLE_USER"),
                'choice_attr' => function ($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
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
                /*'label_attr' => array('class' => 'format_label', 'style' => 'display:none;'),*/
                'choice_attr' => function ($val, $key, $index) {
                    return ['class' => 'format_input'];
                },
            ))
            // Me sert juste à activer l'ajax pour la recherche de ville correspondant
            ->add('departement', TextType::class, array(
                'mapped' => false,
                'label' => "Département *",
                'required' => true,
                'attr' => array("maxlength" => 2, "class" => "departementInputForAjax")
            ))
            ->add('codePostalHidden', HiddenType::class, array("mapped" => false))
            ->add('typeUserHidden', HiddenType::class, array("mapped" => false))
            ->add('idVilleHidden', HiddenType::class, array("mapped" => false));

        /* if(isset($options['data']))
         {
             //var_dump($options['data']);
             $builder->add('ville', EntityType::class, array(
                 'class' => 'ApplicationPlateformeBundle:Ville',
                 'label' => 'Ville *',
                 'query_builder' => function (EntityRepository $er) use ($options) {
                     return $er->createQueryBuilder('v')
                             ->where('v.id = :id')
                             ->setParameter('id',$options['data']->getVille()->getId() )
                         ->orderBy('v.nom', 'ASC')
                         ->setMaxResults( 1 );
                 },
                 'choice_label' => 'nom',
             ));
         }
         else{*/
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
        // }
        $builder->add('adresse', TextType::class, array(
            'label' => 'Adresse ',
            'required' => false,
        ));
        //if(isset($options['data'])) var_dump($options['data']->getVille());
        /*$builder->get('ville')
            ->addModelTransformer(new VilleToNumberTransformer($this->manager));*/
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
