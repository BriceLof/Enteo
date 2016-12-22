<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
* Beneficiaire
*
* @ORM\Table(name="beneficiaire")
* @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\BeneficiaireRepository")
*/
class Beneficiaire
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
     * @ORM\ManyToOne(targetEntity="Ville")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    protected $ville;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\News", mappedBy="beneficiaire")
     */
    private $news;

    /**
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="beneficiaire", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $consultant;

    /**
     * @ORM\OneToMany(targetEntity="Historique", mappedBy="beneficiaire")
     */
    protected $historique;

    /**
     * @ORM\OneToMany(targetEntity="SuiviAdministratif", mappedBy="beneficiaire")
     */
    protected $suiviAdministratif;

    /**
     * @ORM\OneToOne(targetEntity="Accompagnement", cascade={"persist"})
     */
    protected $accompagnement;

    /**
     * @ORM\Column(name="poste", type="string", length=255, nullable=true)
     */
    private $poste;

    /**
     * @ORM\Column(name="encadrement", type="string", length=255, nullable=true)
     */
    private $encadrement;

    /**
     * @ORM\Column(name="tel_2", type="string", nullable=true)
     * @Assert\Regex("#^0[1-68][0-9]{8}$#",
     *     message = "Ce numéro n'est pas valide"
     * )
     */
    private $tel2;

    /**
     * @ORM\Column(name="email_2", type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "L'e-mail '{{ value }}' n'est pas valide",
     *     checkMX = true
     *     )
     */
    private $email2;

    /**
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     * @Assert\Regex("#[0-9]+#",
     *     message = "adresse invalide"
     * )
     */
    private $adresse;

    /**
     * @ORM\Column(name="adresse_complement", type="string", length=255, nullable=true)
     */
    private $adresseComplement;

    /**
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     * @Assert\Regex("#^[a-zA-Z]+$#i",
     *     message = "Erreur sur le nom du Pays"
     * )
     */
    private $pays;

    /**
     * @ORM\Column(name="num_secu", type="string", length=255, nullable=true)
     * @Assert\Regex("#^[12][0-9]{14}$#",
     *     message = "le numéro de Sécurité sociale est invalide"
     * )
     */
    private $numSecu;

    /**
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     * @Assert\Date()
     */
    private $dateNaissance;

    /**
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(name="experience", type="string", nullable=true)
     * @Assert\Type("string")
     */
    private $experience;

    /**
     * @ORM\Column(name="heure_dif", type="integer", nullable=true)
     * @Assert\Type("integer")
     */
    private $heureDif;

    /**
     * @ORM\Column(name="heure_cpf", type="integer", nullable=true)
     * @Assert\Type("integer")
     */
    private $heureCpf;

    /**
     * @ORM\Column(name="heure_cpf_annee", type="integer", nullable=true)
     * @Assert\Type("integer")
     */
    private $heureCpfAnnee;

    /**
     * @ORM\Column(name="motivation", type="text", nullable=true)
     * @Assert\Type("text")
     */
    private $motivation;

    /**
     * @ORM\Column(name="client_id", type="integer")
     */
    private $clientId;

    /**
     * @ORM\Column(name="civilite_conso", type="string", length=255)
     */
    private $civiliteConso;

    /**
     * @ORM\Column(name="nom_conso", type="string", length=255)
     * @Assert\Type("string")
     */
    private $nomConso;

    /**
     * @ORM\Column(name="prenom_conso", type="string", length=255)
     * @Assert\Type("string")
     */
    private $prenomConso;

    /**
     * @ORM\Column(name="heure_rappel", type="string", length=255)
     */
    private $heureRappel;

    /**
     * @ORM\Column(name="date_heure_mer", type="datetime")
     */
    private $dateHeureMer;

    /**
     * @ORM\Column(name="date_conf_mer", type="datetime")
     */
    private $dateConfMer;

    /**
     * @ORM\Column(name="tel_conso", type="string", length=255)
     * @Assert\Regex("#^0[1-68][0-9]{8}$#",
     *     message = "Ce numéro n'est pas valide"
     * )
     */
    private $telConso;

    /**
     * @ORM\Column(name="email_conso", type="string", length=45)
     * @Assert\Email(
     *     message = "L'e-mail '{{ value }}' n'est pas valide",
     *     checkMX = true
     *     )
     */
    private $emailConso;

    /**
     * @ORM\Column(name="domaine_vae", type="string", length=255)
     *
     */
    private $domaineVae;

    /**
     * @ORM\Column(name="diplome_vise", type="string", length=255)
     */
    private $diplomeVise;

    /**
     * @ORM\Column(name="origine_mer", type="string", length=255)
     */
    private $origineMer;
    
    /**
     * @ORM\Column(name="nb_appel_tel", type="integer", nullable=true)
     * @Assert\Type(
     *     type = "integer",
     *     message = ""
     * )
     */
    private $nbAppelTel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->historique = new ArrayCollection();
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
     * Set poste
     *
     * @param string $poste
     *
     * @return Beneficiaire
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return string
     */
    public function getPoste()
    {
        return $this->poste;
    }


    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Beneficiaire
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
     * Set statut
     *
     * @param string $statut
     *
     * @return Beneficiaire
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
     * Set motivation
     *
     * @param string $motivation
     *
     * @return Beneficiaire
     */
    public function setMotivation($motivation)
    {
        $this->motivation = $motivation;

        return $this;
    }

    /**
     * Get motivation
     *
     * @return string
     */
    public function getMotivation()
    {
        return $this->motivation;
    }

    public function addNews(News $news)
    {
        $this->news[] = $news;
        $news->setBeneficiaire($this);
        
        return $this;
    }

    public function removeNews(News $news)
    {
        $this->news->removeElement($news);
    }

    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add historique
     *
     * @param \Application\PlateformeBundle\Entity\Historique $historique
     *
     * @return Beneficiaire
     */
    public function addHistorique(\Application\PlateformeBundle\Entity\Historique $historique)
    {
        $this->historique[] = $historique;

        $historique->setBeneficiaire($this);

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
     * Set ville
     *
     * @param \Application\PlateformeBundle\Entity\Ville $ville
     *
     * @return Beneficiaire
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
     * Set tel2
     *
     * @param integer $tel2
     *
     * @return Beneficiaire
     */
    public function setTel2($tel2)
    {
        $this->tel2 = $tel2;

        return $this;
    }

    /**
     * Get tel2
     *
     * @return integer
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * Set adresseComplement
     *
     * @param string $adresseComplement
     *
     * @return Beneficiaire
     */
    public function setAdresseComplement($adresseComplement)
    {
        $this->adresseComplement = $adresseComplement;

        return $this;
    }

    /**
     * Get adresseComplement
     *
     * @return string
     */
    public function getAdresseComplement()
    {
        return $this->adresseComplement;
    }

    /**
     * Set numSecu
     *
     * @param string $numSecu
     *
     * @return Beneficiaire
     */
    public function setNumSecu($numSecu)
    {
        $this->numSecu = $numSecu;

        return $this;
    }

    /**
     * Get numSecu
     *
     * @return string
     */
    public function getNumSecu()
    {
        return $this->numSecu;
    }

    /**
     * Set heureDif
     *
     * @param integer $heureDif
     *
     * @return Beneficiaire
     */
    public function setHeureDif($heureDif)
    {
        $this->heureDif = $heureDif;

        return $this;
    }

    /**
     * Get heureDif
     *
     * @return integer
     */
    public function getHeureDif()
    {
        return $this->heureDif;
    }

    /**
     * Set heureCpf
     *
     * @param integer $heureCpf
     *
     * @return Beneficiaire
     */
    public function setHeureCpf($heureCpf)
    {
        $this->heureCpf = $heureCpf;

        return $this;
    }

    /**
     * Get heureCpf
     *
     * @return integer
     */
    public function getHeureCpf()
    {
        return $this->heureCpf;
    }

    /**
     * Set heureCpfAnnee
     *
     * @param integer $heureCpfAnnee
     *
     * @return Beneficiaire
     */
    public function setHeureCpfAnnee($heureCpfAnnee)
    {
        $this->heureCpfAnnee = $heureCpfAnnee;

        return $this;
    }

    /**
     * Get heureCpfAnnee
     *
     * @return integer
     */
    public function getHeureCpfAnnee()
    {
        return $this->heureCpfAnnee;
    }

    /**

     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Beneficiaire
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set civiliteConso
     *
     * @param string $civiliteConso
     *
     * @return Beneficiaire
     */
    public function setCiviliteConso($civiliteConso)
    {
        $this->civiliteConso = $civiliteConso;

        return $this;
    }

    /**
     * Get civiliteConso
     *
     * @return string
     */
    public function getCiviliteConso()
    {
        return $this->civiliteConso;
    }

    /**
     * Set nomConso
     *
     * @param string $nomConso
     *
     * @return Beneficiaire
     */
    public function setNomConso($nomConso)
    {
        $this->nomConso = $nomConso;

        return $this;
    }

    /**
     * Get nomConso
     *
     * @return string
     */
    public function getNomConso()
    {
        return $this->nomConso;
    }

    /**
     * Set prenomConso
     *
     * @param string $prenomConso
     *
     * @return Beneficiaire
     */
    public function setPrenomConso($prenomConso)
    {
        $this->prenomConso = $prenomConso;

        return $this;
    }

    /**
     * Get prenomConso
     *
     * @return string
     */
    public function getPrenomConso()
    {
        return $this->prenomConso;
    }

    /**
     * Set heureRappel
     *
     * @param string $heureRappel
     *
     * @return Beneficiaire
     */
    public function setHeureRappel($heureRappel)
    {
        $this->heureRappel = $heureRappel;

        return $this;
    }

    /**
     * Get heureRappel
     *
     * @return string
     */
    public function getHeureRappel()
    {
        return $this->heureRappel;
    }

    /**
     * Set dateHeureMer
     *
     * @param \DateTime $dateHeureMer
     *
     * @return Beneficiaire
     */
    public function setDateHeureMer($dateHeureMer)
    {
        $this->dateHeureMer = $dateHeureMer;

        return $this;
    }

    /**
     * Get dateHeureMer
     *
     * @return \DateTime
     */
    public function getDateHeureMer()
    {
        return $this->dateHeureMer;
    }

    /**
     * Set dateConfMer
     *
     * @param \DateTime $dateConfMer
     *
     * @return Beneficiaire
     */
    public function setDateConfMer($dateConfMer)
    {
        $this->dateConfMer = $dateConfMer;

        return $this;
    }

    /**
     * Get dateConfMer
     *
     * @return \DateTime
     */
    public function getDateConfMer()
    {
        return $this->dateConfMer;
    }

    /**
     * Set emailConso
     *
     * @param string $emailConso
     *
     * @return Beneficiaire
     */
    public function setEmailConso($emailConso)
    {
        $this->emailConso = $emailConso;

        return $this;
    }

    /**
     * Get emailConso
     *
     * @return string
     */
    public function getEmailConso()
    {
        return $this->emailConso;
    }

    /**
     * Set domaineVae
     *
     * @param string $domaineVae
     *
     * @return Beneficiaire
     */
    public function setDomaineVae($domaineVae)
    {
        $this->domaineVae = $domaineVae;

        return $this;
    }

    /**
     * Get domaineVae
     *
     * @return string
     */
    public function getDomaineVae()
    {
        return $this->domaineVae;
    }

    /**
     * Set diplomeVise
     *
     * @param string $diplomeVise
     *
     * @return Beneficiaire
     */
    public function setDiplomeVise($diplomeVise)
    {
        $this->diplomeVise = $diplomeVise;

        return $this;
    }

    /**
     * Get diplomeVise
     *
     * @return string
     */
    public function getDiplomeVise()
    {
        return $this->diplomeVise;
    }



    /**
     * Set clientId
     *
     * @param integer $clientId
     *
     * @return Beneficiaire
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get clientId
     *
     * @return integer
     */
    public function getClientId()
    {
        return $this->clientId;
    }



    /**
     * Set telConso
     *
     * @param string $telConso
     *
     * @return Beneficiaire
     */
    public function setTelConso($telConso)
    {
        $this->telConso = $telConso;

        return $this;
    }

    /**
     * Get telConso
     *
     * @return string
     */
    public function getTelConso()
    {
        return $this->telConso;
    }

    /**

     * Set email2
     *
     * @param string $email2
     *
     * @return Beneficiaire
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
     * Set origineMer
     *
     * @param string $origineMer
     *
     * @return Beneficiaire
     */
    public function setOrigineMer($origineMer)
    {
        $this->origineMer = $origineMer;
        return $this;
    }

    /**

     * Get origineMer
     *
     * @return string
     */
    public function getOrigineMer()
    {
        return $this->origineMer;
    }

    /**
     * Set nbAppelTel
     *
     * @param integer $nbAppelTel
     *
     * @return Beneficiaire
     */
    public function setNbAppelTel($nbAppelTel)
    {
        $this->nbAppelTel = $nbAppelTel;

        return $this;
    }

    /**
     * Get nbAppelTel
     *
     * @return integer
     */
    public function getNbAppelTel()
    {
        return $this->nbAppelTel;

    }
    
    // appeler lors de l'ajout d'une news 
    public function increaseNbAppelTel()
    {
        $this->nbAppelTel++;
    }
    
    /**
     * Set encadrement
     *
     * @param string $encadrement
     *
     * @return Beneficiaire
     */
    public function setEncadrement($encadrement)
    {
        $this->encadrement = $encadrement;

        return $this;
    }

    /**
     * Get encadrement
     *
     * @return string
     */
    public function getEncadrement()
    {
        return $this->encadrement;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Beneficiaire
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Add suiviAdministratif
     *
     * @param \Application\PlateformeBundle\Entity\SuiviAdministratif $suiviAdministratif
     *
     * @return Beneficiaire
     */
    public function addSuiviAdministratif(\Application\PlateformeBundle\Entity\SuiviAdministratif $suiviAdministratif)
    {
        $this->suiviAdministratif[] = $suiviAdministratif;

        return $this;
    }

    /**
     * Remove suiviAdministratif
     *
     * @param \Application\PlateformeBundle\Entity\SuiviAdministratif $suiviAdministratif
     */
    public function removeSuiviAdministratif(\Application\PlateformeBundle\Entity\SuiviAdministratif $suiviAdministratif)
    {
        $this->suiviAdministratif->removeElement($suiviAdministratif);
    }

    /**
     * Get suiviAdministratif
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSuiviAdministratif()
    {
        return $this->suiviAdministratif;
    }



    /**
     * Set accompagnement
     *
     * @param \Application\PlateformeBundle\Entity\Accompagnement $accompagnement
     *
     * @return Beneficiaire
     */
    public function setAccompagnement(\Application\PlateformeBundle\Entity\Accompagnement $accompagnement = null)
    {
        $this->accompagnement = $accompagnement;

        return $this;
    }

    /**
     * Get accompagnement
     *
     * @return \Application\PlateformeBundle\Entity\Accompagnement
     */
    public function getAccompagnement()
    {
        return $this->accompagnement;
    }

    /**
     * Set consultant
     *
     * @param \Application\UsersBundle\Entity\Users $consultant
     *
     * @return Beneficiaire
     */
    public function setConsultant(\Application\UsersBundle\Entity\Users $consultant = null)
    {
        $this->consultant = $consultant;

        $consultant->addBeneficiaire($this);

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
     * Set experience
     *
     * @param integer $experience
     *
     * @return Beneficiaire
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return integer
     */
    public function getExperience()
    {
        return $this->experience;
    }
}
