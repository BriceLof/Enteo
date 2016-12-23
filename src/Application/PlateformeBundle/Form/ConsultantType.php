<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ConsultantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('consultant', EntityType::class, array(
                'class' => 'ApplicationUsersBundle:Users',
                'label' => 'Consultant',
                'placeholder' => 'choisissez votre consultant',
                'choice_label' => 'prenom'
            ))
            ->add('submit', SubmitType::class, array('label' => 'Modifier le consultant'));
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Beneficiaire'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_consultantType';
    }
}
