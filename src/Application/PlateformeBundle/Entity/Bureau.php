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
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="bureaux", cascade={"persist"}, fetch="LAZY")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $ville;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", mappedBy="bureau", cascade={"persist"} )
     */
    private $beneficiaires;

    /**
     * @ORM\OneToMany(targetEntity="Application\UsersBundle\Entity\Users", mappedBy="bureau", cascade={"persist"} )
     */
    private $consultants;

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

    /**
     * @var
     *
     * @ORM\Column(name="meta_title", type="string", length=255, nullable=true)
     */
    private $metaTitle;

    /**
     * @var
     *
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @var
     *
     * @ORM\Column(name="introduction", type="text", nullable=true)
     */
    private $intro;

    /**
     * @var
     *
     * @ORM\Column(name="presentation", type="text", nullable=true)
     */
    private $content;

    /**
     * @var
     *
     * @ORM\Column(name="public", type="array", nullable=true)
     */
    private $public;

    /**
     * @var
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */
    private $price;

    /**
     * @var
     *
     * @ORM\Column(name="duree", type="string", nullable=true)
     */
    private $duree;


    /**
     * @var
     *
     * @ORM\Column(name="enabled_entheor", type="boolean")
     */
    private $enabledEntheor;
	
    public function __construct()
    {
        $this->actifInactif = true;
        $this->temporaire = false;
		$this->supprimer = false;
		$this->enabledEntheor = false;
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
        return (ucfirst($this->getNombureau()).', '.$this->getAdresse().' '.$this->getVille()->getCp().' ').' '.strtoupper($this->getVille()->getNom());
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Bureau
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Bureau
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set intro
     *
     * @param string $intro
     *
     * @return Bureau
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;

        return $this;
    }

    /**
     * Get intro
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Bureau
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set public
     *
     * @param array $public
     *
     * @return Bureau
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return array
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Bureau
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set enabledEntheor
     *
     * @param boolean $enabledEntheor
     *
     * @return Bureau
     */
    public function setEnabledEntheor($enabledEntheor)
    {
        $this->enabledEntheor = $enabledEntheor;

        return $this;
    }

    /**
     * Get enabledEntheor
     *
     * @return boolean
     */
    public function getEnabledEntheor()
    {
        return $this->enabledEntheor;
    }

    /**
     * Add beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\beneficiaire $beneficiaire
     *
     * @return Bureau
     */
    public function addBeneficiaire(\Application\PlateformeBundle\Entity\beneficiaire $beneficiaire)
    {
        $this->beneficiaires[] = $beneficiaire;

        return $this;
    }

    /**
     * Remove beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\beneficiaire $beneficiaire
     */
    public function removeBeneficiaire(\Application\PlateformeBundle\Entity\beneficiaire $beneficiaire)
    {
        $this->beneficiaires->removeElement($beneficiaire);
    }

    /**
     * Get beneficiaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBeneficiaires()
    {
        return $this->beneficiaires;
    }

    /**
     * Add consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return Bureau
     */
    public function addConsultant(\Application\UsersBundle\Entity\Users $consultant)
    {
        $this->consultants[] = $consultant;

        return $this;
    }

    /**
     * Remove consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     */
    public function removeConsultant(\Application\UsersBundle\Entity\Users $consultant)
    {
        $this->consultants->removeElement($consultant);
    }

    /**
     * Get consultants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConsultants()
    {
        return $this->consultants;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return Bureau
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }
}
