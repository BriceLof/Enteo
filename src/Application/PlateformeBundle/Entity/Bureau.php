<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Beneficiaire
 * @ORM\Table(name="bureau")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\BureauRepository")
 */
class Bureau
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
     * @ORM\ManyToOne(targetEntity="Ville", inversedBy="bureaux", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $ville;

    /**
     * @var
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nombureau;
    
    public function __construct()
    {
        //$this->historique = new ArrayCollection();
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Bureau
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set ville
     *
     * @param \Application\PlateformeBundle\Entity\Ville $ville
     *
     * @return Bureau
     */
    public function setVille(\Application\PlateformeBundle\Entity\Ville $ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return \Application\PlateformeBundle\Entity\Ville
     */
    public function getVille()
    {
        return $this->ville;
    }



     /**
     * Add historique
     *
     * @param \Application\PlateformeBundle\Entity\Historique $historique
     *
     * @return Beneficiaire
     */
   /* public function addHistorique(\Application\PlateformeBundle\Entity\Historique $historique)
    {
        $this->historique[] = $historique;

        $historique->setBureau($this);

        return $this;
    }*/

    /**
     * Remove historique
     *
     * @param \Application\PlateformeBundle\Entity\Historique $historique
     */
    /*public function removeHistorique(\Application\PlateformeBundle\Entity\Historique $historique)
    {
        $this->historique->removeElement($historique);
    }*/

    /**
     * Get historique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
   /* public function getHistorique()
    {
        return $this->historique;
    }*/


    /**
     * Set nombureau
     *
     * @param string $nombureau
     *
     * @return Bureau
     */
    public function setNombureau($nombureau)
    {
        $this->nombureau = $nombureau;

        return $this;
    }

    /**
     * Get nombureau
     *
     * @return string
     */
    public function getNombureau()
    {
        return $this->nombureau;
    }
}
