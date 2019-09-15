<?php

namespace Application\UsersBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Beneficiaire", mappedBy="consultant", cascade={"persist"})
     */
    private $beneficiaire;

    /**
     * @ORM\ManyToMany(targetEntity="Application\UsersBundle\Entity\Users", cascade={"persist"})
     */
    private $consultants;

    /**
     * @ORM\ManyToMany(targetEntity="Application\PlateformeBundle\Entity\Bureau", inversedBy="consultants")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $bureaux;

    /**
     * @ORM\OneToMany(targetEntity="Application\StatsBundle\Entity\Semaine", mappedBy="commercial")
     */
    private $semaine;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Historique", mappedBy="user")
     */
    private $historique;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Disponibilites", mappedBy="consultant")
     */
    private $disponibilite;

    /**
     * @ORM\OneToMany(targetEntity="Application\UsersBundle\Entity\Mission", mappedBy="consultant", cascade={"persist","remove"})
     */
    protected $mission;

    /**
     * @ORM\OneToMany(targetEntity="Application\UsersBundle\Entity\Contrat", mappedBy="consultant" )
     */
    protected $contrats;

    /**
     * @ORM\OneToOne(targetEntity="StatutConsultant", cascade={"persist"})
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity="Facturation", cascade={"persist", "remove"})
     */
    private $facturation;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $image;
    
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
    * @ORM\Column(name="distanciel", type="array", nullable=true)
    */
    private $distanciel;
    
    /**
    * @ORM\Column(name="calendarid", type="string", length=500, nullable=true)
    */
    private $calendrierid;
    
    /**
    * @ORM\Column(name="calendrieruri", type="text", nullable=true)
    */
    private $calendrieruri;
    
    /**
    * @ORM\Column(name="date_creation", type="date", nullable=false)
    */
    private $dateCreation;
    
    /**
    * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
    */
    private $adresse;

    /**
     * @ORM\Column(name="num_declaration_activite", type="string", length=255, nullable=true)
     */
    private $numDeclarationActivite;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;
	
    public function __construct()
    {
        parent::__construct();
        
        $this->dateCreation = new \DateTime();
        $this->missions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userDocuments = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Users
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

    public function __toString()
    {
        //return parent::__toString(); // TODO: Change the autogenerated stub
        return ucfirst(strtolower($this->nom)).' '.ucfirst(strtolower($this->prenom)).' ('.$this->ville->getCp().')';
    }

    /**
     * Set distanciel
     *
     * @param array $distanciel
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
     * @return array
     */
    public function getDistanciel()
    {
        return $this->distanciel;
    }

    /**
     * Add historique
     *
     * @param \Application\PlateformeBundle\Entity\Historique $historique
     *
     * @return Users
     */
    public function addHistorique(\Application\PlateformeBundle\Entity\Historique $historique)
    {
        $this->historique[] = $historique;

        return $this;
    }

    /**
     * Remove historique
     *
     * @param \Application\PlateformeBundle\Entity\Historique $historique
     */
    public function removeHistorique(\Application\PlateformeBundle\Entity\Historique $historique)
    {
        $this->historique->removeElement($historique);
    }

    /**
     * Get historique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistorique()
    {
        return $this->historique;
    }

    /**
     * Add disponibilite
     *
     * @param \Application\PlateformeBundle\Entity\Disponibilites $disponibilite
     *
     * @return Users
     */
    public function addDisponibilite(\Application\PlateformeBundle\Entity\Disponibilites $disponibilite)
    {
        $this->disponibilite[] = $disponibilite;

        return $this;
    }

    /**
     * Remove disponibilite
     *
     * @param \Application\PlateformeBundle\Entity\Disponibilites $disponibilite
     */
    public function removeDisponibilite(\Application\PlateformeBundle\Entity\Disponibilites $disponibilite)
    {
        $this->disponibilite->removeElement($disponibilite);
    }

    /**
     * Get disponibilite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Users
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set statut
     *
     * @param \Application\UsersBundle\Entity\StatutConsultant $statut
     *
     * @return Users
     */
    public function setStatut(\Application\UsersBundle\Entity\StatutConsultant $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \Application\UsersBundle\Entity\StatutConsultant
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add mission
     *
     * @param \Application\UsersBundle\Entity\Mission $mission
     *
     * @return Users
     */
    public function addMission(\Application\UsersBundle\Entity\Mission $mission)
    {
        $this->mission[] = $mission;

        return $this;
    }

    /**
     * Remove mission
     *
     * @param \Application\UsersBundle\Entity\Mission $mission
     */
    public function removeMission(\Application\UsersBundle\Entity\Mission $mission)
    {
        $this->mission->removeElement($mission);
    }

    /**
     * Get mission
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set facturation
     *
     * @param \Application\UsersBundle\Entity\Facturation $facturation
     *
     * @return Users
     */
    public function setFacturation(\Application\UsersBundle\Entity\Facturation $facturation = null)
    {
        $this->facturation = $facturation;

        return $this;
    }

    /**
     * Get facturation
     *
     * @return \Application\UsersBundle\Entity\Facturation
     */
    public function getFacturation()
    {
        return $this->facturation;
    }

    /**
     * @return mixed
     */
    public function getSemaine()
    {
        return $this->semaine;
    }

    /**
     * @param mixed $semaine
     */
    public function setSemaine($semaine)
    {
        $this->semaine = $semaine;
    }



    /**
     * Add semaine
     *
     * @param \Application\StatsBundle\Entity\Semaine $semaine
     *
     * @return Users
     */
    public function addSemaine(\Application\StatsBundle\Entity\Semaine $semaine)
    {
        $this->semaine[] = $semaine;

        return $this;
    }

    /**
     * Remove semaine
     *
     * @param \Application\StatsBundle\Entity\Semaine $semaine
     */
    public function removeSemaine(\Application\StatsBundle\Entity\Semaine $semaine)
    {
        $this->semaine->removeElement($semaine);
    }

    /**
     * @return mixed
     */
    public function getConsultants()
    {
        return $this->consultants;
    }

    /**
     * Add consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return Users
     */
    public function addConsultant(\Application\UsersBundle\Entity\Users $consultant)
    {
        $this->consultants[] = $consultant;

        return $this;
    }

    /**
     * Remove consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     */
    public function removeConsultant(\Application\UsersBundle\Entity\Users $consultant)
    {
        $this->consultants->removeElement($consultant);
    }

    /**
     * Add contrat
     *
     * @param \Application\UsersBundle\Entity\Contrat $contrat
     *
     * @return Users
     */
    public function addContrat(\Application\UsersBundle\Entity\Contrat $contrat)
    {
        $this->contrats[] = $contrat;

        return $this;
    }

    /**
     * Remove contrat
     *
     * @param \Application\UsersBundle\Entity\Contrat $contrat
     */
    public function removeContrat(\Application\UsersBundle\Entity\Contrat $contrat)
    {
        $this->contrats->removeElement($contrat);
    }

    /**
     * Get contrats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrats()
    {
        return $this->contrats;
    }

    /**
     * Set numDeclarationActivite
     *
     * @param string $numDeclarationActivite
     *
     * @return Users
     */
    public function setNumDeclarationActivite($numDeclarationActivite)
    {
        $this->numDeclarationActivite = $numDeclarationActivite;

        return $this;
    }

    /**
     * Get numDeclarationActivite
     *
     * @return string
     */
    public function getNumDeclarationActivite()
    {
        return $this->numDeclarationActivite;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Users
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add bureaux
     *
     * @param \Application\PlateformeBundle\Entity\Bureau $bureaux
     *
     * @return Users
     */
    public function addBureaux(\Application\PlateformeBundle\Entity\Bureau $bureaux)
    {
        $this->bureaux[] = $bureaux;

        return $this;
    }

    /**
     * Remove bureaux
     *
     * @param \Application\PlateformeBundle\Entity\Bureau $bureaux
     */
    public function removeBureaux(\Application\PlateformeBundle\Entity\Bureau $bureaux)
    {
        $this->bureaux->removeElement($bureaux);
    }

    /**
     * Get bureaux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBureaux()
    {
        return $this->bureaux;
    }

    /**
     * @param mixed $bureaux
     */
    public function setBureaux($bureaux)
    {
        $this->bureaux = $bureaux;
    }
}
