<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Employeur
 *
 * @ORM\Table(name="employeur")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\EmployeurRepository")
 */
class Employeur
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
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", mappedBy="employeur", cascade={"persist"})
     */
    protected $beneficiaire;

    /**
     * @ORM\Column(name="raison_sociale", type="string", length=255, nullable=true)
     */
    private $raisonSociale;

    /**
     * @ORM\Column(name="siret", type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(name="convention_collective", type="string", length=255, nullable=true)
     */
    private $conventionCollective;

    /**
     * @ORM\Column(name="ape_nace", type="string", length=255, nullable=true)
     */
    private $apeNace;


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
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Employeur
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
     * Set siret
     *
     * @param string $siret
     *
     * @return Employeur
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
     * Set conventionCollective
     *
     * @param string $conventionCollective
     *
     * @return Employeur
     */
    public function setConventionCollective($conventionCollective)
    {
        $this->conventionCollective = $conventionCollective;

        return $this;
    }

    /**
     * Get conventionCollective
     *
     * @return string
     */
    public function getConventionCollective()
    {
        return $this->conventionCollective;
    }

    /**
     * Set apeNace
     *
     * @param string $apeNace
     *
     * @return Employeur
     */
    public function setApeNace($apeNace)
    {
        $this->apeNace = $apeNace;

        return $this;
    }

    /**
     * Get apeNace
     *
     * @return string
     */
    public function getApeNace()
    {
        return $this->apeNace;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Add beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Employeur
     */
    public function addBeneficiaire(\Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire)
    {
        $this->beneficiaire[] = $beneficiaire;

        return $this;
    }

    /**
     * Remove beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     */
    public function removeBeneficiaire(\Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire)
    {
        $this->beneficiaire->removeElement($beneficiaire);
    }

    /**
     * Get beneficiaire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBeneficiaire()
    {
        return $this->beneficiaire;
    }
}
