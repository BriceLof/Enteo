<?php

namespace Application\PlateformeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="Application\PlateformeBundle\Repository\FeedbackRepository")
 */
class Feedback
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text", nullable=true)
     */
    private $detail;
    
    /**
    * @var string
    *
    * @ORM\Column(name="image", type="string", length=255, nullable=true)
    * 
    * @Assert\File(mimeTypes={
    *          "image/jpeg",
    *          "image/png",
    *          "image/jpg",
    *          "image/gif"
    *     },
    *     maxSize = "2048k", 
    *     mimeTypesMessage = "L'image choisi n'est pas valide",
    *     uploadErrorMessage = "Erreur dans l'upload du fichier"
    * )
    */
    private $image;
    
    /**
    * @ORM\ManyToOne(targetEntity="Application\UsersBundle\Entity\Users")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;
    
    public function __construct()
    {
        
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
     * Set type
     *
     * @param string $type
     *
     * @return Feedback
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
     * Set description
     *
     * @param string $description
     *
     * @return Feedback
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
     * Set url
     *
     * @param string $url
     *
     * @return Feedback
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return Feedback
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Feedback
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
     * Set user
     *
     * @param \Application\UsersBundle\Entity\Users $user
     *
     * @return Feedback
     */
    public function setUser(\Application\UsersBundle\Entity\Users $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\UsersBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }
}
