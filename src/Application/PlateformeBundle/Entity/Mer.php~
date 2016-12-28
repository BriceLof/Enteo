<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mise_en_relation
 *
 * @ORM\Table(name="mise_en_relation")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\MerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Mer
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
     * @var int
     *
     * @ORM\Column(name="mer_id", type="integer", unique=true)
     */
    private $merId;

    /**
     * @var int
     *
     * @ORM\Column(name="client_id", type="integer")
     */
    private $clientId;

    /**
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_conso", type="string", length=255)
     */
    private $nomConso;

    /**
     * @var string
     *
     * @ORM\Column(name="civilite_conso", type="string", length=255)
     */
    private $civiliteConso;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_conso", type="string", length=255)
     */
    private $prenomConso;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_conso", type="string", length=255)
     */
    private $villeConso;

    /**
     * @ORM\Column(name="code_postal", type="string", length=5)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="heure_rappel", type="string", length=255)
     */
    private $heureRappel;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_heure_mer", type="datetime")
     */
    private $dateHeureMer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_conf_mer", type="datetime")
     */
    private $dateConfMer;

    /**
     * @ORM\Column(name="tel_conso", type="string")
     */
    private $telConso;

    /**
     * @var string
     *
     * @ORM\Column(name="email_conso", type="string", length=255)
     */
    private $emailConso;

    /**
     * @var string
     *
     * @ORM\Column(name="domaine_vae_conso", type="string", length=255)
     */
    private $domaineVaeConso;

    /**
     * @var string
     *
     * @ORM\Column(name="diplome_vise_conso", type="string", length=255)
     */
    private $diplomeViseConso;

    /**
     * @var string
     *
     * @ORM\Column(name="origine_mer", type="string", length=255)
     */
    private $origineMer;

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
     * Set merId
     *
     * @param integer $merId
     *
     * @return Mise_en_relation
     */
    public function setMerId($merId)
    {
        $this->merId = $merId;

        return $this;
    }

    /**
     * Get merId
     *
     * @return int
     */
    public function getMerId()
    {
        return $this->merId;
    }

    /**
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Mise_en_relation
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set nomConso
     *
     * @param string $nomConso
     *
     * @return Mise_en_relation
     */
    public function setNomConso($nomConso)
    {
        $this->nomConso = $nomConso;

        return $this;
    }

    /**
     * Get nomConso
     *
     * @return string
     */
    public function getNomConso()
    {
        return $this->nomConso;
    }

    /**
     * Set prenomConso
     *
     * @param string $prenomConso
     *
     * @return Mise_en_relation
     */
    public function setPrenomConso($prenomConso)
    {
        $this->prenomConso = $prenomConso;

        return $this;
    }

    /**
     * Get prenomConso
     *
     * @return string
     */
    public function getPrenomConso()
    {
        return $this->prenomConso;
    }


    /**
     * Set heureRappel
     *
     * @param string $heureRappel
     *
     * @return Mise_en_relation
     */
    public function setHeureRappel($heureRappel)
    {
        $this->heureRappel = $heureRappel;

        return $this;
    }

    /**
     * Get heureRappel
     *
     * @return string
     */
    public function getHeureRappel()
    {
        return $this->heureRappel;
    }

    /**
     * Set dateHeureMer
     *
     * @param \DateTime $dateHeureMer
     *
     * @return Mise_en_relation
     */
    public function setDateHeureMer($dateHeureMer)
    {
        $this->dateHeureMer = $dateHeureMer;

        return $this;
    }

    /**
     * Get dateHeureMer
     *
     * @return \DateTime
     */
    public function getDateHeureMer()
    {
        return $this->dateHeureMer;
    }

    /**
     * Set telConso
     *
     * @param integer $telConso
     *
     * @return Mise_en_relation
     */
    public function setTelConso($telConso)
    {
        $this->telConso = $telConso;

        return $this;
    }

    /**
     * Get telConso
     *
     * @return int
     */
    public function getTelConso()
    {
        return $this->telConso;
    }

    /**
     * Set emailConso
     *
     * @param string $emailConso
     *
     * @return Mise_en_relation
     */
    public function setEmailConso($emailConso)
    {
        $this->emailConso = $emailConso;

        return $this;
    }

    /**
     * Get emailConso
     *
     * @return string
     */
    public function getEmailConso()
    {
        return $this->emailConso;
    }

    /**
     * Set domaineVaeConso
     *
     * @param string $domaineVaeConso
     *
     * @return Mise_en_relation
     */
    public function setDomaineVaeConso($domaineVaeConso)
    {
        $this->domaineVaeConso = $domaineVaeConso;

        return $this;
    }

    /**
     * Get domaineVaeConso
     *
     * @return string
     */
    public function getDomaineVaeConso()
    {
        return $this->domaineVaeConso;
    }

    /**
     * Set diplomeViseConso
     *
     * @param string $diplomeViseConso
     *
     * @return Mise_en_relation
     */
    public function setDiplomeViseConso($diplomeViseConso)
    {
        $this->diplomeViseConso = $diplomeViseConso;

        return $this;
    }

    /**
     * Get diplomeViseConso
     *
     * @return string
     */
    public function getDiplomeViseConso()
    {
        return $this->diplomeViseConso;
    }

    /**
     * Set civiliteConso
     *
     * @param string $civiliteConso
     *
     * @return Mer
     */
    public function setCiviliteConso($civiliteConso)
    {
        $this->civiliteConso = $civiliteConso;

        return $this;
    }

    /**
     * Get civiliteConso
     *
     * @return string
     */
    public function getCiviliteConso()
    {
        return $this->civiliteConso;
    }

    /**
     * Set villeConso
     *
     * @param string $villeConso
     *
     * @return Mer
     */
    public function setVilleConso($villeConso)
    {
        $this->villeConso = $villeConso;

        return $this;
    }

    /**
     * Get villeConso
     *
     * @return string
     */
    public function getVilleConso()
    {
        return $this->villeConso;
    }

    /**
     * Set dateConfMer
     *
     * @param \DateTime $dateConfMer
     *
     * @return Mer
     */
    public function setDateConfMer($dateConfMer)
    {
        $this->dateConfMer = $dateConfMer;

        return $this;
    }

    /**
     * Get dateConfMer
     *
     * @return \DateTime
     */
    public function getDateConfMer()
    {
        return $this->dateConfMer;
    }

    /**
     * Set origineMer
     *
     * @param string $origineMer
     *
     * @return Mer
     */
    public function setOrigineMer($origineMer)
    {
        $this->origineMer = $origineMer;

        return $this;
    }

    /**
     * Get origineMer
     *
     * @return string
     */
    public function getOrigineMer()
    {
        return $this->origineMer;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Mer
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Mer
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

}
