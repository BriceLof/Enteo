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
}
