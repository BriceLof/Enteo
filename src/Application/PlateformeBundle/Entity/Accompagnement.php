<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Application\PlateformeBundle\Validator\Constraints as FormAssert;

/**
 * Acoompagnement
 *
 * @ORM\Table(name="accompagnement")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\AccompagnementRepository")
 * @FormAssert\DateRange()
 */
class Accompagnement
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
     * @var
     *
     * @ORM\Column(name="opca_opacif", type="string", length=255)
     */
    private $opcaOpacif;

    /**
     * @var
     *
     * @ORM\Column(name="heure", type="integer")
     * @Assert\Regex("#^[0-9]+$#",
     *     message = " veuillez rentrer le nombre d'heure "
     * )
     */
    private $heure;

    /**
     * @var
     *
     * @ORM\Column(name="tarif", type="float")
     */
    private $tarif;

    /**
     * @var
     *
     * @ORM\Column(name="date_debut", type="date")
     */
    private $dateDebut;

    /**
     * @var
     *
     * @ORM\Column(name="date_fin", type="date")
     */
    private $dateFin;



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
     * Set opcaOpacif
     *
     * @param string $opcaOpacif
     *
     * @return accompagnement
     */
    public function setOpcaOpacif($opcaOpacif)
    {
        $this->opcaOpacif = $opcaOpacif;

        return $this;
    }

    /**
     * Get opcaOpacif
     *
     * @return string
     */
    public function getOpcaOpacif()
    {
        return $this->opcaOpacif;
    }

    /**
     * Set heure
     *
     * @param integer $heure
     *
     * @return accompagnement
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;

        return $this;
    }

    /**
     * Get heure
     *
     * @return integer
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * Set tarif
     *
     * @param float $tarif
     *
     * @return accompagnement
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return float
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return accompagnement
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
     * @return accompagnement
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
}
