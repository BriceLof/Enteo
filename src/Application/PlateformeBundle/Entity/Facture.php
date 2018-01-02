<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\FactureRepository")
 */
class Facture
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
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $beneficiaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut_accompagnement", type="date")
     */
    private $dateDebutAccompagnement;

    /**
     * @var \DateTime
     * @Assert\Date()
     * @ORM\Column(name="date_fin_accompagnement", type="date")
     */
    private $dateFinAccompagnement;

    /**
     * @var string
     *
     * @ORM\Column(name="financeur", type="string", length=255)
     */
    private $financeur;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=255, unique=true)
     */
    private $numero;

    /**
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(name="date_paiement", type="date", nullable=true)
     */
    private $datePaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="mode_paiement", type="string", length=255, nullable=true)
     */
    private $modePaiement;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_payer", type="float", nullable=true)
     */
    private $montantPayer;

    /**
     * @var string
     *
     * @ORM\Column(name="info_paiement", type="string", length=255, nullable=true)
     */
    private $infoPaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_ref", type="string", length=255, nullable=true)
     */
    private $numeroRef;

    /**
     * @var string
     *
     * @ORM\Column(name="code_adherent", type="string", length=255, nullable=true)
     */
    private $codeAdherent;

    /**
     * @var string
     *
     * @ORM\Column(name="detail_accompagnement", type="string", length=255, nullable=true)
     */
    private $detailAccompagnement;

    /**
     * @var
     *
     * @ORM\Column(name="ouvert", type="boolean")
     */
    private $ouvert;

    /**
     * @var string
     *
     * @ORM\Column(name="type_paiement", type="string", length=255)
     */
    private $typePaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="banque", type="string", length=255, nullable=true)
     */
    private $banque;

    /**
     * @var string
     *
     * @ORM\Column(name="heure_accompagnement_facture", type="string", length=255, nullable=true)
     */
    private $heureAccompagnementFacture;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->ouvert = true;
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
     * Set dateDebutAccompagnement
     *
     * @param \DateTime $dateDebutAccompagnement
     *
     * @return Facture
     */
    public function setDateDebutAccompagnement($dateDebutAccompagnement)
    {
        $this->dateDebutAccompagnement = $dateDebutAccompagnement;

        return $this;
    }

    /**
     * Get dateDebutAccompagnement
     *
     * @return \DateTime
     */
    public function getDateDebutAccompagnement()
    {
        return $this->dateDebutAccompagnement;
    }

    /**
     * Set dateFinAccompagnement
     *
     * @param \DateTime $dateFinAccompagnement
     *
     * @return Facture
     */
    public function setDateFinAccompagnement($dateFinAccompagnement)
    {
        $this->dateFinAccompagnement = $dateFinAccompagnement;

        return $this;
    }

    /**
     * Get dateFinAccompagnement
     *
     * @return \DateTime
     */
    public function getDateFinAccompagnement()
    {
        return $this->dateFinAccompagnement;
    }



    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Facture
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Facture
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
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
     * Set montant
     *
     * @param float $montant
     *
     * @return Facture
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
     * Set statut
     *
     * @param string $statut
     *
     * @return Facture
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

    /**
     * Set numeroRef
     *
     * @param string $numeroRef
     *
     * @return Facture
     */
    public function setNumeroRef($numeroRef)
    {
        $this->numeroRef = $numeroRef;

        return $this;
    }

    /**
     * Get numeroRef
     *
     * @return string
     */
    public function getNumeroRef()
    {
        return $this->numeroRef;
    }

    /**
     * Set codeAdherent
     *
     * @param string $codeAdherent
     *
     * @return Facture
     */
    public function setCodeAdherent($codeAdherent)
    {
        $this->codeAdherent = $codeAdherent;

        return $this;
    }

    /**
     * Get codeAdherent
     *
     * @return string
     */
    public function getCodeAdherent()
    {
        return $this->codeAdherent;
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Facture
     */
    public function setBeneficiaire(\Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire = null)
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
     * Set financeur
     *
     * @param string $financeur
     *
     * @return Facture
     */
    public function setFinanceur($financeur)
    {
        $this->financeur = $financeur;

        return $this;
    }

    /**
     * Get financeur
     *
     * @return string
     */
    public function getFinanceur()
    {
        return $this->financeur;
    }



    /**
     * Set infoPaiement
     *
     * @param string $infoPaiement
     *
     * @return Facture
     */
    public function setInfoPaiement($infoPaiement)
    {
        $this->infoPaiement = $infoPaiement;

        return $this;
    }

    /**
     * Get infoPaiement
     *
     * @return string
     */
    public function getInfoPaiement()
    {
        return $this->infoPaiement;
    }

    /**
     * Set ouvert
     *
     * @param boolean $ouvert
     *
     * @return Facture
     */
    public function setOuvert($ouvert)
    {
        $this->ouvert = $ouvert;

        return $this;
    }

    /**
     * Get ouvert
     *
     * @return boolean
     */
    public function getOuvert()
    {
        return $this->ouvert;
    }

    /**
     * Set detailAccompagnement
     *
     * @param string $detailAccompagnement
     *
     * @return Facture
     */
    public function setDetailAccompagnement($detailAccompagnement)
    {
        $this->detailAccompagnement = $detailAccompagnement;

        return $this;
    }

    /**
     * Get detailAccompagnement
     *
     * @return string
     */
    public function getDetailAccompagnement()
    {
        return $this->detailAccompagnement;
    }

    /**
     * Set datePaiement
     *
     * @param \DateTime $datePaiement
     *
     * @return Facture
     */
    public function setDatePaiement($datePaiement)
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    /**
     * Get datePaiement
     *
     * @return \DateTime
     */
    public function getDatePaiement()
    {
        return $this->datePaiement;
    }

    /**
     * Set modePaiement
     *
     * @param string $modePaiement
     *
     * @return Facture
     */
    public function setModePaiement($modePaiement)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return string
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }

    /**
     * Set montantPayer
     *
     * @param float $montantPayer
     *
     * @return Facture
     */
    public function setMontantPayer($montantPayer)
    {
        $this->montantPayer = $montantPayer;

        return $this;
    }

    /**
     * Get montantPayer
     *
     * @return float
     */
    public function getMontantPayer()
    {
        return $this->montantPayer;
    }

    /**
     * Set typePaiement
     *
     * @param string $typePaiement
     *
     * @return Facture
     */
    public function setTypePaiement($typePaiement)
    {
        $this->typePaiement = $typePaiement;

        return $this;
    }

    /**
     * Get typePaiement
     *
     * @return string
     */
    public function getTypePaiement()
    {
        return $this->typePaiement;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Facture
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

    /**
     * Set banque
     *
     * @param string $banque
     *
     * @return Facture
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * Set heureAccompagnementFacture
     *
     * @param string $heureAccompagnementFacture
     *
     * @return Facture
     */
    public function setHeureAccompagnementFacture($heureAccompagnementFacture)
    {
        $this->heureAccompagnementFacture = $heureAccompagnementFacture;

        return $this;
    }

    /**
     * Get heureAccompagnementFacture
     *
     * @return string
     */
    public function getHeureAccompagnementFacture()
    {
        return $this->heureAccompagnementFacture;
    }
}
