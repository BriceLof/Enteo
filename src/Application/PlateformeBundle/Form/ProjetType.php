<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProjetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statut', TextType::class, array(
                'label' => 'Statut actuel :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))
            ->add('experience', TextType::class, array(
                'label' => 'Années Expérience :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))
            ->add('heureDif', IntegerType::class, array(
                'label' => 'Heures DIF dispo :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))
            ->add('heureCpf', IntegerType::class, array(
                'label' => 'Heures CPF dispo :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))

            ->add('typeFinanceur', ChoiceType::class, array(
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
                    'class' => 'projet type_employeur',
                    'onchange' => 'changeOrganisme(this)',
                )
            ))

            ->add('organisme', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'class' => 'projet organisme',
                ),
            ))

            ->add('refFinanceur', TextType::class, array(
                'label' => 'Réf. financeur',
                'required' => false,
                'attr' => array(
                    'class' => 'projet',
                    'maxlength' => 24,
                ),
            ))

            ->add('formationInitiale', TextType::class, array(
                'label' => 'Formation Initiale :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))
            ->add('diplomeVise', TextType::class, array(
                'label' => 'Diplôme visé :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))

            ->add('ecoleUniversite', TextType::class, array(
                'label' => 'Ecole/Université :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))
			
			->add('diplomeObtenu', ChoiceType::class, array(
                'required' => false,
                'label' => 'Diplôme obtenu :',
                'choices' => array(
                    'Sans niveau spécifique' => 'sans niveau',
                    'Niveau VI' => 'niveau vi',
                    'Niveau V bis (préqualification)' => 'niveau vi bis',
                    'Niveau V (CAP, BEP, CFPA)' => 'niveau v',
                    'Niveau IV (BAC, BP, BT)' => 'niveau iv',
					'Niveau III (BTS, DUT)' => 'niveau iii',
                    'Niveau II (licence ou maîtrise)' => 'niveau ii',
                    'Niveau I (sup. à la maîtrise)' => 'niveau i',
                ),
                'attr' => array(
					'placeholder' => '',
                    'class' => 'projet',
                )
            ))
			
            ->add('motivation', TextareaType::class, array(
                'label' => 'Motivation :',
                'required' => false,
                'attr' => array(
                    'placeholder' => '',
                    'class' => 'projet'
                )
            ))
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
        return 'application_plateformebundle_projetType';
    }
}