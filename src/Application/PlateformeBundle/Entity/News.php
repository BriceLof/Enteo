<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\NewsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class News
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
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="news", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

    /**
     * @ORM\ManyToOne(targetEntity="Statut")
     * @ORM\JoinColumn(nullable=true)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity="DetailStatut")
     * @ORM\JoinColumn(nullable=true)
     */
    private $detailStatut;


    /**
     * @var
     *
     * @ORM\Column(name="date_heure", type="datetime")
     */
    private $dateHeure;

    /**
     * @ORM\Column(name="consultant_id", type="integer", nullable=true)
     */
    protected $consultant;

    public function __construct()
    {
        $this->dateHeure = new \DateTime();
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
     * Set statut
     *
     * @param string $statut
     *
     * @return News
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
     * Set detailStatut
     *
     * @param string $detailStatut
     *
     * @return News
     */
    public function setDetailStatut($detailStatut)
    {
        $this->detailStatut = $detailStatut;

        return $this;
    }

    /**
     * Get detailStatut
     *
     * @return string
     */
    public function getDetailStatut()
    {
        return $this->detailStatut;
    }






    /**
     * Set dateHeure
     *
     * @param \DateTime $dateHeure
     *
     * @return News
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
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return News
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

    // rajoute un +1 au nombre appel si la news à un statut téléphone
    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        if($this->statut->getSlug() == "telephone" OR $this->statut->getSlug() == "rv1-a-faire" OR $this->statut->getSlug() == "rv2-a-faire" OR $this->statut->getSlug() == "abandon"
                OR $this->statut->getSlug() == "reporte" OR $this->statut->getSlug() == "termine")
            $this->getBeneficiaire()->increaseNbAppelTel();


        /*if (!in_array($this->detailStatut->getId(), array(7,14))){
            $suivis = $this->getBeneficiaire()->getSuiviAdministratif();
            foreach ($suivis as $suivi){
                $this->getBeneficiaire()->removeSuiviAdministratif($suivi);
            }
        }*/


        if (in_array($this->statut->getId(), array(11,12,13))){
            $this->getBeneficiaire()->setDeleted(true);
            $this->getBeneficiaire()->setLastDetailStatut(null);
        }else{
            $this->getBeneficiaire()->setDeleted(false);
        }
        if (!is_null($this->statut) && $this->statut->isAccesConsultant() == true){
            $this->getBeneficiaire()->setLastDetailStatutConsultant($this->detailStatut);
        }
    }



    /**
     * Set consultant
     *
     * @param integer $consultant
     *
     * @return News
     */
    public function setConsultant($consultant)
    {
        $this->consultant = $consultant;

        return $this;
    }

    /**
     * Get consultant
     *
     * @return integer
     */
    public function getConsultant()
    {
        return $this->consultant;
    }
}
