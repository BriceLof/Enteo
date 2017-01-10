<?php
namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * News
 *
 * @ORM\Table(name="historique")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\HistoriqueRepository")
 */
class Historique
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
     * @var string
     *
     * @ORM\Column(name="summary", type="text")
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime")
     */
    private $dateFin;

    /**
     * @var \Time
     *
     * @ORM\Column(name="heure_debut", type="time")
     */
    private $heuredebut;

    /**
     * @var \Time
     *
     * @ORM\Column(name="heure_fin", type="time")
     */
    private $heurefin;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type_rdv", type="text")
     */
    private $typerdv;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Bureau", inversedBy="historique")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $bureau;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="historique")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

    /**
     * Historique constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->dateDebut = new \DateTime('now');
        $this->dateFin = new \DateTime('now');
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
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Historique
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
     * Set summary
     *
     * @param string $summary
     *
     * @return Historique
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Historique
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Historique
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Historique
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set heuredebut
     *
     * @param \Time $heuredebut
     *
     * @return Historique
     */
    public function setHeuredebut($heuredebut)
    {
        $this->heuredebut = $heuredebut;

        return $this;
    }

    /**
     * Get heuredebut
     *
     * @return \Time
     */
    public function getHeuredebut()
    {
        return $this->heuredebut;
    }

    /**
     * Set heurefin
     *
     * @param \Time $heurefin
     *
     * @return Historique
     */
    public function setHeurefin($heurefin)
    {
        $this->heurefin = $heurefin;

        return $this;
    }

    /**
     * Get heurefin
     *
     * @return \Time
     */
    public function getHeurefin()
    {
        return $this->heurefin;
    }

    /**
     * Set typeRdv
     *
     * @param string $typeRdv
     *
     * @return Historique
     */
    public function setTypeRdv($typeRdv)
    {
        $this->type_rdv = $typeRdv;

        return $this;
    }

    /**
     * Get typeRdv
     *
     * @return string
     */
    public function getTypeRdv()
    {
        return $this->type_rdv;
    }

    /**
     * Set bureau
     *
     * @param \Application\PlateformeBundle\Entity\Bureau $bureau
     *
     * @return Historique
     */
    public function setBureau(\Application\PlateformeBundle\Entity\Bureau $bureau)
    {
        $this->bureau = $bureau;

        return $this;
    }

    /**
     * Get bureau
     *
     * @return \Application\PlateformeBundle\Entity\Bureau
     */
    public function getBureau()
    {
        return $this->bureau;
    }
}
