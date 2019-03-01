<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="avis")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\AvisRepository")
 */
class Avis
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="news", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;
    
    /**
     * @var int
     *
     * @ORM\Column(name="noteGlobale", type="integer")
     */
    private $noteGlobale;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaireGeneral", type="text")
     */
    private $commentaireGeneral;

    /**
     * @var bool
     *
     * @ORM\Column(name="recommendationAmi", type="boolean")
     */
    private $recommendationAmi;

    /**
     * @var bool
     *
     * @ORM\Column(name="autorisationPublication", type="boolean")
     */
    private $autorisationPublication;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAccordPublication", type="datetime", nullable=true)
     */
    private $dateAccordPublication;

    /**
     * @var bool
     *
     * @ORM\Column(name="autorisationPublicationEntheor", type="boolean")
     */
    private $autorisationPublicationEntheor;


    public function __construct()
    {
        $this->date = new \DateTime();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Avis
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
     * Set noteGlobale
     *
     * @param integer $noteGlobale
     *
     * @return Avis
     */
    public function setNoteGlobale($noteGlobale)
    {
        $this->noteGlobale = $noteGlobale;

        return $this;
    }

    /**
     * Get noteGlobale
     *
     * @return int
     */
    public function getNoteGlobale()
    {
        return $this->noteGlobale;
    }

    /**
     * Set commentaireGeneral
     *
     * @param string $commentaireGeneral
     *
     * @return Avis
     */
    public function setCommentaireGeneral($commentaireGeneral)
    {
        $this->commentaireGeneral = $commentaireGeneral;

        return $this;
    }

    /**
     * Get commentaireGeneral
     *
     * @return string
     */
    public function getCommentaireGeneral()
    {
        return $this->commentaireGeneral;
    }

    /**
     * Set recommendationAmi
     *
     * @param boolean $recommendationAmi
     *
     * @return Avis
     */
    public function setRecommendationAmi($recommendationAmi)
    {
        $this->recommendationAmi = $recommendationAmi;

        return $this;
    }

    /**
     * Get recommendationAmi
     *
     * @return bool
     */
    public function getRecommendationAmi()
    {
        return $this->recommendationAmi;
    }

    /**
     * Set autorisationPublication
     *
     * @param boolean $autorisationPublication
     *
     * @return Avis
     */
    public function setAutorisationPublication($autorisationPublication)
    {
        $this->autorisationPublication = $autorisationPublication;

        return $this;
    }

    /**
     * Get autorisationPublication
     *
     * @return bool
     */
    public function getAutorisationPublication()
    {
        return $this->autorisationPublication;
    }

    /**
     * Set dateAccordPublication
     *
     * @param \DateTime $dateAccordPublication
     *
     * @return Avis
     */
    public function setDateAccordPublication($dateAccordPublication)
    {
        if($this->getAutorisationPublication() == true)
            $this->dateAccordPublication = $dateAccordPublication;
        else
            $this->dateAccordPublication = null;

        return $this;
    }

    /**
     * Get dateAccordPublication
     *
     * @return \DateTime
     */
    public function getDateAccordPublication()
    {
        return $this->dateAccordPublication;
    }

    /**
     * Set autorisationPublicationEntheor
     *
     * @param boolean $autorisationPublicationEntheor
     *
     * @return Avis
     */
    public function setAutorisationPublicationEntheor($autorisationPublicationEntheor)
    {
        $this->autorisationPublicationEntheor = $autorisationPublicationEntheor;

        return $this;
    }

    /**
     * Get autorisationPublicationEntheor
     *
     * @return bool
     */
    public function getAutorisationPublicationEntheor()
    {
        return $this->autorisationPublicationEntheor;
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Avis
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
}
