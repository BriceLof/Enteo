<?php

namespace Application\PlateformeBundle\Entity;

use Application\PlateformeBundle\Model\FileUpload;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\DocumentRepository")
 *
 */
class Document extends FileUpload
{
    /**
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $beneficiaire;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    public function getWebDir()
    {
        return 'uploads/beneficiaire/documents/'.$this->beneficiaire->getId();
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Document
     */
    public function setBeneficiaire(\Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire = null)
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    /**
     * Get beneficiaire
     *
     * @return \Application\PlateformeBundle\Entity\Beneficiaire
     */
    public function getBeneficiaire()
    {
        return $this->beneficiaire;
    }



    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Document
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Document
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
