<?php

namespace Application\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DocumentName
 *
 * @ORM\Table(name="document_name")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\UserDocumentRepository")
 *
 */
class UserDocument
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="UserDocuments")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="userDocument", cascade={"persist","remove"})
     * @Assert\Valid
     */
    protected $documents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserDocument
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set user
     *
     * @param \Application\UsersBundle\Entity\Users $user
     *
     * @return UserDocument
     */
    public function setUser(\Application\UsersBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\UsersBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Add document
     *
     * @param \Application\UsersBundle\Entity\Document $document
     *
     * @return UserDocument
     */
    public function addDocument(\Application\UsersBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \Application\UsersBundle\Entity\Document $document
     */
    public function removeDocument(\Application\UsersBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
