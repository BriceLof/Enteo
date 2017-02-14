<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 *
 * @ORM\Table(name="contact_employeur")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\ContactRepository")
 */
class ContactEmployeur
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
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="contactEmployeur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $beneficiaire;

    /**
     * @ORM\Column(name="nature", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     */
    private $nature;

    /**
     * @ORM\Column(name="civilite", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     */
    private $civilite;

    /**
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     */
    private $nom;

    /**
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     */
    private $prenom;

    /**
     * @ORM\Column(name="champs_libre", type="string", length=255, nullable=true)
     * @Assert\Type("string")
     */
    private $champsLibre;

    /**
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     * @Assert\Regex("#^0[1-678][0-9]{8}$#",
     *     message = "Ce numéro n'est pas valide"
     * )
     */
    private $tel;

    /**
     * @ORM\Column(name="tel2", type="string", length=255, nullable=true)
     * @Assert\Regex("#^0[1-678][0-9]{8}$#",
     *     message = "Ce numéro n'est pas valide"
     * )
     */
    private $tel2;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     *
     */
    private $email;

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
     * Set nature
     *
     * @param string $nature
     *
     * @return ContactEmployeur
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get nature
     *
     * @return string
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set civilite
     *
     * @param string $civilite
     *
     * @return ContactEmployeur
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
     * Set nom
     *
     * @param string $nom
     *
     * @return ContactEmployeur
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
     * @return ContactEmployeur
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
     * Set champsLibre
     *
     * @param string $champsLibre
     *
     * @return ContactEmployeur
     */
    public function setChampsLibre($champsLibre)
    {
        $this->champsLibre = $champsLibre;

        return $this;
    }

    /**
     * Get champsLibre
     *
     * @return string
     */
    public function getChampsLibre()
    {
        return $this->champsLibre;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return ContactEmployeur
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set tel2
     *
     * @param string $tel2
     *
     * @return ContactEmployeur
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
     * Set email
     *
     * @param string $email
     *
     * @return ContactEmployeur
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return ContactEmployeur
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
}
