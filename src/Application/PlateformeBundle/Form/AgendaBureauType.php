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


class AgendaBureauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array(
                'label' => 'Nom ',
            ))
            ->add('calendrierId', TextType::class, array(
                'label' => 'Calendrier ID ',
                
            ))
            ->add('calendrierUri', TextareaType::class, array(
                'label' => 'Calendrier URI ',
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
            'data_class' => 'Application\PlateformeBundle\Entity\AgendaBureau'
        ));
    }

    public function getName()
    {
        return 'application_plateformebundle_agendaBureauType';
    }
}
