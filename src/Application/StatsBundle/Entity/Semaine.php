<?php

namespace Application\StatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Semaine
 *
 * @ORM\Table(name="semaine")
 * @ORM\Entity(repositoryClass="Application\StatsBundle\Repository\SemaineRepository")
 */
class Semaine{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users" )
     * @ORM\JoinColumn(nullable=true)
     */
    private $commercial;

    /**
     * @var
     *
     * @ORM\Column(name="annee", type="integer", nullable=true)
     */
    private $annee;

    /**
     * @var
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity="Application\StatsBundle\Entity\Appel", mappedBy="semaine", cascade={"persist","remove"})
     */
    protected $appels;

    /**
     * Semaine constructor.
     */
    public function __construct()
    {
        $this->appels = new ArrayCollection();
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
     * @return Semaine
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
     * Add appel
     *
     * @param \Application\StatsBundle\Entity\Appel $appel
     *
     * @return Semaine
     */
    public function addAppel(\Application\StatsBundle\Entity\Appel $appel)
    {
        $this->appels[] = $appel;

        $appel->setSemaine($this);

        return $this;
    }

    /**
     * Remove appel
     *
     * @param \Application\StatsBundle\Entity\Appel $appel
     */
    public function removeAppel(\Application\StatsBundle\Entity\Appel $appel)
    {
        $this->appels->removeElement($appel);
    }

    /**
     * Get appels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAppels()
    {
        return $this->appels;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return Semaine
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Semaine
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return mixed
     */
    public function getCommercial()
    {
        return $this->commercial;
    }

    /**
     * @param mixed $commercial
     */
    public function setCommercial($commercial)
    {
        $this->commercial = $commercial;

        $commercial->addSemaine($this);
    }


}
