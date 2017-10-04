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

class UpdateListener extends \Twig_Extension
{
    private $mailer;
    private $container;
    private $em;
    private $currentUser;
    private $entity;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /*
     * Envoi un mail dès la mise à jour d'une entité lié au bénéficiaire, si ce n'est pas le consultant qui s'en est charger.
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $this->mailer = $this->container->get('application_plateforme.mail');
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        // L'entité touché lors de l'update
        $this->entity = $args->getObject();

        // Obtenir le nom de l'entité utilisé (=> le nom de sa classe)
        $entityNameOriginal = $this->em->getMetadataFactory()->getMetadataFor(get_class($this->entity))->getName();
        $entityNameExplode = explode('\\', $entityNameOriginal);
        $entityName = end($entityNameExplode);
        // Liste des entités touchant la page bénéficiaire dont on veut avoir les notifications
        $listEntity = array(
            "Accompagnement"    => "Accompagnement",
            "Nouvelle"          => "News",
            "Document"          => "Espace Documentaire",
            "Historique"        => "Historique bénéficiaire",
            "News"              => "Statut bénéficiaire",
        );
        // Vérification, si l'entité touché lors de l'update fait partie de notre tableau d'entités
        if(array_key_exists($entityName, $listEntity) !== false){
            $section = $listEntity[$entityName];
            $this->notificationByEntity($this->entity, $entityName, $section);
        }
    }

    public function notificationByEntity($entity, $theEntityName, $section)
    {
        if($theEntityName == "Accompagnement") {
            $beneficiaireRepository = $this->em->getRepository(Beneficiaire::class);
            $beneficiaire = $beneficiaireRepository->findOneByAccompagnement($entity);
        }else{
            $beneficiaire = $entity->getBeneficiaire();
        }

        $consultant = $beneficiaire->getConsultant();
        if (!is_null($consultant) && ($this->currentUser != $consultant)) {
            $subject = "Notification : mise à jour de votre bénéficiaire";
            $message = "
                    Bonjour " . ucfirst($consultant->getCivilite()) . " " . ucfirst($consultant->getPrenom()) . " " . strtoupper($consultant->getNom()) . ",<br><br>                
                    La fiche de votre bénéficiaire <b></b><a href='//appli.entheor.com/web/beneficiaire/show/" . $beneficiaire->getId() . "'>" . ucfirst($beneficiaire->getCiviliteConso()) . " " . ucfirst($beneficiaire->getPrenomConso()) . " " . strtoupper($beneficiaire->getNomConso()) . "</a></b> a été mis à jour.<br>  
                    Voir dans la partie '".$section."'.  
                    ";
            $this->mailer->sendNewNotification($consultant->getEmail(), $subject, $message);
        }
    }
}