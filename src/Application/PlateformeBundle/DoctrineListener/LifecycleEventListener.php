<?php
/**
 * Created by PhpStorm.
 * User: lofbri01
 * Date: 26/09/2017
 * Time: 15:46
 */

namespace Application\PlateformeBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\PlateformeBundle\Entity\Beneficiaire;

/*
 * Envoi un mail dès la creation/maj d'une entite lie au beneficiaire, si ce n'est pas le consultant qui s'en est charger.
 */
class LifecycleEventListener extends \Twig_Extension
{
    private $mailer;
    private $container;
    private $em;
    private $currentUser;
    private $entity;
    private $listEntity;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        // Liste des entités touchant la page bénéficiaire dont on veut avoir les notifications
        $this->listEntity = array(
            "Accompagnement"        => "Projet bénéficiaire",
            "Financeur"             => "Projet bénéficiaire",
            "Nouvelle"              => "News",
            "Document"              => "Espace Documentaire",
            "News"                  => "Statut bénéficiaire",
            "StatutRecevabilite"    => "Statut bénéficiaire",
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $this->mailer = $this->container->get('application_plateforme.mail');
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        // L'entité touché lors de l'update
        $this->entity = $args->getObject();
        // Obtenir le nom de l'entité utilisé (=> le nom de sa classe)
        $entityName = $this->getEntityName($this->entity);
        // Vérification, si l'entité touché lors de l'update fait partie de notre tableau d'entités
        if(array_key_exists($entityName, $this->listEntity) !== false){
            $section = $this->listEntity[$entityName];
            $this->notificationByEntity($this->entity, $entityName, $section);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $this->mailer = $this->container->get('application_plateforme.mail');
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        // L'entité touché lors de l'update
        $this->entity = $args->getObject();
        // Obtenir le nom de l'entité utilisé (=> le nom de sa classe)
        $entityName = $this->getEntityName($this->entity);
        // Vérification, si l'entité touché lors de l'update fait partie de notre tableau d'entités
        if(array_key_exists($entityName, $this->listEntity) !== false){
            $section = $this->listEntity[$entityName];
            $this->notificationByEntity($this->entity, $entityName, $section);
        }
    }

    public function notificationByEntity($entity, $theEntityName, $section)
    {
        $beneficiaireRepository = $this->em->getRepository(Beneficiaire::class);
        if($theEntityName == "Accompagnement") {
            $beneficiaire = $beneficiaireRepository->findOneByAccompagnement($entity);
        }
        elseif($theEntityName == "Financeur") {
            $beneficiaire = $beneficiaireRepository->findOneByAccompagnement($entity->getAccompagnement()->getId());
        }
        else{
            $beneficiaire = $entity->getBeneficiaire();
        }

        if(!is_null($beneficiaire) && !is_null($beneficiaire->getConsultant())) {
            $consultant = $beneficiaire->getConsultant();
            if ($this->currentUser != $consultant) {
                $subject = "Notification : mise à jour de votre bénéficiaire";
                $message = "
                Bonjour " . ucfirst($consultant->getCivilite()) . " " . ucfirst($consultant->getPrenom()) . " " . strtoupper($consultant->getNom()) . ",<br><br>                
                La fiche de votre bénéficiaire <b></b><a href='https://appli.entheor.com/web/beneficiaire/show/" . $beneficiaire->getId() . "'>" . ucfirst($beneficiaire->getCiviliteConso()) . " " . ucfirst($beneficiaire->getPrenomConso()) . " " . strtoupper($beneficiaire->getNomConso()) . "</a></b> a été mise à jour.<br><br>  
                De nouvelles informations sont affichées dans la partie '" . $section . "'.  
                ";

                // Desabonner christine des notifications
                if($consultant->getEmail() !='christine.clementmolier@entheor.com')
                    $this->mailer->sendNewNotification($consultant->getEmail(), $subject, $message);
            }
        }
    }

    public function getEntityName($entity)
    {
        // Retourne le nom de l'entité : exmple -> $entity = "Application\PlateformeBundle\Entity\Beneficiaire", la fonction renverra uniquement "Beneficiaire"
        $entityNameOriginal = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
        $entityNameExplode = explode('\\', $entityNameOriginal);
        return $entityName = end($entityNameExplode);
    }
}