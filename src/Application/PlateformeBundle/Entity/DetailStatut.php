<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetailStatut
 *
 * @ORM\Table(name="detail_statut")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\DetailStatutRepository")
 */
class DetailStatut
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
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=255)
     */
    private $detail;
    
    /**
    * @ORM\ManyToOne(targetEntity="Statut")
    * @ORM\JoinColumn(nullable=false)
    */
    private $statut;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return DetailStatut
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set statut
     *
     * @param \Application\PlateformeBundle\Entity\Statut $statut
     *
     * @return DetailStatut
     */
    public function setStatut(\Application\PlateformeBundle\Entity\Statut $statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \Application\PlateformeBundle\Entity\Statut
     */
    public function getStatut()
    {
        return $this->statut;
    }
}
