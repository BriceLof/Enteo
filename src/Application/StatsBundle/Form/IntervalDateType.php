<?php

namespace Application\StatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IntervalDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', TextType::class, array(
                'label' => 'Début',
                'required' => false,
                'mapped' => false,
                'attr' => array('class' => 'datepickerBrice',
                    'placeholder' => 'JJ/MM/AAAA'),
            ))

            ->add('dateTo', TextType::class, array(
                'label' => 'Fin',
                'required' => false,
                'mapped' => false,
                'attr' => array('class' => 'datepickerBrice',
                    'placeholder' => 'JJ/MM/AAAA'),
            ))

            ->add('submit', SubmitType::class, array(
                'label' => 'OK',
                'attr' => array('class' => 'btn btn-primary')
            ))
        ;
    }


}