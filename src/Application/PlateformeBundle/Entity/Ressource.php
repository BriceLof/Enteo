<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Ressource
 *
 * @ORM\Table(name="ressource")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\RessourceRepository")

 */
class Ressource
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
     * @var string
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=20, nullable=true)
     */
    private $format;

    /**
     * @ORM\Column(type="string")
     *
     * 
     * @Assert\File(mimeTypes={
     *          "image/jpeg",
     *          "image/png",
     *          "image/jpg",
     *          "image/gif",
     *           "image/tiff",
     *          "application/pdf",
     *          "application/x-pdf",
     *          "application/msword",
     *          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *          "application/vnd.ms-excel",
     *          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *          "text/csv"
     *     },
     *     mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="Application\PlateformeBundle\Entity\RessourceRubrique")
     */
    private $rubrique;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Ressource
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Ressource
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set format
     *
     * @param string $format
     *
     * @return Ressource
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }


    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }


    public function getFile()
    {
        return $this->file;
    }


    /**
     * Set rubrique
     *
     * @param \Application\PlateformeBundle\Entity\RessourceRubrique $rubrique
     *
     * @return Ressource
     */
    public function setRubrique(\Application\PlateformeBundle\Entity\RessourceRubrique $rubrique = null)
    {
        $this->rubrique = $rubrique;

        return $this;
    }

    /**
     * Get rubrique
     *
     * @return \Application\PlateformeBundle\Entity\RessourceRubrique
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }
}
