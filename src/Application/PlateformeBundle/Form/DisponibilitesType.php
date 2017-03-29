<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DisponibilitesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDebuts', DateTimeType::class, array(
                'attr'=>array('class'=>'allchampH datedebuts')
            ))
            ->add('dateFins', DateTimeType::class, array(
                'attr' => array('class' => 'datefins allchampH'))
            )
            ->add('Enregistrer',  SubmitType::class, array(
                'label' => 'Enregistrer'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Disponibilites'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_plateformebundle_disponibilites';
    }


}
