<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Statut
 *
 * @ORM\Table(name="statut")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\StatutRepository")
 *
 */
class Statut
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
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var string
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\DetailStatut", mappedBy="statut", cascade={"persist"})
     */
    protected $detailStatut;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="acces_consultant", type="boolean")
     */
    private $accesConsultant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->beneficiaire = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Statut
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Statut
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Add detailStatut
     *
     * @param \Application\PlateformeBundle\Entity\DetailStatut $detailStatut
     *
     * @return Statut
     */
    public function addDetailStatut(\Application\PlateformeBundle\Entity\DetailStatut $detailStatut)
    {
        $this->detailStatut[] = $detailStatut;

        return $this;
    }

    /**
     * Remove detailStatut
     *
     * @param \Application\PlateformeBundle\Entity\DetailStatut $detailStatut
     */
    public function removeDetailStatut(\Application\PlateformeBundle\Entity\DetailStatut $detailStatut)
    {
        $this->detailStatut->removeElement($detailStatut);
    }

    /**
     * Get detailStatut
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetailStatut()
    {
        return $this->detailStatut;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * @return bool
     */
    public function isAccesConsultant()
    {
        return $this->accesConsultant;
    }

    /**
     * @param bool $accesConsultant
     */
    public function setAccesConsultant($accesConsultant)
    {
        $this->accesConsultant = $accesConsultant;
    }
}
