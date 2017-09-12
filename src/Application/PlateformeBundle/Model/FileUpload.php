<?php
namespace Application\PlateformeBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap( {"beneficiaire" = "Document", "consultant" = "Document"} )
 */
abstract class FileUpload
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @ORM\Column(name="type_doc", type="string", length=255, nullable=true)
     */
    public $type;

    /**
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    public $description;

    /**
     * @Assert\File(mimeTypes={
     *          "image/jpeg",
     *          "image/png",
     *          "image/jpg",
     *          "image/gif",
     *          "application/pdf",
     *          "application/x-pdf",
     *          "application/msword",
     *          "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
     *     },
     *     mimeTypesMessage = "Le fichier choisi ne correspond pas à un fichier valide",
     *     uploadErrorMessage = "Erreur dans l'upload du fichier"
     * )
     */
    public $file;


    abstract protected function getWebDir();

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
     * @return FileUpload
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

        $zip->addFile($fileBeneficiaire, $this->path);
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
     * @return FileUpload
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
     * @return FileUpload
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
     * Set type
     *
     * @param string $type
     *
     * @return FileUpload
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
}