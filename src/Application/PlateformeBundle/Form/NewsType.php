<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class NewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('statut', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:Statut',
                'choice_label' => 'nom', 
            ))
            ->add('detailStatut', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:DetailStatut',
                'choice_label' => 'detail', 
                'placeholder' => '',
            ))
            ->add('message', null, array(
                'label' => 'Message :'
            )) 
            ->add('Enregistrer', SubmitType::class, array(
                'attr' => array('class' => 'btn  btn-danger'),
            ))    
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\News'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_plateformebundle_news';
    }


}
