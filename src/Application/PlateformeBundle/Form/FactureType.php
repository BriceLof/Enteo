<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_financeur', TextType::class, array(
                'required' => true,
                'attr' =>array('placeholder' => 'Nom du financeur'),
                'mapped' => false
            ))
            ->add('rue_financeur', TextType::class, array(
                'required' => true,
                'attr' =>array('placeholder' => 'Rue du financeur'),
                'mapped' => false
            ))
            ->add('code_postal_financeur', TextType::class, array(
                'required' => true,
                'attr' =>array('placeholder' => 'Code postal et ville du financeur'),
                'mapped' => false
            ))

            ->add('montant', TextType::class, array(
                'required' => true,
                'attr' =>array('placeholder' => 'Montant en €')
            ))
            ->add('numero_ref', TextType::class, array(
                'required' => false,
                'attr' =>array('placeholder' => 'N° de référence')
            ))
            ->add('code_adherent', TextType::class, array(
                'required' => false,
                'attr' =>array('placeholder' => 'Code adhérent')))
            ->add('detail_accompagnement', TextType::class, array(
                'required' => false,
                'attr' =>array('placeholder' => 'Détail de l\'accompagnement')))
            ->add('info_paiement', TextType::class, array(
                'label' => 'Info paiement',
                'required' => false,
                'attr' =>array('placeholder' => 'Ex : Paiement comptant à réception')))

            ->add('save', SubmitType::class, array(
                'label' => "Générer",
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
        return 'application_plateformebundle_facture';
    }


}
