<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Ressource
 *
 * @ORM\Table(name="ressource_rubrique")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\RessourceRubriqueRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class RessourceRubrique
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="compteur", type="integer")
     */
    private $compteur;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer")
     */
    private $ordre;
    
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
     * Set nom
     *
     * @param string $nom
     *
     * @return RessourceRubrique
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
     * Set compteur
     *
     * @param integer $compteur
     *
     * @return RessourceRubrique
     */
    public function setCompteur($compteur)
    {
        $this->compteur = $compteur;

        return $this;
    }

    /**
     * Get compteur
     *
     * @return integer
     */
    public function getCompteur()
    {
        return $this->compteur;
    }
    

    /**
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return RessourceRubrique
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }


}
