<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Beneficiaire
 * @ORM\Table(name="bureau")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\BureauRepository")
 */
class Bureau
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
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="bureaux", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $ville;

    /**
     * @var
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nombureau;

    /**
     * @var
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $actifInactif;
    
    /**
     * @var
     *
     * @ORM\Column(name="temporaire", type="boolean")
     */
    private $temporaire;
    
    public function __construct()
    {
        $this->actifInactif = true;
        $this->temporaire = false;
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Bureau
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set ville
     *
     * @param \Application\PlateformeBundle\Entity\Ville $ville
     *
     * @return Bureau
     */
    public function setVille(\Application\PlateformeBundle\Entity\Ville $ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \Application\PlateformeBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set nombureau
     *
     * @param string $nombureau
     *
     * @return Bureau
     */
    public function setNombureau($nombureau)
    {
        $this->nombureau = $nombureau;

        return $this;
    }

    /**
     * Get nombureau
     *
     * @return string
     */
    public function getNombureau()
    {
        return $this->nombureau;
    }

    /**
     * Set actifInactif
     *
     * @param boolean $actifInactif
     *
     * @return Bureau
     */
    public function setActifInactif($actifInactif)
    {
        $this->actifInactif = $actifInactif;

        return $this;
    }

    /**
     * Get actifInactif
     *
     * @return boolean
     */
    public function getActifInactif()
    {
        return $this->actifInactif;
    }

    /**
     * Set temporaire
     *
     * @param boolean $temporaire
     *
     * @return Bureau
     */
    public function setTemporaire($temporaire)
    {
        $this->temporaire = $temporaire;

        return $this;
    }

    /**
     * Get temporaire
     *
     * @return boolean
     */
    public function getTemporaire()
    {
        return $this->temporaire;
    }
}
