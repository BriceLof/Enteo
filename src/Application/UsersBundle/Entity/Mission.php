<?php

namespace Application\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mission
 *
 * @ORM\Table(name="mission")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\MissionRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Mission
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
     * @ORM\OneToOne(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", inversedBy="mission")
     * @ORM\JoinColumn(name="beneficiaire_id", nullable=false)
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
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(name="date_accepter", type="datetime", nullable=true)
     */
    private $dateAcceptation;

    /**
     * @ORM\Column(name="date_confirmation", type="datetime", nullable=true)
     */
    private $dateConfirmation;

    /**
     * @ORM\Column(name="date_terminer", type="datetime", nullable=true)
     */
    private $dateTerminer;

    
    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", nullable=true)
     */
    private $tarif;

    /**
     * @var float
     *
     * @ORM\Column(name="duree", type="integer",nullable=true)
     */
    private $duree;



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
     * @return Mission
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
     * @return Mission
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Mission
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateAcceptation
     *
     * @param \DateTime $dateAcceptation
     *
     * @return Mission
     */
    public function setDateAcceptation($dateAcceptation)
    {
        $this->dateAcceptation = $dateAcceptation;

        return $this;
    }

    /**
     * Get dateAcceptation
     *
     * @return \DateTime
     */
    public function getDateAcceptation()
    {
        return $this->dateAcceptation;
    }

    /**
     * Set dateConfirmation
     *
     * @param \DateTime $dateConfirmation
     *
     * @return Mission
     */
    public function setDateConfirmation($dateConfirmation)
    {
        $this->dateConfirmation = $dateConfirmation;

        return $this;
    }

    /**
     * Get dateConfirmation
     *
     * @return \DateTime
     */
    public function getDateConfirmation()
    {
        return $this->dateConfirmation;
    }

    /**
     * Set dateTerminer
     *
     * @param \DateTime $dateTerminer
     *
     * @return Mission
     */
    public function setDateTerminer($dateTerminer)
    {
        $this->dateTerminer = $dateTerminer;

        return $this;
    }

    /**
     * Get dateTerminer
     *
     * @return \DateTime
     */
    public function getDateTerminer()
    {
        return $this->dateTerminer;
    }

    /**
     * Set tarif
     *
     * @param float $tarif
     *
     * @return Mission
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
     * @return Mission
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
     * @return Mission
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
     * Set duree
     *
     * @param integer $duree
     *
     * @return Mission
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer
     */
    public function getDuree()
    {
        return $this->duree;
    }
}
