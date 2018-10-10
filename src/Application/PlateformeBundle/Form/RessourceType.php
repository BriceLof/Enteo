<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Application\PlateformeBundle\Entity\RessourceRubrique;

class RessourceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, array("label" => "Nom du fichier"))
            ->add('file', FileType::class, array('label' => 'Fichier (PDF file)',  'required' => false, 'data_class' => null))
            ->add('rubrique', EntityType::class, array(
                'placeholder' => 'Choisissez',
                'class' => 'ApplicationPlateformeBundle:RessourceRubrique',
                'choice_label' => 'nom'
            ))
            ->add('droit', ChoiceType::class, array(
                'choices' => array(
                    'Tous' => "all",
                    'Tous sauf consultant' => "all_except_consultant",
                    'Administrateur' => "admin",
                ),
                //'mapped' => false,
                'label' => "Droit d'accès *",
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'placeholder' => "Choisissez"


            ))
            ->add('submit', SubmitType::class, array(
                'label' => "Enregistrer",
                'attr' => array("class" => "btn btn-default")
            ));;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\PlateformeBundle\Entity\Ressource'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_plateformebundle_ressource';
    }


}
