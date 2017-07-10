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
use Doctrine\ORM\EntityRepository;

class StatutRecevabiliteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('statut', EntityType::class, array(
                'placeholder' => 'Choisissez',
                'class' => 'ApplicationPlateformeBundle:Statut',
                'choice_label' => 'nom', 
                'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('s')
                                ->where('s.slug = :slug1')
                                ->setParameters(array('slug1' => 'recevabilite'))
                            ;
                },
                'attr' => array(
                    'class' => ''
                )
            ))
            ->add('detailStatut', EntityType::class, array(
                'placeholder' => 'Choisissez',
                'class' => 'ApplicationPlateformeBundle:DetailStatut',
                'choice_label' => 'detail', 
                'attr' => array(
                    'class' => ''
                )
            ))
           
            ->add('Enregistrer', SubmitType::class, array(
                'attr' => array('class' => 'btn  btn-primary'),
            ))    
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\StatutRecevabilite'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_plateformebundle_statutRecevabilite';
    }


}
