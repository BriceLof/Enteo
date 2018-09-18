<?php

namespace Application\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contrat
 *
 * @ORM\Table(name="contrat_cadre")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\FacturationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Contrat
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
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users")
     * @ORM\JoinColumn(name="consultant_id", nullable=false)
     */
    protected $consultant;

    /**
     * @ORM\Column(name="nom_fichier", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

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
     * Set file
     *
     * @param string $file
     *
     * @return Contrat
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Contrat
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
     * Set consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return Contrat
     */
    public function setConsultant(\Application\UsersBundle\Entity\Users $consultant)
    {
        $this->consultant = $consultant;

        $consultant->addContrat($this);

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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Contrat
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
