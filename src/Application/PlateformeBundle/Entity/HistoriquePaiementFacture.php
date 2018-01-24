<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoriquePaiementFacture
 *
 * @ORM\Table(name="historique_paiement_facture")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\HistoriquePaiementFactureRepository")
 */
class HistoriquePaiementFacture
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
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Facture", inversedBy="historiquesPaiement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $facture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_heure", type="datetime")
     */
    private $dateHeure;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", nullable=true )
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="mode_paiement", type="string", length=255, nullable=true)
     */
    private $modePaiement;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;


    public function __construct()
    {
        $this->dateHeure = new \DateTime();
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
     * Set dateHeure
     *
     * @param \DateTime $dateHeure
     *
     * @return HistoriquePaiementFacture
     */
    public function setDateHeure($dateHeure)
    {
        $this->dateHeure = $dateHeure;

        return $this;
    }

    /**
     * Get dateHeure
     *
     * @return \DateTime
     */
    public function getDateHeure()
    {
        return $this->dateHeure;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Historique_statut_paiement_facture
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
     * Set montant
     *
     * @param float $montant
     *
     * @return Historique_statut_paiement_facture
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
     * Set modePaiement
     *
     * @param string $modePaiement
     *
     * @return Historique_statut_paiement_facture
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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Historique_statut_paiement_facture
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
     * Set facture
     *
     * @param \Application\PlateformeBundle\Entity\Facture $facture
     *
     * @return HistoriquePaiementFacture
     */
    public function setFacture(\Application\PlateformeBundle\Entity\Facture $facture)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \Application\PlateformeBundle\Entity\Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }
}
