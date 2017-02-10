<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class SuiviAdministratifType extends AbstractType
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
                'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('s')
                                ->where('s.slug = :slug1')
                                ->orWhere('s.slug = :slug2')
                                ->orWhere('s.slug = :slug3')
                                ->setParameters(array('slug1' => 'recevabilite', 'slug2' => 'financement','slug3' => 'facturation'))
                            ;
                },
            ))
            ->add('detailStatut', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:DetailStatut',
                'choice_label' => 'detail', 
                'placeholder' => '',
            ))
            ->add('info', TextType::class, array(
                'label' => 'News',
                'attr' => array(
                    'placeholder' => '',
                ), 
                'required' => false
            ))
            ->add('submit', SubmitType::class, array('label' => 'Ajouter')
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\SuiviAdministratif'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'application_plateformebundle_suiviAdministratifType';
    }


}
