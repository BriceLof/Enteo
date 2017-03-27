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
    
    /**
    * @ORM\Column(name="calendarid", type="string", length=500, nullable=true)
    */
    private $calendrierid;
    
    /**
    * @ORM\Column(name="calendrieruri", type="text", nullable=true)
    */
    private $calendrieruri;
    
    /**
    * @ORM\Column(name="acces", type="text", nullable=true)
    */
    private $acces;
    
    /**
    * @ORM\Column(name="commentaire", type="text", nullable=true)
    */
    private $commentaire;
    
	/**
     * @var
     *
     * @ORM\Column(name="supprimer", type="boolean")
     */
    private $supprimer;
	
    public function __construct()
    {
        $this->actifInactif = true;
        $this->temporaire = false;
		$this->supprimer = false;
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

    /**
     * Set calendrierid
     *
     * @param string $calendrierid
     *
     * @return Bureau
     */
    public function setCalendrierid($calendrierid)
    {
        $this->calendrierid = $calendrierid;

        return $this;
    }

    /**
     * Get calendrierid
     *
     * @return string
     */
    public function getCalendrierid()
    {
        return $this->calendrierid;
    }

    /**
     * Set calendrieruri
     *
     * @param string $calendrieruri
     *
     * @return Bureau
     */
    public function setCalendrieruri($calendrieruri)
    {
        $this->calendrieruri = $calendrieruri;

        return $this;
    }

    /**
     * Get calendrieruri
     *
     * @return string
     */
    public function getCalendrieruri()
    {
        return $this->calendrieruri;
    }

    /**
     * Set supprimer
     *
     * @param boolean $supprimer
     *
     * @return Bureau
     */
    public function setSupprimer($supprimer)
    {
        $this->supprimer = $supprimer;

        return $this;
    }

    /**
     * Get supprimer
     *
     * @return boolean
     */
    public function getSupprimer()
    {
        return $this->supprimer;
    }

    /**
     * Set acces
     *
     * @param string $acces
     *
     * @return Bureau
     */
    public function setAcces($acces)
    {
        $this->acces = $acces;

        return $this;
    }

    /**
     * Get acces
     *
     * @return string
     */
    public function getAcces()
    {
        return $this->acces;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Bureau
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return (ucfirst($this->getNombureau()).', '.$this->getAdresse().' '.$this->getVille()->getCp().' ').' '.strtoupper($this->getVille()->getNom());
    }
}
