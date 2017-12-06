<?php

namespace Application\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Facturation
 *
 * @ORM\Table(name="facturation")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\FacturationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Facturation
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
     * @ORM\OneToOne(targetEntity="Application\UsersBundle\Entity\Users")
     */
    protected $consultant;

    /**
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="raison_sociale", type="string", length=255, nullable=true)
     */
    private $raisonSociale;

    /**
     * @ORM\Column(name="forme_societe", type="string", length=255, nullable=true)
     */
    private $formeSociete;

    /**
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Ville")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $ville;

    /**
     * @ORM\Column(name="adresse_complement", type="string", length=255, nullable=true)
     */
    private $adresseComplement;

    /**
     * @ORM\Column(name="representant_legal_civilite", type="string", length=255, nullable=true)
     */
    private $representantLegalCivilite;

    /**
     * @ORM\Column(name="representant_legal_nom", type="string", length=255, nullable=true)
     */
    private $representantLegalNom;

    /**
     * @ORM\Column(name="representant_legal_prenom", type="string", length=255, nullable=true)
     */
    private $representantLegalPrenom;

    /**
     * @ORM\Column(name="representant_legal_fonction", type="string", length=255, nullable=true)
     */
    private $representantLegalFonction;

    /**
     * @ORM\Column(name="intitule", type="string", length=255, nullable=true)
     */
    private $intitule;

    /**
     * @ORM\Column(name="siret", type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(name="attestation_urssaf", type="string", length=255, nullable=true)
     */
    private $attestationUrssaf;

    /**
     * @ORM\Column(name="reference_portage", type="string", length=255, nullable=true)
     */
    private $referencePortage;

    /**
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set type
     *
     * @param string $type
     *
     * @return Facturation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set formeSociete
     *
     * @param string $formeSociete
     *
     * @return Facturation
     */
    public function setFormeSociete($formeSociete)
    {
        $this->formeSociete = $formeSociete;

        return $this;
    }

    /**
     * Get formeSociete
     *
     * @return string
     */
    public function getFormeSociete()
    {
        return $this->formeSociete;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Facturation
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
     * Set adresseComplement
     *
     * @param string $adresseComplement
     *
     * @return Facturation
     */
    public function setAdresseComplement($adresseComplement)
    {
        $this->adresseComplement = $adresseComplement;

        return $this;
    }

    /**
     * Get adresseComplement
     *
     * @return string
     */
    public function getAdresseComplement()
    {
        return $this->adresseComplement;
    }

    /**
     * Set representantLegalCivilite
     *
     * @param string $representantLegalCivilite
     *
     * @return Facturation
     */
    public function setRepresentantLegalCivilite($representantLegalCivilite)
    {
        $this->representantLegalCivilite = $representantLegalCivilite;

        return $this;
    }

    /**
     * Get representantLegalCivilite
     *
     * @return string
     */
    public function getRepresentantLegalCivilite()
    {
        return $this->representantLegalCivilite;
    }

    /**
     * Set representantLegalNom
     *
     * @param string $representantLegalNom
     *
     * @return Facturation
     */
    public function setRepresentantLegalNom($representantLegalNom)
    {
        $this->representantLegalNom = $representantLegalNom;

        return $this;
    }

    /**
     * Get representantLegalNom
     *
     * @return string
     */
    public function getRepresentantLegalNom()
    {
        return $this->representantLegalNom;
    }

    /**
     * Set representantLegalPrenom
     *
     * @param string $representantLegalPrenom
     *
     * @return Facturation
     */
    public function setRepresentantLegalPrenom($representantLegalPrenom)
    {
        $this->representantLegalPrenom = $representantLegalPrenom;

        return $this;
    }

    /**
     * Get representantLegalPrenom
     *
     * @return string
     */
    public function getRepresentantLegalPrenom()
    {
        return $this->representantLegalPrenom;
    }

    /**
     * Set representantLegalFonction
     *
     * @param string $representantLegalFonction
     *
     * @return Facturation
     */
    public function setRepresentantLegalFonction($representantLegalFonction)
    {
        $this->representantLegalFonction = $representantLegalFonction;

        return $this;
    }

    /**
     * Get representantLegalFonction
     *
     * @return string
     */
    public function getRepresentantLegalFonction()
    {
        return $this->representantLegalFonction;
    }

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Facturation
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set attestationUrssaf
     *
     * @param string $attestationUrssaf
     *
     * @return Facturation
     */
    public function setAttestationUrssaf($attestationUrssaf)
    {
        $this->attestationUrssaf = $attestationUrssaf;

        return $this;
    }

    /**
     * Get attestationUrssaf
     *
     * @return string
     */
    public function getAttestationUrssaf()
    {
        return $this->attestationUrssaf;
    }

    /**
     * Set referencePortage
     *
     * @param string $referencePortage
     *
     * @return Facturation
     */
    public function setReferencePortage($referencePortage)
    {
        $this->referencePortage = $referencePortage;

        return $this;
    }

    /**
     * Get referencePortage
     *
     * @return string
     */
    public function getReferencePortage()
    {
        return $this->referencePortage;
    }

    /**
     * Set ville
     *
     * @param \Application\PlateformeBundle\Entity\Ville $ville
     *
     * @return Facturation
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

    /**
     * Set consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return Facturation
     */
    public function setConsultant(\Application\UsersBundle\Entity\Users $consultant = null)
    {
        $this->consultant = $consultant;

        $consultant->setFacturation($this);

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
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Facturation
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Facturation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setDateUploadAttestationUrssaf()
    {
        if (!is_null($this->attestationUrssaf)){
            $this->setDate(new \DateTime('now'));
        }else{
            $this->setDate(null);
        }
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Facturation
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }
}
