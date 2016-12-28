<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SuiviAdministratif
 *
 * @ORM\Table(name="suivi_administratif")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\SuiviAdministratifRepository")
 */
class SuiviAdministratif
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
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="suiviAdministratif", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

    /**
     * @var
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var
     *
     * @ORM\Column(name="qui", type="string", length=255)
     */
    private $qui;

    /**
     * @var
     *
     * @ORM\Column(name="quoi", type="string", length=255)
     */
    private $quoi;

    /**
     * SuiviAdministratif constructor.
     * @param $date
     */
    public function __construct()
    {
        $this->date = new \DateTime('now');
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SuiviAdministratif
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
     * Set qui
     *
     * @param string $qui
     *
     * @return SuiviAdministratif
     */
    public function setQui($qui)
    {
        $this->qui = $qui;

        return $this;
    }

    /**
     * Get qui
     *
     * @return string
     */
    public function getQui()
    {
        return $this->qui;
    }

    /**
     * Set quoi
     *
     * @param string $quoi
     *
     * @return SuiviAdministratif
     */
    public function setQuoi($quoi)
    {
        $this->quoi = $quoi;

        return $this;
    }

    /**
     * Get quoi
     *
     * @return string
     */
    public function getQuoi()
    {
        return $this->quoi;
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return SuiviAdministratif
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
}
