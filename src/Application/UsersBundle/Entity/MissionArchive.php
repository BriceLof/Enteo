<?php

namespace Application\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mission
 *
 * @ORM\Table(name="mission_archive")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\MissionArchiveRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class MissionArchive
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
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="mission")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $consultant;

    /**
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", inversedBy="missionArchives", cascade={"persist"})
     */
    protected $beneficiaire;

    /**
     * @ORM\Column(name="state", length=255, nullable=false)
     */
    private $state;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $document;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $information;

    /**
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float",nullable=true)
     */
    private $tarif;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->document = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set state
     *
     * @param string $state
     *
     * @return MissionArchive
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set document
     *
     * @param string $document
     *
     * @return MissionArchive
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set information
     *
     * @param string $information
     *
     * @return MissionArchive
     */
    public function setInformation($information)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Get information
     *
     * @return string
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return MissionArchive
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
     * Set tarif
     *
     * @param float $tarif
     *
     * @return MissionArchive
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return float
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return MissionArchive
     */
    public function setConsultant(\Application\UsersBundle\Entity\Users $consultant = null)
    {
        $this->consultant = $consultant;

        return $this;
    }

    /**
     * Get consultant
     *
     * @return \Application\UsersBundle\Entity\Users
     */
    public function getConsultant()
    {
        return $this->consultant;
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return MissionArchive
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
