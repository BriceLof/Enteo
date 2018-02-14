<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class FactureFiltreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('beneficiaire', TextType::class, array(
                'required' => false,
                'mapped' => false,
                'attr' => array('class' => 'beneficiaireSearchFactureField',
                    'placeholder' => 'Entrer nom bénéficiaire'),
            ))
            ->add('consultant', EntityType::class, array(
                'placeholder' => 'Sélectionner',
                'required' => false,
                'mapped' => false,
                'class' => 'ApplicationUsersBundle:Users',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role ')
                        ->setParameter('role', '%ROLE_CONSULTANT%')
                        ->orderBy('u.nom', 'ASC')
                        ;
                },
                'attr' => array(
                    'class' => ''
                )
            ))
            ->add('statut', ChoiceType::class, array(
                'label' => 'Statut',
                'placeholder' => 'Sélectionner',
                'choices'  => array(
                    'Sent' => 'sent',
                    'Paid' => 'paid',
                    'Paid partiel' => 'partiel',
                    'Perte et profit' => 'perte et profit',
                    'Error' => 'error',
                ),
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'statutPaiementFacture'
                )
            ))
            ->add('date_debut_accompagnement_start', DateType::class, array(
                'label' => 'Début accompagnement ',
                'widget' => 'single_text',
                'html5' => true,
                'mapped' => false,
                'required' => false,
                //'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => ' '
                )
            ))
            ->add('date_debut_accompagnement_end', DateType::class, array(
                'label' => 'Début accompagnement ',
                'widget' => 'single_text',
                'html5' => true,
                'mapped' => false,
                'required' => false,
                //'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => ' '
                )
            ))

            ->add('date_fin_accompagnement_start', DateType::class, array(
                'label' => 'Fin accompagnement ',
                'widget' => 'single_text',
                'html5' => true,
                'mapped' => false,
                'required' => false,
                //'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => ' '
                )
            ))
            ->add('date_fin_accompagnement_end', DateType::class, array(
                'label' => 'Fin accompagnement ',
                'widget' => 'single_text',
                'html5' => true,
                'mapped' => false,
                'required' => false,
                //'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => ' '
                )
            ))
            ->add('date_facture_start', DateType::class, array(
                'label' => 'Date facture',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'mapped' => false,
                //'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => 'dateFactureField dateFactureStartField'
                )
            ))
            ->add('date_facture_end', DateType::class, array(
                'label' => 'Date facture',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                'mapped' => false,
                //'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => 'dateFactureField dateFactureEndField'
                )
            ))
            ->add('numero_facture', TextType::class, array(
                'label' => 'N° facture',
                'required' => false,
                'mapped' => false,
                'attr' => array(
                    'placeholder' => 'XXX'
                ),
            ))
            ->add('annee_numero_facture', ChoiceType::class, array(
                'label' => 'N° facture',
                'placeholder' => 'Année',
                'choices'  => array(
                    '2017' => '-2017',
                    '2018' => '-2018',
                    '2019' => '-2019',
                ),
                //'preferred_choices' => array('-'.date('Y')),
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'class' => ''
                )
            ))
            ->add('financeur', TextType::class, array(
                'label' => 'Financeur',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Entrer le nom d\'un financeur'
                ),
            ))
            ->add('filtrer', SubmitType::class, array(
                'label' => "Filtrer",
                'attr' => array('class' => 'btn  btn-primary'),
            ));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Facture'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_plateformebundle_factureFiltre';
    }


}
