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
use Application\PlateformeBundle\Entity\Accompagnement;

class UpdateListener extends \Twig_Extension
{
    private $mailer;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        $this->mailer = $this->container->get('application_plateforme.mail');
        $entity = $args->getObject();

        $subject = "Notification : mise à jour de votre bénéficiaire";
        // Si l'utilisateur courant n'est pas le consultant du beneficiaire, on envoi un mail de notification
        if($entity instanceof Beneficiaire  && ($currentUser != $entity->getConsultant()))
        {
            $consultant = $entity->getConsultant();
            $beneficiaire = $entity;
            $message = "
                Bonjour ".ucfirst($consultant->getCivilite())." ".ucfirst($consultant->getPrenom())." ".strtoupper($consultant->getNom()).",<br><br>
                
                La fiche de votre bénéficiaire <b></b><a href='//appli.entheor.com/web/beneficiaire/show/".$beneficiaire->getId()."'>".ucfirst($beneficiaire->getCiviliteConso())." ".ucfirst($beneficiaire->getPrenomConso())." ".strtoupper($beneficiaire->getNomConso())."</a></b> a été mis à jour.  
            ";
            $this->mailer->sendNewNotification($consultant->getEmail(), $subject, $message);
        }

        if($entity instanceof Accompagnement)
        {

        }

    }
}