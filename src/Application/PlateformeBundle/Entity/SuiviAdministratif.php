<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SuiviAdministratif
 *
 * @ORM\Table(name="suivi_administratif")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\SuiviAdministratifRepository")
 */
class SuiviAdministratif
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="suiviAdministratif", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

    /**
     * @var
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    
    
    /**
     * @ORM\ManyToOne(targetEntity="Statut")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="DetailStatut")
     * @ORM\JoinColumn(nullable=false)
     */
    private $detailStatut;
    
    /**
     * @var
     *
     * @ORM\Column(name="qui", type="string", length=255)
     */
    private $qui;
    
    /**
     * SuiviAdministratif constructor.
     * @param $date
     */
    public function __construct()
    {
        $this->date = new \DateTime('now');
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SuiviAdministratif
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set qui
     *
     * @param string $qui
     *
     * @return SuiviAdministratif
     */
    public function setQui($qui)
    {
        $this->qui = $qui;

        return $this;
    }

    /**
     * Get qui
     *
     * @return string
     */
    public function getQui()
    {
        return $this->qui;
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return SuiviAdministratif
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
     * @return SuiviAdministratif
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

    /**
     * Set detailStatut
     *
     * @param \Application\PlateformeBundle\Entity\DetailStatut $detailStatut
     *
     * @return SuiviAdministratif
     */
    public function setDetailStatut(\Application\PlateformeBundle\Entity\DetailStatut $detailStatut)
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
