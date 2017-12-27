<?php

namespace Application\PlateformeBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturePaiementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statut', ChoiceType::class, array(
                'label' => 'Statut',
                'placeholder' => 'Selectionner',
                'choices'  => array(
                    'Sent' => 'sent',
                    'Paid' => 'paid',
                    'Paid partiel' => 'partiel',
                    'Perte et profit' => 'perte et profit',
                    'Error' => 'error',
                ),
                'required' => true,
                'attr' => array(
                    'class' => 'statutPaiementFacture'
                )
            ))
            ->add('date_paiement', DateType::class, array(
                'label' => 'Date de paiement ',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
                //'format' => 'dd/MM/yyyy',
                'attr' => array(
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => ' datePaiementFactureField'
                )
            ))
            ->add('mode_paiement', ChoiceType::class, array(
                'label' => 'Mode de paiement',
                'placeholder' => 'Selectionner',
                'choices'  => array(
                    'Chèque' => 'cheque',
                    'Virement' => 'virement',
                ),
                'required' => false,
                'attr' => array('class' => 'modePaiementFactureField'),
            ))
            ->add('banque', TextType::class, array(
                'label' => 'Banque *',
                'required' => false,
                'attr' => array('class' => 'banqueFactureField'),
            ))
            ->add('montant_payer', TextType::class, array(
                'label' => 'Montant payé',
                'required' => false,
                'attr' => array('class' => 'montantPayerFactureField'),
            ))
            ->add('commentaire', TextType::class, array(
                'label' => 'Commentaire',
                'required' => false,
                'attr' => array('class' => 'commentaireFactureField'),
            ))
            ->add('save', SubmitType::class, array(
                'label' => "Valider",
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
        return 'application_plateformebundle_facturePaiement';
    }


}
