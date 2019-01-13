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
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Ville", inversedBy="beneficiaire")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $ville;

    /**
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Employeur", inversedBy="beneficiaire", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $employeur;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\ContactEmployeur", mappedBy="beneficiaire")
     */
    private $contactEmployeur;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Historique", mappedBy="beneficiaire")
     */
    private $historique;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\Facture", mappedBy="beneficiaire")
     */
    private $factures;

    /**
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\Ville", inversedBy="beneficiaire")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $villeMer;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\News", mappedBy="beneficiaire", cascade={"persist"} )
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity="Application\PlateformeBundle\Entity\StatutRecevabilite", mappedBy="beneficiaire")
     */
    private $statutRecevabilite;

    /**
     * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users", inversedBy="beneficiaire", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $consultant;

    /**
     * @ORM\OneToMany(targetEntity="SuiviAdministratif", mappedBy="beneficiaire", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $suiviAdministratif;

    /**
     * @ORM\OneToOne(targetEntity="Accompagnement", cascade={"persist"})
     */
    protected $accompagnement;

    /**
     * @ORM\ManyToOne(targetEntity="DetailStatut", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $lastDetailStatutConsultant;

    /**
     * @ORM\ManyToOne(targetEntity="DetailStatut", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $lastDetailStatut;

    /**
     * @ORM\ManyToOne(targetEntity="DetailStatut", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $lastDetailStatutAdmin;

    /**
     * @ORM\ManyToOne(targetEntity="DetailStatut", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $lastDetailStatutCommercial;

    /**
     * @ORM\OneToMany(targetEntity="Application\UsersBundle\Entity\MissionArchive", mappedBy="beneficiaire", cascade={"persist","remove"})
     */
    protected $missionArchives;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="beneficiaire", cascade={"persist","remove"})
     * @Assert\Valid
     */
    protected $documents;

    /**
     * @ORM\Column(name="poste", type="string", length=255, nullable=true)
     */
    private $poste;

    /**
     * @ORM\Column(name="csp", type="string", length=255, nullable=true)
     */
    private $csp;

    /**
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(name="tel_2", type="string", nullable=true)
     * @Assert\Regex("#^0[1-6789][0-9]{8}$#",
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
     * @var
     *
     * @ORM\Column(name="typeFinanceur", type="string", length=255,nullable=true)
     */
    private $typeFinanceur;

    /**
     * @var
     *
     * @ORM\Column(name="organisme", type="string", length=255, nullable=true)
     */
    private $organisme;

    /**
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     *
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
     * @Assert\Regex("#^[12][0-9]{12}$#",
     *     message = "le numéro de Sécurité sociale est invalide"
     * )
     */
    private $numSecu;

    /**
     * @ORM\Column(name="num_secu_cle", type="string", length=10, nullable=true)
     * @Assert\Regex("#^[0-9]{2}$#",
     *     message = "veuillez verifier la clé"
     * )
     */
    private $numSecuCle;

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
     */
    private $motivation;

    /**
     * @ORM\Column(name="client_id", type="integer", nullable=true)
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
     * @Assert\Regex("#^0[1-6789][0-9]{8}$#",
     *     message = "Ce numéro n'est pas valide"
     * )
     */
    private $telConso;

    /**
     * @ORM\Column(name="indicatif_tel", type="string", length=10)
     * @Assert\Type("string")
     */
    private $indicatifTel;

    /**
     * @ORM\Column(name="email_conso", type="string", length=255)
     *
     */
    private $emailConso;

    /**
     * @ORM\Column(name="domaine_vae", type="string", length=255)
     *
     */
    private $domaineVae;

    /**
     * @ORM\Column(name="diplome_vise", type="string", length=255, nullable=true)
     */
    private $diplomeVise;

    /**
     * @ORM\Column(name="formation_initiale", type="string", length=255, nullable=true)
     */
    private $formationInitiale;

    /**
     * @ORM\Column(name="origine_mer", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="region_travail", type="string", length=255, nullable=true)
     */
    private $regionTravail;

    /**
     * @ORM\Column(name="dpt_travail", type="string", length=255, nullable=true)
     */
    private $dptTravail;

    /**
     * @ORM\Column(name="diplome_obtenu", type="string", length=255, nullable=true)
     */
    private $diplomeObtenu;

    /**
     * @var
     *
     * @ORM\Column(name="ref_financeur", type="string", length=255, nullable=true)
     */
    private $refFinanceur;

    /**
     * @var
     *
     * @ORM\Column(name="ecole_universite", type="string", length=255, nullable=true)
     */
    private $ecoleUniversite;

    /**
     * @ORM\OneToMany(targetEntity="Nouvelle", mappedBy="beneficiaire", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $nouvelle;

    /**
     * @ORM\Column(name="date_livret_1", type="datetime", nullable=true)
     */
    private $dateLivret1;

    /**
     * @var
     *
     * @ORM\Column(name="statut_livret_1", type="string", length=255, nullable=true)
     */
    private $statutLivret1;

    /**
     * @ORM\Column(name="date_livret_2", type="datetime", nullable=true)
     */
    private $dateLivret2;

    /**
     * @var
     *
     * @ORM\Column(name="statut_livret_2", type="string", length=255, nullable=true)
     */
    private $statutLivret2;

    /**
     * @ORM\Column(name="date_jury", type="datetime", nullable=true)
     */
    private $dateJury;

    /**
     * @var
     *
     * @ORM\Column(name="statut_jury", type="string", length=255, nullable=true)
     */
    private $statutJury;

    /**
     * @var datetime $deletedAt
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->nouvelle = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->updatedAt = new \DateTime('now');
        $this->deleted = false;
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
        if (!is_null($consultant)){
            $consultant->addBeneficiaire($this);
        }

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



    /**
     * Add document
     *
     * @param \Application\PlateformeBundle\Entity\Document $document
     *
     * @return Beneficiaire
     */
    public function addDocument(\Application\PlateformeBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        $document->setBeneficiaire($this);

        return $this;
    }

    /**
     * Remove document
     *
     * @param \Application\PlateformeBundle\Entity\Document $document
     */
    public function removeDocument(\Application\PlateformeBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Set formationInitiale
     *
     * @param string $formationInitiale
     *
     * @return Beneficiaire
     */
    public function setFormationInitiale($formationInitiale)
    {
        $this->formationInitiale = $formationInitiale;

        return $this;
    }

    /**
     * Get formationInitiale
     *
     * @return string
     */
    public function getFormationInitiale()
    {
        return $this->formationInitiale;
    }

    /**
     * Set numSecuCle
     *
     * @param string $numSecuCle
     *
     * @return Beneficiaire
     */
    public function setNumSecuCle($numSecuCle)
    {
        $this->numSecuCle = $numSecuCle;
        return $this;
    }


    /**
     * Get numSecuCle
     *
     * @return string
     */
    public function getNumSecuCle()
    {
        return $this->numSecuCle;
    }

    /**
     * Set villeMer
     *
     * @param \Application\PlateformeBundle\Entity\Ville $villeMer
     *
     * @return Beneficiaire
     */
    public function setVilleMer(\Application\PlateformeBundle\Entity\Ville $villeMer = null)
    {
        $this->villeMer = $villeMer;

        return $this;
    }

    /**
     * Get villeMer
     *
     * @return \Application\PlateformeBundle\Entity\Ville
     */
    public function getVilleMer()
    {
        return $this->villeMer;
    }

    /**
     * Set csp
     *
     * @param string $csp
     *
     * @return Beneficiaire
     */
    public function setCsp($csp)
    {
        $this->csp = $csp;

        return $this;
    }

    /**
     * Get csp
     *
     * @return string
     */
    public function getCsp()
    {
        return $this->csp;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Beneficiaire
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set employeur
     *
     * @param \Application\PlateformeBundle\Entity\Employeur $employeur
     *
     * @return Beneficiaire
     */
    public function setEmployeur(\Application\PlateformeBundle\Entity\Employeur $employeur = null)
    {
        $this->employeur = $employeur;

        return $this;
    }

    /**
     * Get employeur
     *
     * @return \Application\PlateformeBundle\Entity\Employeur
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }


    /**
     * Add contactEmployeur
     *
     * @param \Application\PlateformeBundle\Entity\ContactEmployeur $contactEmployeur
     *
     * @return Beneficiaire
     */
    public function addContactEmployeur(\Application\PlateformeBundle\Entity\ContactEmployeur $contactEmployeur)
    {
        $this->contactEmployeur[] = $contactEmployeur;

        return $this;
    }

    /**
     * Remove contactEmployeur
     *
     * @param \Application\PlateformeBundle\Entity\ContactEmployeur $contactEmployeur
     */
    public function removeContactEmployeur(\Application\PlateformeBundle\Entity\ContactEmployeur $contactEmployeur)
    {
        $this->contactEmployeur->removeElement($contactEmployeur);
    }

    /**
     * Get contactEmployeur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactEmployeur()
    {
        return $this->contactEmployeur;
    }

    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Set typeFinanceur
     *
     * @param string $typeFinanceur
     *
     * @return Beneficiaire
     */
    public function setTypeFinanceur($typeFinanceur)
    {
        $this->typeFinanceur = $typeFinanceur;

        return $this;
    }

    /**
     * Get typeFinanceur
     *
     * @return string
     */
    public function getTypeFinanceur()
    {
        return $this->typeFinanceur;
    }

    /**
     * Set organisme
     *
     * @param string $organisme
     *
     * @return Beneficiaire
     */
    public function setOrganisme($organisme)
    {
        $this->organisme = $organisme;

        return $this;
    }

    /**
     * Get organisme
     *
     * @return string
     */
    public function getOrganisme()
    {
        return $this->organisme;
    }

    /**
     * Set regionTravail
     *
     * @param string $regionTravail
     *
     * @return Beneficiaire
     */
    public function setRegionTravail($regionTravail)
    {
        $this->regionTravail = $regionTravail;

        return $this;
    }

    /**
     * Get regionTravail
     *
     * @return string
     */
    public function getRegionTravail()
    {
        return $this->regionTravail;
    }

    /**
     * Set diplomeObtenu
     *
     * @param string $diplomeObtenu
     *
     * @return Beneficiaire
     */
    public function setDiplomeObtenu($diplomeObtenu)
    {
        $this->diplomeObtenu = $diplomeObtenu;

        return $this;
    }

    /**
     * Get diplomeObtenu
     *
     * @return string
     */
    public function getDiplomeObtenu()
    {
        return $this->diplomeObtenu;
    }

    /**
     * Set refFinanceur
     *
     * @param string $refFinanceur
     *
     * @return Beneficiaire
     */
    public function setRefFinanceur($refFinanceur)
    {
        $this->refFinanceur = $refFinanceur;

        return $this;
    }

    /**
     * Get refFinanceur
     *
     * @return string
     */
    public function getRefFinanceur()
    {
        return $this->refFinanceur;
    }

    /**
     * Add nouvelle
     *
     * @param \Application\PlateformeBundle\Entity\Nouvelle $nouvelle
     *
     * @return Beneficiaire
     */
    public function addNouvelle(\Application\PlateformeBundle\Entity\Nouvelle $nouvelle)
    {
        $this->nouvelle[] = $nouvelle;

        return $this;
    }

    /**
     * Remove nouvelle
     *
     * @param \Application\PlateformeBundle\Entity\Nouvelle $nouvelle
     */
    public function removeNouvelle(\Application\PlateformeBundle\Entity\Nouvelle $nouvelle)
    {
        $this->nouvelle->removeElement($nouvelle);
    }

    /**
     * Get nouvelle
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNouvelle()
    {
        return $this->nouvelle;
    }

    public function getLastNouvelle()
    {
        return $this->getNouvelle()[count($this->getNouvelle())-1];
    }


    /**
     * Set dptTravail
     *
     * @param string $dptTravail
     *
     * @return Beneficiaire
     */
    public function setDptTravail($dptTravail)
    {
        $this->dptTravail = $dptTravail;

        return $this;
    }

    /**
     * Get dptTravail
     *
     * @return string
     */
    public function getDptTravail()
    {
        return $this->dptTravail;
    }

    /**
     * Add statutRecevabilite
     *
     * @param \Application\PlateformeBundle\Entity\StatutRecevabilite $statutRecevabilite
     *
     * @return Beneficiaire
     */
    public function addStatutRecevabilite(\Application\PlateformeBundle\Entity\StatutRecevabilite $statutRecevabilite)
    {
        $this->statutRecevabilite[] = $statutRecevabilite;

        return $this;
    }

    /**
     * Remove statutRecevabilite
     *
     * @param \Application\PlateformeBundle\Entity\StatutRecevabilite $statutRecevabilite
     */
    public function removeStatutRecevabilite(\Application\PlateformeBundle\Entity\StatutRecevabilite $statutRecevabilite)
    {
        $this->statutRecevabilite->removeElement($statutRecevabilite);
    }

    /**
     * Get statutRecevabilite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatutRecevabilite()
    {
        return $this->statutRecevabilite;
    }

    /**
     * Add missionArchive
     *
     * @param \Application\UsersBundle\Entity\MissionArchive $missionArchive
     *
     * @return Beneficiaire
     */
    public function addMissionArchive(\Application\UsersBundle\Entity\MissionArchive $missionArchive)
    {
        $this->missionArchives[] = $missionArchive;

        return $this;
    }

    /**
     * Remove missionArchive
     *
     * @param \Application\UsersBundle\Entity\MissionArchive $missionArchive
     */
    public function removeMissionArchive(\Application\UsersBundle\Entity\MissionArchive $missionArchive)
    {
        $this->missionArchives->removeElement($missionArchive);
    }

    /**
     * Get missionArchives
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMissionArchives()
    {
        return $this->missionArchives;
    }

    /**
     * Add facture
     *
     * @param \Application\PlateformeBundle\Entity\Facture $facture
     *
     * @return Beneficiaire
     */
    public function addFacture(\Application\PlateformeBundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    /**
     * Remove facture
     *
     * @param \Application\PlateformeBundle\Entity\Facture $facture
     */
    public function removeFacture(\Application\PlateformeBundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactures()
    {
        return $this->factures;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return Beneficiaire
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @return mixed
     */
    public function getLastDetailStatutConsultant()
    {
        return $this->lastDetailStatutConsultant;
    }

    /**
     * @param mixed $lastDetailStatutConsultant
     */
    public function setLastDetailStatutConsultant($lastDetailStatutConsultant)
    {
        $this->lastDetailStatutConsultant = $lastDetailStatutConsultant;
    }

    /**
     * @return mixed
     */
    public function getLastDetailStatut()
    {
        return $this->lastDetailStatut;
    }

    /**
     * @param mixed $lastDetailStatut
     */
    public function setLastDetailStatut($lastDetailStatut)
    {
        $this->lastDetailStatut = $lastDetailStatut;
    }

    /**
     * @return mixed
     */
    public function getEcoleUniversite()
    {
        return $this->ecoleUniversite;
    }

    /**
     * @param mixed $ecoleUniversite
     */
    public function setEcoleUniversite($ecoleUniversite)
    {
        $this->ecoleUniversite = $ecoleUniversite;
    }

    /**
     * Set indicatifTel
     *
     * @param string $indicatifTel
     *
     * @return Beneficiaire
     */
    public function setIndicatifTel($indicatifTel)
    {
        $this->indicatifTel = $indicatifTel;

        return $this;
    }

    /**
     * Get indicatifTel
     *
     * @return string
     */
    public function getIndicatifTel()
    {
        return $this->indicatifTel;
    }

    /**
     * Set lastDetailStatutCommercial
     *
     * @param \Application\PlateformeBundle\Entity\DetailStatut $lastDetailStatutCommercial
     *
     * @return Beneficiaire
     */
    public function setLastDetailStatutCommercial(\Application\PlateformeBundle\Entity\DetailStatut $lastDetailStatutCommercial = null)
    {
        $this->lastDetailStatutCommercial = $lastDetailStatutCommercial;

        return $this;
    }

    /**
     * Get lastDetailStatutCommercial
     *
     * @return \Application\PlateformeBundle\Entity\DetailStatut
     */
    public function getLastDetailStatutCommercial()
    {
        return $this->lastDetailStatutCommercial;
    }

    /**
     * Set lastDetailStatutAdmin
     *
     * @param \Application\PlateformeBundle\Entity\DetailStatut $lastDetailStatutAdmin
     *
     * @return Beneficiaire
     */
    public function setLastDetailStatutAdmin(\Application\PlateformeBundle\Entity\DetailStatut $lastDetailStatutAdmin = null)
    {
        $this->lastDetailStatutAdmin = $lastDetailStatutAdmin;

        return $this;
    }

    /**
     * Get lastDetailStatutAdmin
     *
     * @return \Application\PlateformeBundle\Entity\DetailStatut
     */
    public function getLastDetailStatutAdmin()
    {
        return $this->lastDetailStatutAdmin;
    }

    /**
     * Set dateLivret1
     *
     * @param \DateTime $dateLivret1
     *
     * @return Beneficiaire
     */
    public function setDateLivret1($dateLivret1)
    {
        $this->dateLivret1 = $dateLivret1;

        return $this;
    }

    /**
     * Get dateLivret1
     *
     * @return \DateTime
     */
    public function getDateLivret1()
    {
        return $this->dateLivret1;
    }

    /**
     * Set dateLivret2
     *
     * @param \DateTime $dateLivret2
     *
     * @return Beneficiaire
     */
    public function setDateLivret2($dateLivret2)
    {
        $this->dateLivret2 = $dateLivret2;

        return $this;
    }

    /**
     * Get dateLivret2
     *
     * @return \DateTime
     */
    public function getDateLivret2()
    {
        return $this->dateLivret2;
    }

    /**
     * Set dateJury
     *
     * @param \DateTime $dateJury
     *
     * @return Beneficiaire
     */
    public function setDateJury($dateJury)
    {
        $this->dateJury = $dateJury;

        return $this;
    }

    /**
     * Get dateJury
     *
     * @return \DateTime
     */
    public function getDateJury()
    {
        return $this->dateJury;
    }

    /**
     * Set statutLivret1
     *
     * @param string $statutLivret1
     *
     * @return Beneficiaire
     */
    public function setStatutLivret1($statutLivret1)
    {
        $this->statutLivret1 = $statutLivret1;

        return $this;
    }

    /**
     * Get statutLivret1
     *
     * @return string
     */
    public function getStatutLivret1()
    {
        return $this->statutLivret1;
    }

    /**
     * Set statutLivret2
     *
     * @param string $statutLivret2
     *
     * @return Beneficiaire
     */
    public function setStatutLivret2($statutLivret2)
    {
        $this->statutLivret2 = $statutLivret2;

        return $this;
    }

    /**
     * Get statutLivret2
     *
     * @return string
     */
    public function getStatutLivret2()
    {
        return $this->statutLivret2;
    }

    /**
     * Set statutJury
     *
     * @param string $statutJury
     *
     * @return Beneficiaire
     */
    public function setStatutJury($statutJury)
    {
        $this->statutJury = $statutJury;

        return $this;
    }

    /**
     * Get statutJury
     *
     * @return string
     */
    public function getStatutJury()
    {
        return $this->statutJury;
    }
}
