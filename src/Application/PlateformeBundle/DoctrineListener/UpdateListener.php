<?php
/**
 * Created by PhpStorm.
 * User: lofbri01
 * Date: 26/09/2017
 * Time: 15:46
 */

namespace Application\PlateformeBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Application\PlateformeBundle\Services\Mailer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Application\PlateformeBundle\Entity\Beneficiaire;

class UpdateListener
{
    private $mailer;
    private $user;

    public function __construct(Mailer $mailer, TokenStorageInterface $tokenStorage)
    {
        $this->mailer = $mailer;
        $this->user = $tokenStorage;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $currentUser = $this->user->getToken()->getUser();
        $entity = $args->getObject();
        // Si l'utilisateur courant n'est pas le consultant du beneficiaire, on envoi un mail de notification
        if($entity instanceof Beneficiaire && ($currentUser != $entity->getConsultant()))
        {
            $consultant = $entity->getConsultant();
            $subject = "Notification : Votre bénéficiaire a été mis à jour";
            $message = "Votre bénéficiaire a été mis à jour par X.";
            $this->mailer->sendNewNotification($consultant->getEmail(), $subject, $message);

        }

    }
}