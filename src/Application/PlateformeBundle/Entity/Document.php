<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="Beneficiaire", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $beneficiaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\File(mimeTypes={
     *          "image/jpeg",
     *          "image/png",
     *          "image/jpg",
     *          "image/gif",
     *          "application/pdf",
     *          "application/x-pdf"
     *     },
     *     mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     */
    public $file;

    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;


    public function getWebDir()
    {
        return 'uploads/beneficiaire/documents/'.$this->beneficiaire->getId();
    }

    protected function getUploadDir()
    {
        return __DIR__.'/../Resources/public/uploads/Documents/.'.$this->beneficiaire->getId();
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getWebDir();
    }



    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
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
     * Set file
     *
     * @param string $file
     *
     * @return Document
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload()
    {
        $this->tempFile = $this->getAbsolutePath();
        $this->oldFile = $this->getPath();

        if (null !== $this->file){
            $this->path = sha1(uniqid(mt_rand(),true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        $zip = new \ZipArchive();
        $zip_path=$this->getUploadRootDir().'/download.zip';

        if($zip->open($zip_path) === TRUE){
        }else {
            if ($zip->open($zip_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                die ("An error occurred creating your ZIP file.");
            }
        }

        $fileBeneficiaire = $this->file->move($this->getUploadRootDir(), $this->path);

        $zip->addFile($fileBeneficiaire, $this->path) ;
        $zip->close();

        //supprimer le fichier original non-compressé
        $this->tempFile = $this->getAbsolutePath();
        if (file_exists($this->tempFile))
            unlink($this->tempFile);

        unset($fileBeneficiaire);
        unset($this->file);

        if ($this->oldFile != null) unlink($this->tempFile);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(){
        $this->tempFile = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (file_exists($this->tempFile))
            unlink($this->tempFile);
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }



    public function __toString()
    {
        return $this->getId() . " - " . $this->getFile();
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Document
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
     * Set beneficiaire
     *
     * @param \Application\PlateformeBundle\Entity\Beneficiaire $beneficiaire
     *
     * @return Document
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
