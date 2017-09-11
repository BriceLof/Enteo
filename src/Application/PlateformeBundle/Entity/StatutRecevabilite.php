<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatutRecevabilite
 *
 * @ORM\Table(name="statut_recevabilite")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\StatutRecevabiliteRepository")
 */
class StatutRecevabilite
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
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="statut_recevabilite", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

    /**
     * @ORM\ManyToOne(targetEntity="Statut")
     * @ORM\JoinColumn(nullable=true)
     */
    private $statut;
    
    /**
     * @ORM\ManyToOne(targetEntity="DetailStatut")
     * @ORM\JoinColumn(nullable=true)
     */
    private $detailStatut;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateHeure", type="datetime")
     */
    private $dateHeure;
    
    public function __construct() {
        $this->dateHeure = new \DateTime();
    }

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
     * Set dateHeure
     *
     * @param \DateTime $dateHeure
     *
     * @return StatutRecevabilite
     */
    public function setDateHeure($dateHeure)
    {
        $this->dateHeure = $dateHeure;

        return $this;
    }

    /**
     * Get dateHeure
     *
     * @return \DateTime
     */
    public function getDateHeure()
    {
        return $this->dateHeure;
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return StatutRecevabilite
     */
    public function setBeneficiaire(\Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire)
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
     * Set statut
     *
     * @param \Application\PlateformeBundle\Entity\Statut $statut
     *
     * @return StatutRecevabilite
     */
    public function setStatut(\Application\PlateformeBundle\Entity\Statut $statut = null)
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

    /**
     * Set detailStatut
     *
     * @param \Application\PlateformeBundle\Entity\DetailStatut $detailStatut
     *
     * @return StatutRecevabilite
     */
    public function setDetailStatut(\Application\PlateformeBundle\Entity\DetailStatut $detailStatut = null)
    {
        $this->detailStatut = $detailStatut;

        return $this;
    }

    /**
     * Get detailStatut
     *
     * @return \Application\PlateformeBundle\Entity\DetailStatut
     */
    public function getDetailStatut()
    {
        return $this->detailStatut;
    }
}
