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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var
     *
     * @ORM\Column(name="date_debut", type="date")
     */
    private $dateAccord;





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
}
