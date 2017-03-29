<?php

namespace Application\PlateformeBundle\Form;

use Application\PlateformeBundle\Entity\ContactEmployeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class AddBeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Me sert juste à activer l'ajax pour la recherche de ville correspondant
            ->add('codePostal', TextType::class, array(
                'mapped' => false, 
                'label' => "Code postal *", 
                'required' => true, 
                'attr' => array("maxlength" => 5, "class" => "codePostalInputForAjaxBeneficiaire")
            ))
            ->add('codePostalHiddenBeneficiaire', HiddenType::class, array("mapped" => false))
            ->add('idVilleHiddenBeneficiaire', HiddenType::class, array("mapped" => false))   
            ->add('ville', EntityType::class, array(
                'class' => 'ApplicationPlateformeBundle:Ville',
                'label' => 'Ville *',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.nom', 'ASC')
                        ->setMaxResults( 1 );
                },
                'choice_label' => 'nom',
                'required' => true,
            ))
            ->add('statut', ChoiceType::class, array(
                'label' => 'Statut * ',
                'placeholder' => 'Choisissez',
                'choices' => array(
                    'Salarié(e) du privé' => 'Salarie(e) du prive',
                    'Salarié(e) du public' => 'Salarie(e) du public',
                    'Entreprise' => 'Entreprise',
                    'Autre' => 'Autre',
                ),
                'required' => true,
            ))            
            ->add('clientId', TextType::class, array(
                'label' => 'Client ID ',
                'required' => false,
            ))
            ->add('civiliteConso', ChoiceType::class, array(
                'choices' => array(
                    'M.'                =>  "M.",
                    'Mme'               =>  "Mme",
                    'Mlle'              =>  "Mlle",
                ),
                'label' => "Civilité *",
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'placeholder' => 'Choisissez',
                'required' => true,
                    
            ))
            ->add('nomConso', TextType::class, array(
                'label' => 'Nom *', 
                'required' => true,
            ))            
            ->add('prenomConso', TextType::class, array(
                'label' => 'Prénom *',
                'required' => true,
            ))
            ->add('heureRappel', ChoiceType::class, array(
                'label' => 'Plage horaire d\'appel * ',
                'placeholder' => 'Choisissez',
                'choices' => array(
                    'Avant 12 heures' => 'Avant 12 heures',
                    'Entre 12 et 14 heures' => 'Entre 12 et 14 heures',
                    'Entre 14 et 16 heures' => 'Entre 14 et 16 heures',
                    'Après 16 heures' => 'Après 16 heures',
                    'Indifférent' => 'Indifférent',
                ),
                'required' => true,
            ))            
            ->add('telConso', TextType::class, array(
                'label' => 'Téléphone *',
                'required' => true,
            )) 
            ->add('emailConso', EmailType::class, array(
                'label' => 'Email *',
                'required' => true,
            )) 
            ->add('domaineVae', ChoiceType::class, array(
                'label' => 'Domaine de la VAE ',
                'placeholder' => 'Choisissez',
                'required' => false,
                'choices' => array(
                    "Achat, Logistique et Transport" => "Achat, Logistique et Transport",
                    "Agriculture, Agroalimentaire, Soins Animaliers" => "Agriculture, Agroalimentaire, Soins Animaliers",
                    "Art, Culture, Mode et Audiovisuel" => "Art, Culture, Mode et Audiovisuel",
                    "Assistanat et Secrétariat" => "Assistanat et Secrétariat",
                    "Banque, Finance et Assurance" => "Banque, Finance et Assurance",
                    "Bureautique, Web et Multimédia" => "Bureautique, Web et Multimédia",
                    "Communication, Information et Sc. Humaines" => "Communication, Information et Sc. Humaines",
                    "Comptabilité et Audit" => "Comptabilité et Audit",
                    "Droit et Sciences Politiques" => "Droit et Sciences Politiques",
                    "Emploi et Création d'entreprise" => "Emploi et Création d'entreprise",
                    "Esthétique, Cosmétique et Coiffure" => "Esthétique, Cosmétique et Coiffure",
                    "Hôtellerie, Restauration et Tourisme" => "Hôtellerie, Restauration et Tourisme",
                    "Hygiène, Qualité, Sécurité, Environnement" => "Hygiène, Qualité, Sécurité, Environnement",
                    "Industrie, Sciences et Techniques" => "Industrie, Sciences et Techniques",
                    "Informatique et Télécommunication" => "Informatique et Télécommunication",
                    "Langues" => "Langues",
                    "Management" => "Management",
                    "Ressources Humaines et Formation" => "Ressources Humaines et Formation",
                    "Santé et Social" => "Santé et Social",
                    "Urbanisme, BTP et Immobilier" => "Urbanisme, BTP et Immobilier",
                    "Vente et Marketing" => "Vente et Marketing",
                    "Vie Privée, Sport et Loisirs" => "Vie Privée, Sport et Loisirs",
                ),
            ))
            ->add('diplomeVise', ChoiceType::class, array(
                'label' => 'Diplôme visé *',
                'placeholder' => 'Choisissez',
                'required' => true,
                'choices' => array(
                    'Jusqu`au Bac' => 'Jusqu`au Bac',
                    'Bac à Bac+2' => 'Bac à Bac+2',
                    'Bac+3 et plus' => 'Bac+3 et plus',
                ),
            ))
            ->add('origineMerQui', ChoiceType::class, array(
                'label' => 'Origine bénéficiaire ?',
                'placeholder' => 'Choisissez',
                'required' => true,
                'mapped' => false,
                'choices' => array(
                    'IF' => 'if',
                    'Entheor.com' => 'entheor_com',
                    'Entreprise' => 'entreprise',
                ),
            ))
            ->add('origineMerComment', ChoiceType::class, array(
                'label' => 'Comment ?',
                'placeholder' => 'Choisissez',
                'required' => true,
                'mapped' => false,
                'choices' => array(
                    'Naturel' => 'naturel',
                    'Payant' => 'payant',
                ),
            ))
            ->add('origineMerDetailComment', ChoiceType::class, array(
                'label' => 'Détail',
                'placeholder' => 'Choisissez',
                'required' => true,
                'mapped' => false,
                'choices' => array(
                    'Adwords' => 'adw',
                    'Bing' => 'msn',
                    'Partenaires' => 'partenaires',
                ),
            ))

            ->add('submit', SubmitType::class, array('label' => 'Enregistrer',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ));
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
        return 'application_plateformebundle_beneficiaireType';
    }
}
