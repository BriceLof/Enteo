<?php

namespace Application\UsersBundle\Entity;

use Application\PlateformeBundle\Model\FileUpload;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="document_user")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\DocumentRepository")
 */
class Document extends FileUpload
{
    /**
     * @ORM\ManyToOne(targetEntity="UserDocument", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $userDocument;

    public function getWebDir()
    {
        return 'uploads/'.$this->userDocument->getUser()->getId().'/'.$this->userDocument->getName();
    }

    /**
     * Set userDocument
     *
     * @param \Application\UsersBundle\Entity\UserDocument $userDocument
     *
     * @return Document
     */
    public function setUserDocument(\Application\UsersBundle\Entity\UserDocument $userDocument = null)
    {
        $this->userDocument = $userDocument;

        return $this;
    }

    /**
     * Get userDocument
     *
     * @return \Application\UsersBundle\Entity\UserDocument
     */
    public function getUserDocument()
    {
        return $this->userDocument;
    }
}
