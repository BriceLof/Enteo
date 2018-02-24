<?php

namespace Application\StatsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Appel
 *
 * @ORM\Table(name="appel")
 * @ORM\Entity(repositoryClass="Application\StatsBundle\Repository\AppelRepository")
 */
class Appel
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
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Application\StatsBundle\Entity\Semaine", inversedBy="appels")
     */
    protected $semaine;

    /**
     * @var
     *
     * @ORM\Column(name="jour", type="integer", nullable=true)
     */
    private $jour;
    
    /**
     * @var
     *
     * @ORM\Column(name="nb_rdv_am", type="integer", nullable=true)
     */
    private $nbRdvAm;

    /**
     * @var
     *
     * @ORM\Column(name="nb_reponse_am", type="integer", nullable=true)
     */
    private $nbReponseAm;

    /**
     * @var
     *
     * @ORM\Column(name="nb_contact_am", type="integer", nullable=true)
     */
    private $nbContactAm;

    /**
     * @var
     *
     * @ORM\Column(name="nb_rdv_pm", type="integer", nullable=true)
     */
    private $nbRdvPm;

    /**
     * @var
     *
     * @ORM\Column(name="nb_reponse_pm", type="integer", nullable=true)
     */
    private $nbReponsePm;

    /**
     * @var
     *
     * @ORM\Column(name="nb_contact_apres_midi", type="integer", nullable=true)
     */
    private $nbContactPm;

    /**
     * @var
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @var
     *
     * @ORM\Column(name="horaire_am", type="text", nullable=true)
     */
    private $horaireAm;

    /**
     * @var
     *
     * @ORM\Column(name="horaire_pm", type="text", nullable=true)
     */
    private $horairePm;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Appel
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
     * Set nbRdvAm
     *
     * @param integer $nbRdvAm
     *
     * @return Appel
     */
    public function setNbRdvAm($nbRdvAm)
    {
        $this->nbRdvAm = $nbRdvAm;

        return $this;
    }

    /**
     * Get nbRdvAm
     *
     * @return integer
     */
    public function getNbRdvAm()
    {
        return $this->nbRdvAm;
    }

    /**
     * Set nbReponseAm
     *
     * @param integer $nbReponseAm
     *
     * @return Appel
     */
    public function setNbReponseAm($nbReponseAm)
    {
        $this->nbReponseAm = $nbReponseAm;

        return $this;
    }

    /**
     * Get nbReponseAm
     *
     * @return integer
     */
    public function getNbReponseAm()
    {
        return $this->nbReponseAm;
    }

    /**
     * Set nbContactAm
     *
     * @param integer $nbContactAm
     *
     * @return Appel
     */
    public function setNbContactAm($nbContactAm)
    {
        $this->nbContactAm = $nbContactAm;

        return $this;
    }

    /**
     * Get nbContactAm
     *
     * @return integer
     */
    public function getNbContactAm()
    {
        return $this->nbContactAm;
    }

    /**
     * Set nbRdvPm
     *
     * @param integer $nbRdvPm
     *
     * @return Appel
     */
    public function setNbRdvPm($nbRdvPm)
    {
        $this->nbRdvPm = $nbRdvPm;

        return $this;
    }

    /**
     * Get nbRdvPm
     *
     * @return integer
     */
    public function getNbRdvPm()
    {
        return $this->nbRdvPm;
    }

    /**
     * Set nbReponsePm
     *
     * @param integer $nbReponsePm
     *
     * @return Appel
     */
    public function setNbReponsePm($nbReponsePm)
    {
        $this->nbReponsePm = $nbReponsePm;

        return $this;
    }

    /**
     * Get nbReponsePm
     *
     * @return integer
     */
    public function getNbReponsePm()
    {
        return $this->nbReponsePm;
    }

    /**
     * Set nbContactPm
     *
     * @param integer $nbContactPm
     *
     * @return Appel
     */
    public function setNbContactPm($nbContactPm)
    {
        $this->nbContactPm = $nbContactPm;

        return $this;
    }

    /**
     * Get nbContactPm
     *
     * @return integer
     */
    public function getNbContactPm()
    {
        return $this->nbContactPm;
    }

    /**
     * Set semaine
     *
     * @param integer $semaine
     *
     * @return Appel
     */
    public function setSemaine($semaine)
    {
        $this->semaine = $semaine;

        return $this;
    }

    /**
     * Get semaine
     *
     * @return integer
     */
    public function getSemaine()
    {
        return $this->semaine;
    }

    /**
     * Set jour
     *
     * @param integer $jour
     *
     * @return Appel
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return integer
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Appel
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
     * Set horaireAm
     *
     * @param string $horaireAm
     *
     * @return Appel
     */
    public function setHoraireAm($horaireAm)
    {
        $this->horaireAm = $horaireAm;

        return $this;
    }

    /**
     * Get horaireAm
     *
     * @return string
     */
    public function getHoraireAm()
    {
        return $this->horaireAm;
    }

    /**
     * Set horairePm
     *
     * @param string $horairePm
     *
     * @return Appel
     */
    public function setHorairePm($horairePm)
    {
        $this->horairePm = $horairePm;

        return $this;
    }

    /**
     * Get horairePm
     *
     * @return string
     */
    public function getHorairePm()
    {
        return $this->horairePm;
    }
}
