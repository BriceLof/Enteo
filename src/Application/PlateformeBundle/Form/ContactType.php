<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nature', ChoiceType::class, array(
                'label' => 'Nature du Contact ',
                'choices' => array(
                    'Contact RH' => 'contact rh',
                    'Signataire Convention' => 'signataire convention',
                    'Manager' => 'manager',
                ),
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('civilite', TextType::class, array(
                'label' => 'Civilité ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('nom', TextType::class, array(
                'label' => 'Nom ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('prenom', TextType::class, array(
                'label' => 'Prenom ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('tel', TextType::class, array(
                'label' => 'Tél ',
                'required' => false,
                'attr' => array(
                    'maxlength' => 14,
                    'placeholder' => '',
                    'class' => 'telephoneConso fiche'
                )
            ))
            ->add('tel', TextType::class, array(
                'label' => 'Tél 2 ',
                'required' => false,
                'attr' => array(
                    'maxlength' => 14,
                    'placeholder' => '',
                    'class' => 'telephoneConso fiche'
                )
            ))
            ->add('email', TextType::class, array(
                'label' => 'Email ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ))
            ->add('champsLibre', TextareaType::class, array(
                'label' => 'Autres renseignement ',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                )
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Contact'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_contactType';
    }
}


