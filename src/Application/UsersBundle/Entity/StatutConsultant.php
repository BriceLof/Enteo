<?php

namespace Application\UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Statut
 *
 * @ORM\Table(name="statut_consultant")
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\StatutConsultantRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class StatutConsultant
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
     * @ORM\Column(name="statut", type="integer", length=2, nullable=false)
     */
    private $statut;

    /**
     * @ORM\Column(name="detail", type="array", nullable=false)
     */
    private $detail;

    /**
     * @ORM\Column(name="dates", type="array", nullable=true)
     */
    private $dates;


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
     * Set statut
     *
     * @param integer $statut
     *
     * @return StatutConsultant
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set detail
     *
     * @param array $detail
     *
     * @return StatutConsultant
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return array
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set dates
     *
     * @param array $dates
     *
     * @return StatutConsultant
     */
    public function setDates($dates)
    {
        $this->dates = $dates;

        return $this;
    }

    /**
     * Get dates
     *
     * @return array
     */
    public function getDates()
    {
        return $this->dates;
    }
}
