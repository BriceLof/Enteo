<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Ville
 *
 * @ORM\Table(name="ville")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\VilleRepository")
 */
class Ville
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", mappedBy="ville", cascade={"persist"})
     */
    protected $beneficiaire;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Employeur", mappedBy="ville", cascade={"persist"})
     */
    protected $employeur;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Bureau", mappedBy="ville", cascade={"persist"})
     */
    protected $bureaux;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Disponibilites", mappedBy="ville", cascade={"persist"})
     */
    protected $disponibilites;

    /**
     * @var string
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(name="slug_ville", type="string", length=255, nullable=false)
     */
    private $slugVille;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=5, nullable=true)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="dpt", type="string", length=255, nullable=true)
     */
    private $dpt;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", nullable=true)
     */
    private $region;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="departement_id", type="integer", nullable=false)
     */
    private $departementId;

    /**
     * @var string
     *
     * @ORM\Column(name="pre_position", type="string", length=10, nullable=true)
     */
    private $prePosition;

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
     * Set slugVille
     *
     * @param string $slugVille
     *
     * @return Ville
     */
    public function setSlugVille($slugVille)
    {
        $this->slugVille = $slugVille;

        return $this;
    }

    /**
     * Get slugVille
     *
     * @return string
     */
    public function getSlugVille()
    {
        return $this->slugVille;
    }

    /**
     * Set cp
     *
     * @param string $cp
     *
     * @return Ville
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set dpt
     *
     * @param string $dpt
     *
     * @return Ville
     */
    public function setDpt($dpt)
    {
        $this->dpt = $dpt;

        return $this;
    }

    /**
     * Get dpt
     *
     * @return string
     */
    public function getDpt()
    {
        return $this->dpt;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Ville
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Ville
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set departementId
     *
     * @param integer $departementId
     *
     * @return Ville
     */
    public function setDepartementId($departementId)
    {
        $this->departementId = $departementId;

        return $this;
    }

    /**
     * Get departementId
     *
     * @return integer
     */
    public function getDepartementId()
    {
        return $this->departementId;
    }

    /**
     * Set prePosition
     *
     * @param string $prePosition
     *
     * @return Ville
     */
    public function setPrePosition($prePosition)
    {
        $this->prePosition = $prePosition;

        return $this;
    }

    /**
     * Get prePosition
     *
     * @return string
     */
    public function getPrePosition()
    {
        return $this->prePosition;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Ville
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
     * Constructor
     */
    public function __construct()
    {
        $this->beneficiaire = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Ville
     */
    public function addBeneficiaire(\Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire)
    {
        $this->beneficiaire[] = $beneficiaire;

        $beneficiaire->setVille($this);

        return $this;
    }

    /**
     * Remove beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     */
    public function removeBeneficiaire(\Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire)
    {
        $this->beneficiaire->removeElement($beneficiaire);
    }

    /**
     * Get beneficiaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBeneficiaire()
    {
        return $this->beneficiaire;
    }

    /**
     * Add bureaux
     *
     * @param \Application\PlateformeBundle\Entity\Bureau $bureaux
     *
     * @return Ville
     */
    public function addBureaux(\Application\PlateformeBundle\Entity\Bureau $bureaux)
    {
        $this->bureaux[] = $bureaux;

        return $this;
    }

    /**
     * Remove bureaux
     *
     * @param \Application\PlateformeBundle\Entity\Bureau $bureaux
     */
    public function removeBureaux(\Application\PlateformeBundle\Entity\Bureau $bureaux)
    {
        $this->bureaux->removeElement($bureaux);
    }

    /**
     * Get bureaux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBureaux()
    {
        return $this->bureaux;
    }

    /**
     * Add employeur
     *
     * @param \Application\PlateformeBundle\Entity\Employeur $employeur
     *
     * @return Ville
     */
    public function addEmployeur(\Application\PlateformeBundle\Entity\Employeur $employeur)
    {
        $this->employeur[] = $employeur;

        return $this;
    }

    /**
     * Remove employeur
     *
     * @param \Application\PlateformeBundle\Entity\Employeur $employeur
     */
    public function removeEmployeur(\Application\PlateformeBundle\Entity\Employeur $employeur)
    {
        $this->employeur->removeElement($employeur);
    }

    /**
     * Get employeur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }

    /**
     * Add disponibilite
     *
     * @param \Application\PlateformeBundle\Entity\Disponibilites $disponibilite
     *
     * @return Ville
     */
    public function addDisponibilite(\Application\PlateformeBundle\Entity\Disponibilites $disponibilite)
    {
        $this->disponibilites[] = $disponibilite;

        return $this;
    }

    /**
     * Remove disponibilite
     *
     * @param \Application\PlateformeBundle\Entity\Disponibilites $disponibilite
     */
    public function removeDisponibilite(\Application\PlateformeBundle\Entity\Disponibilites $disponibilite)
    {
        $this->disponibilites->removeElement($disponibilite);
    }

    /**
     * Get disponibilites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisponibilites()
    {
        return $this->disponibilites;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Ville
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }
}
