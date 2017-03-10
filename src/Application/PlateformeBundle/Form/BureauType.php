<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


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
                    'attr' => array("maxlength" => 5, "class" => "codePostalInputForAjax")
                ))
            ->add('codePostalHidden', HiddenType::class, array("mapped" => false))
            ->add('idVilleHidden', HiddenType::class, array("mapped" => false))
            ->add('ville', EntityType::class, array(
                    'class' => 'ApplicationPlateformeBundle:Ville',
                    'label' => 'Ville *',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('v')
                            ->orderBy('v.nom', 'ASC')
                            ->setMaxResults( 1 );
                    },
                    'choice_label' => 'nom',
            ))
            ->add('observation', TextareaType::class, array(
                'label' => 'Observation ',
            ))               
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
        return 'application_plateformebundle_bureauType';
    }
}
