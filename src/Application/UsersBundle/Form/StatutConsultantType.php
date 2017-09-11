<?php

namespace Application\UsersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class StatutConsultantType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $serviceStatut = $options['application_users_statut'];

        $builder
            ->add('statut', ChoiceType::class, array(
                'choices' => $serviceStatut->getTabStatut(),
                'label_attr' => array(
                    'style' => 'padding-top :0;'
                )
            ))

            ->add('detail', ChoiceType::class, array(
                'choices' => $serviceStatut->getTabDetail(),
                'expanded' => true,
                'multiple' => true
            ))

            ->add('dates', CollectionType::class, array(
                'entry_type' => DateType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'entry_options' => array(
                    'attr' => array(
                        'class' => 'date',
                    ),
                    'widget' => 'single_text',
                    'label' => false,
                    'format' => 'dd/MM/yyyy',
                ),
                'label' => false
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UsersBundle\Entity\StatutConsultant'
        ));
        $resolver->setRequired('application_users_statut');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'application_usersbundle_statut_consultant';
    }


}
