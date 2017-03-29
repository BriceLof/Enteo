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
     * @ORM\Column(name="autre_summary", type="text", nullable=true)
     */
    private $autreSummary;

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
     * @var string
     *
     * @ORM\Column(name="evenetId", type="text")
     */
    private $eventId;

    /**
     * @var string
     *
     * @ORM\Column(name="eventArchive", type="text")
     */
    private $eventarchive;
    /**
     * @ORM\ManyToOne(targetEntity="Bureau")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $bureau;

    /**
     * @ORM\ManyToOne(targetEntity="Beneficiaire")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

    /**
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $consultant;

    /**
     * @var string
     *
     * @ORM\Column(name="evenetid_bureau", type="text", nullable=true)
     */
    private $eventIdBureau;

    /**
     * Historique constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->dateDebut = new \DateTime('now');
		$this->dateFin = new \DateTime('now');
        $this->eventarchive = 'off';
        $this->heuredebut = (new \DateTime('now'))->setTime((new \DateTime('now'))->format('H'), 0, 0);
        $this->heurefin = (new \DateTime('now'))->setTime((new \DateTime('now'))->format('H'), 0, 0)->modify('+1 hour');
        $this->typerdv = 'presentiel';
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
        $this->typerdv = $typeRdv;

        return $this;
    }

    /**
     * Get typeRdv
     *
     * @return string
     */
    public function getTypeRdv()
    {
        return $this->typerdv;
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
     * Set eventId
     *
     * @param string $eventId
     *
     * @return Historique
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Get eventId
     *
     * @return string
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set bureau
     *
     * @param \Application\PlateformeBundle\Entity\Bureau $bureau
     *
     * @return Historique
     */
    public function setBureau(\Application\PlateformeBundle\Entity\Bureau $bureau = null)
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
     * Set consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return Historique
     */
    public function setConsultant(\Application\UsersBundle\Entity\Users $consultant)
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
     * Set autreSummary
     *
     * @param string $autreSummary
     *
     * @return Historique
     */
    public function setAutreSummary($autreSummary)
    {
        $this->autreSummary = $autreSummary;

        return $this;
    }

    /**
     * Get autreSummary
     *
     * @return string
     */
    public function getAutreSummary()
    {
        return $this->autreSummary;
    }

    /**
     * Set eventarchive
     *
     * @param string $eventarchive
     *
     * @return Historique
     */
    public function setEventarchive($eventarchive)
    {
        $this->eventarchive = $eventarchive;

        return $this;
    }

    /**
     * Get eventarchive
     *
     * @return string
     */
    public function getEventarchive()
    {
        return $this->eventarchive;
    }


    /**
     * Set eventIdBureau
     *
     * @param string $eventIdBureau
     *
     * @return Historique
     */
    public function setEventIdBureau($eventIdBureau)
    {
        $this->eventIdBureau = $eventIdBureau;

        return $this;
    }

    /**
     * Get eventIdBureau
     *
     * @return string
     */
    public function getEventIdBureau()
    {
        return $this->eventIdBureau;
    }
}
