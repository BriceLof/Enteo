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
    * @ORM\Column(name="civilite", type="string", length=255, nullable=true)
    */
    private $civilite;

    /**
    * @ORM\Column(name="nom", type="string", length=255, nullable=true)
    */
    private $nom;
    
    /**
    * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
    */
    private $prenom;
    
    /**
    * @ORM\Column(name="tel1", type="string", length=10, nullable=true)
    */
    private $tel1;
    
    /**
    * @ORM\Column(name="tel2", type="string", length=10, nullable=true)
    */
    private $tel2;
    
    /**
    * @ORM\Column(name="email2", type="string", length=255, nullable=true)
    */
    private $email2;

    /**
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Ville" )
     * @ORM\JoinColumn(nullable=true)
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
    
    
    /**
    * @ORM\Column(name="date_creation", type="date", nullable=false)
    */
    private $dateCreation;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->dateCreation = new \DateTime();
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

    /**
     * Set civilite
     *
     * @param string $civilite
     *
     * @return Users
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return string
     */
    public function getCivilite()
    {
        return $this->civilite;
    }



    /**
     * Set email2
     *
     * @param string $email2
     *
     * @return Users
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;

        return $this;
    }

    /**
     * Get email2
     *
     * @return string
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Set tel1
     *
     * @param string $tel1
     *
     * @return Users
     */
    public function setTel1($tel1)
    {
        $this->tel1 = $tel1;

        return $this;
    }

    /**
     * Get tel1
     *
     * @return string
     */
    public function getTel1()
    {
        return $this->tel1;
    }

    /**
     * Set tel2
     *
     * @param string $tel2
     *
     * @return Users
     */
    public function setTel2($tel2)
    {
        $this->tel2 = $tel2;

        return $this;
    }

    /**
     * Get tel2
     *
     * @return string
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * Set ville
     *
     * @param \Application\PlateformeBundle\Entity\Ville $ville
     *
     * @return Users
     */
    public function setVille(\Application\PlateformeBundle\Entity\Ville $ville = null)
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
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Users
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }
}
