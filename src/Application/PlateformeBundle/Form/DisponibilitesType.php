<?php
namespace Application\PlateformeBundle\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
class DisponibilitesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class, array(
                "mapped" => false,
                'data' => (new \DateTime('now'))->modify('+1 day'),
            ))
            ->add('dateDebuts', TimeType::class, array(
                'label' => 'Heure Début',
                'minutes' => array(0,15,30,45),
                'attr'=>array(
                    'class'=>''
                )
            ))
            ->add('dateFins', TimeType::class, array(
                'label' => 'Heure Fin',
                'minutes' => array(0,15,30,45),
                'attr' => array(
                    'class' => ''
                )
            ))
            ->add('villeNom', TextType::class, array(
                'label' => 'Ville ',
                "mapped" => false,
                "required" => false,
                'attr' => array(
                    'placeholder' => 'Veuillez saisir la ville',
                    'autocomplete' => 'off'
                )
            ))
            ->add('villeId', IntegerType::class, array(
                "mapped" => false,
                'attr' => array(
                    'style' => 'display:none;'
                ),
                'required' => false,
                'label_attr' => array(
                    'style' => 'display:none;'
                )
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