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
                'placeholder' => 'Choisissez',
                'class' => 'ApplicationPlateformeBundle:Statut',
                'choice_label' => 'nom', 
                'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('s')
                                ->where('s.slug = :slug1')
                                ->orWhere('s.slug = :slug2')
                                ->orWhere('s.slug = :slug3')
                                ->orWhere('s.slug = :slug4')
                                ->setParameters(array('slug1' => 'dossier-en-cours', 'slug2' => 'financement', 'slug3' => 'facturation','slug4' => 'reglement'))
                            ;
                },
            ))
            ->add('detailStatut', EntityType::class, array(
                'placeholder' => 'Choisissez',
                'class' => 'ApplicationPlateformeBundle:DetailStatut',
                'choice_label' => 'detail', 
                'placeholder' => '',
            ))
            ->add('info', TextType::class, array(
                'label' => 'News',
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'suivi'
                ), 
                'required' => false
            ))
            ->add('typeFinanceur', ChoiceType::class, array(
                'mapped' => false,
                'required' => false,
                'label' => 'Type Financeur',
                'choices' => array(
                    '...' => '',
                    'OPCA' => 'OPCA',
                    'FONGECIF' => 'FONGECIF',
                    'Entreprise' => 'Entreprise',
                    'Bénéficiaire' => 'Beneficiaire',
                    'Pôle Emploi' => 'Pole Emploi',
                ),
                'attr' => array(
                    'class' => 'suivi type_employeur',
                    'onchange' => 'changeOrganisme(this)',
                )
            ))

            ->add('organisme', TextType::class, array(
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'suivi organisme',
                ),
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
