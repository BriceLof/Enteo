<?php
namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Disponibilites
 *
 * @ORM\Table(name="disponibilites")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\DisponibilitesRepository")
 */
class Disponibilites {
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="disponibilites", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $ville;
    
    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text")
     */
    private $summary;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debuts", type="datetime")
     */
    private $dateDebuts;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fins", type="datetime")
     */
    private $dateFins;
    
    /**
     * @var string
     * @ORM\Column(name="evenetid", type="text")
     */
    private $eventId;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="disponibilite")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $consultant;
    
    /**
     * @ORM\ManyToOne(targetEntity="Bureau")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $bureau;
    
    // Constructeur
    public function __construct(){
        $this->dateDebuts = (new \DateTime('now'))->setTime(7, 0, 0);
	    $this->dateFins = (new \DateTime('now'))->setTime(20, 0, 0);
        $this->summary = 'Disponibilites';
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
     * Set consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return Disponibilites
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
     * Set summary
     *
     * @param string $summary
     *
     * @return Disponibilites
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
     * Set eventId
     *
     * @param string $eventId
     *
     * @return Disponibilites
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
     * Set dateDebuts
     *
     * @param \DateTime $dateDebuts
     *
     * @return Disponibilites
     */
    public function setDateDebuts($dateDebuts)
    {
        $this->dateDebuts = $dateDebuts;

        return $this;
    }

    /**
     * Get dateDebuts
     *
     * @return \DateTime
     */
    public function getDateDebuts()
    {
        return $this->dateDebuts;
    }

    /**
     * Set dateFins
     *
     * @param \DateTime $dateFins
     *
     * @return Disponibilites
     */
    public function setDateFins($dateFins)
    {
        $this->dateFins = $dateFins;

        return $this;
    }

    /**
     * Get dateFins
     *
     * @return \DateTime
     */
    public function getDateFins()
    {
        return $this->dateFins;
    }

    /**
     * Set bureau
     *
     * @param \Application\PlateformeBundle\Entity\Bureau $bureau
     *
     * @return Disponibilites
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
     * Set ville
     *
     * @param \Application\PlateformeBundle\Entity\Ville $ville
     *
     * @return Disponibilites
     */
    public function setVille(\Application\PlateformeBundle\Entity\Ville $ville = null)
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
}
