<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class NewsType extends AbstractType
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
                        ->where('s.slug != :slug1')
                        ->andWhere('s.slug != :slug2')
                        ->andWhere('s.slug != :slug3')
                        ->andWhere('s.slug != :slug4')
                        ->andWhere('s.slug != :slug5')
                        ->andWhere('s.slug != :slug6')
                        ->setParameters(array('slug1' => 'dossier-en-cours', 'slug2' => 'financement', 'slug3' => 'recevabilite','slug4' => 'facturation','slug5' => 'reglement', 'slug6' => 'reporte'))
                        ;
                },
                'attr' => array(
                    'class' => 'news'
                )
            ))
            ->add('detailStatut', EntityType::class, array(
                'placeholder' => 'Choisissez',
                'class' => 'ApplicationPlateformeBundle:DetailStatut',
                'choice_label' => 'detail',
                'attr' => array(
                    'class' => 'news'
                )
            ))

            ->add('motif', TextareaType::class, array(
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                ),
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