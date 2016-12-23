<?php

namespace Application\UsersBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\UsersBundle\Repository\UsersRepository")
 * @ORM\Table(name="users")
 */
class Users extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", mappedBy="consultant")
     */
    private $beneficiaire;


    /**
    * @ORM\Column(name="nom", type="string", length=255, nullable=true)
    */
    private $nom;

    /**
    * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
    */
    private $prenom;

    /**
    * @ORM\Column(name="ville", type="string", length=255, nullable=true)
    */
    private $ville;

    /**
    * @ORM\Column(name="distanciel", type="boolean", nullable=true)
    */
    private $distanciel;
    
    /**
    * @ORM\Column(name="calendarid", type="string", length=500, nullable=true)
    */
    private $calendrierid;
    
    /**
    * @ORM\Column(name="calendrieruri", type="string", length=500, nullable=true)
    */
    private $calendrieruri;
    
    public function __construct()
    {
        parent::__construct();
        
    }
    

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Users
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

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Users
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Users
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set distanciel
     *
     * @param boolean $distanciel
     *
     * @return Users
     */
    public function setDistanciel($distanciel)
    {
        $this->distanciel = $distanciel;

        return $this;
    }

    /**
     * Get distanciel
     *
     * @return boolean
     */
    public function getDistanciel()
    {
        return $this->distanciel;
    }

    /**
     * Set calendrieruri
     *
     * @param string $calendrieruri
     *
     * @return Users
     */
    public function setCalendrieruri($calendrieruri)
    {
        $this->calendrieruri = $calendrieruri;

        return $this;
    }

    /**
     * Get calendrieruri
     *
     * @return string
     */
    public function getCalendrieruri()
    {
        return $this->calendrieruri;
    }

    /**
     * Set calendrierid
     *
     * @param string $calendrierid
     *
     * @return Users
     */
    public function setCalendrierid($calendrierid)
    {
        $this->calendrierid = $calendrierid;

        return $this;
    }

    /**
     * Get calendrierid
     *
     * @return string
     */
    public function getCalendrierid()
    {
        return $this->calendrierid;
    }
 

    /**
     * Add beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Users
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
