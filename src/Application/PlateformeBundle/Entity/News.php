<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\NewsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class News
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
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="news", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

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
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var
     *
     * @ORM\Column(name="date_heure", type="datetime")
     */
    private $dateHeure;

    public function __construct()
    {
        $this->dateHeure = new \DateTime("Y-m-d");
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
     * Set statut
     *
     * @param string $statut
     *
     * @return News
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set detailStatut
     *
     * @param string $detailStatut
     *
     * @return News
     */
    public function setDetailStatut($detailStatut)
    {
        $this->detailStatut = $detailStatut;

        return $this;
    }

    /**
     * Get detailStatut
     *
     * @return string
     */
    public function getDetailStatut()
    {
        return $this->detailStatut;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return News
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }




    /**
     * Set dateHeure
     *
     * @param \DateTime $dateHeure
     *
     * @return News
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
     * @return News
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

    // rajoute un +1 au nombre appel si la news à un statut téléphone
    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        if($this->statut->getSlug() == "telephone")
            $this->getBeneficiaire()->increaseNbAppelTel();
    }
}
