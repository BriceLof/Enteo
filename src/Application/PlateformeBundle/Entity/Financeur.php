<?php
namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Financeur
 *
 * @ORM\Table(name="financeur")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\FinanceurRepository")
 */
class Financeur
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
     * @ORM\ManyToOne(targetEntity="Accompagnement", inversedBy="financeur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $accompagnement;

    /**
     * @var
     *
     * @ORM\Column(name="nom", type="string", length=255,nullable=true)
     */
    private $nom;

    /**
     * @var
     *
     * @ORM\Column(name="organisme", type="string", length=255, nullable=true)
     */
    private $organisme;

    /**
     * @var
     *
     * @ORM\Column(name="montant", type="float",nullable=true)
     */
    private $montant;

    /**
     * @var
     *
     * @ORM\Column(name="date_debut", type="date",nullable=true)
     */
    private $dateAccord;

    /**
     * @var
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var
     *
     * @ORM\Column(name="complement_adresse", type="string", length=255, nullable=true)
     */
    private $complementAdresse;

    /**
     * @var
     *
     * @ORM\Column(name="cp", type="string", length=255, nullable=true)
     */
    private $cp;

    /**
     * @var
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @var
     *
     * @ORM\Column(name="civilite_contact", type="string", length=255, nullable=true)
     */
    private $civiliteContact;

    /**
     * @var
     *
     * @ORM\Column(name="nom_contact", type="string", length=255, nullable=true)
     */
    private $nomContact;

    /**
     * @var
     *
     * @ORM\Column(name="prenom_contact", type="string", length=255, nullable=true)
     */
    private $prenomContact;

    /**
     * @var
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $telContact;

    /**
     * @var
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $emailContact;

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
     * Set montant
     *
     * @param float $montant
     *
     * @return Financeur
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set dateAccord
     *
     * @param \DateTime $dateAccord
     *
     * @return Financeur
     */
    public function setDateAccord($dateAccord)
    {
        $this->dateAccord = $dateAccord;

        return $this;
    }

    /**
     * Get dateAccord
     *
     * @return \DateTime
     */
    public function getDateAccord()
    {
        return $this->dateAccord;
    }

    /**
     * Set accompagnement
     *
     * @param \Application\PlateformeBundle\Entity\Accompagnement $accompagnement
     *
     * @return Financeur
     */
    public function setAccompagnement(\Application\PlateformeBundle\Entity\Accompagnement $accompagnement)
    {
        $this->accompagnement = $accompagnement;

        return $this;
    }

    /**
     * Get accompagnement
     *
     * @return \Application\PlateformeBundle\Entity\Accompagnement
     */
    public function getAccompagnement()
    {
        return $this->accompagnement;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Financeur
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
     * Set organisme
     *
     * @param string $organisme
     *
     * @return Financeur
     */
    public function setOrganisme($organisme)
    {
        $this->organisme = $organisme;

        return $this;
    }

    /**
     * Get organisme
     *
     * @return string
     */
    public function getOrganisme()
    {
        return $this->organisme;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Financeur
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
     * Set complementAdresse
     *
     * @param string $complementAdresse
     *
     * @return Financeur
     */
    public function setComplementAdresse($complementAdresse)
    {
        $this->complementAdresse = $complementAdresse;

        return $this;
    }

    /**
     * Get complementAdresse
     *
     * @return string
     */
    public function getComplementAdresse()
    {
        return $this->complementAdresse;
    }

    /**
     * Set cp
     *
     * @param string $cp
     *
     * @return Financeur
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
     * Set ville
     *
     * @param string $ville
     *
     * @return Financeur
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set civiliteContact
     *
     * @param string $civiliteContact
     *
     * @return Financeur
     */
    public function setCiviliteContact($civiliteContact)
    {
        $this->civiliteContact = $civiliteContact;

        return $this;
    }

    /**
     * Get civiliteContact
     *
     * @return string
     */
    public function getCiviliteContact()
    {
        return $this->civiliteContact;
    }

    /**
     * Set nomContact
     *
     * @param string $nomContact
     *
     * @return Financeur
     */
    public function setNomContact($nomContact)
    {
        $this->nomContact = $nomContact;

        return $this;
    }

    /**
     * Get nomContact
     *
     * @return string
     */
    public function getNomContact()
    {
        return $this->nomContact;
    }

    /**
     * Set prenomContact
     *
     * @param string $prenomContact
     *
     * @return Financeur
     */
    public function setPrenomContact($prenomContact)
    {
        $this->prenomContact = $prenomContact;

        return $this;
    }

    /**
     * Get prenomContact
     *
     * @return string
     */
    public function getPrenomContact()
    {
        return $this->prenomContact;
    }

    /**
     * Set telContact
     *
     * @param string $telContact
     *
     * @return Financeur
     */
    public function setTelContact($telContact)
    {
        $this->telContact = $telContact;

        return $this;
    }

    /**
     * Get telContact
     *
     * @return string
     */
    public function getTelContact()
    {
        return $this->telContact;
    }

    /**
     * Set emailContact
     *
     * @param string $emailContact
     *
     * @return Financeur
     */
    public function setEmailContact($emailContact)
    {
        $this->emailContact = $emailContact;

        return $this;
    }

    /**
     * Get emailContact
     *
     * @return string
     */
    public function getEmailContact()
    {
        return $this->emailContact;
    }
}
