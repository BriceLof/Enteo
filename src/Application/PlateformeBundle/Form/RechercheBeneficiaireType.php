<?php

namespace Application\PlateformeBundle\Form;

use Application\PlateformeBundle\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RechercheBeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nomConso', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Nom',
                )
            ))
            ->add('prenomConso', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Prénom',
                )
            ))
            ->add('emailConso', TextType::class, array(
                'label' => '',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Email',
                )
            ))
            ->add('consultant', EntityType::class, array(
                'placeholder' => 'Consultant',
				'required' => false,
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
			
			->add('statut', EntityType::class, array(
				"mapped" => false,
				'required' => false,
                'placeholder' => 'Statut',
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
                "mapped" => false,
                'required' => false,
                'placeholder' => 'Détail Statut',
                'class' => 'ApplicationPlateformeBundle:DetailStatut',
                'choice_label' => 'detail', 
            ))
			
            ->add('ville', TextType::class, array(
                "mapped" => false,
                "required" => false,
                'attr' => array(
                    'placeholder' => 'Veuillez saisir la ville',
                    'autocomplete' => 'off'
                )
            ))

            ->add('refFinanceur', TextType::class, array(
                'label' => 'Réf. Financeur',
                'required' => false,
            ))

            ->add('tri', HiddenType::class, array(
                'mapped' => false,
                'data' => 0
            ))

            ->add('page', HiddenType::class, array(
                'mapped' => false,
                'data' => 0
            ))

            ->add('submit', SubmitType::class, array('label' => 'Mettre à jour'));

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
        return 'application_plateformebundle_rechercheBeneficiaireType';
    }
}