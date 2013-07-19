<?php

namespace PlSharer\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * MyUser
 *
 * @ORM\Table(name="SfUser")
 * @ORM\Entity(repositoryClass="PlSharer\AuthBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     * @Assert\NotBlank(
     *     message="El username no debe estar en blanco.",
     *     groups={"registration"}
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(
     *     message="El email {{ value }} no es un email válido.",
     *     groups={"registration"}
     * )
     * @Assert\Length(
     *     max=100,
     *     min=5,
     *     maxMessage="El email es muy largo, debe tener máximo {{ limit }} caracteres.",
     *     minMessage="El email es muy corto, debería tener al menos {{ limit }} caracteres.",
     *     groups={"registration"}
     * )
     * @Assert\NotBlank(
     *     message="El email no debe estar en blanco.",
     *     groups={"registration"}
     * )
     */
    private $email;

    /**
     * Encriptacion de clave autogenerada
     *
     * @ORM\Column(name="salt", type="string", length=32)
     * @Assert\Length(
     *     max=32,
     *     maxMessage="El salt es muy largo, debe tener máximo {{ limit }} caracteres.",
     *     min=31,
     *     minMessage="El salt es muy corto, debería tener al menos {{ limit }} caracteres."
     * )
     * @Assert\NotBlank(message="El salt no debe estar en blanco.")
     */
    protected $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(
     *     max=128,
     *     maxMessage="La contraseña es muy larga, debe tener máximo {{ limit }} caracteres.",
     *     groups={"registration"}
     * )
     * @Assert\NotBlank(
     *     message="La contraseña no debe estar en blanco.",
     *     groups={"registration"}
     * )
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gender", type="boolean")
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text")
     */
    private $bio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize="6M",
     *     maxSizeMessage="La imagen es muy pesada ({{ size }}). El tamaño máximo permitido es {{ limit }}.",
     *     mimeTypesMessage="El archivo no es una imagen válida.",
     *     notFoundMessage="El archivo de imagen no pudo ser encontrado.",
     *     notReadableMessage="El archivo de imagen no es legible o no tiene permisos de lectura.",
     *     uploadIniSizeErrorMessage="La imagen es muy pesada ({{ size }}). El tamaño máximo permitido (php.ini) es {{ limit }}.",
     *     uploadFormSizeErrorMessage="La imagen es muy pesada. El navegador que está usando no soporta este tamaño de imagen.",
     *     uploadErrorMessage="El archivo no pudo ser cargado. Por favor, vuelva a intentarlo."
     * )
     */
    private $file;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     * @Assert\NotBlank(message="isActive no debe estar en blanco.")
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_admin", type="boolean")
     */
    private $isAdmin;

    /**
     * @var Array
     *
     * @ORM\OneToMany(targetEntity="PlSharer\MusicBundle\Entity\Playlist",
     *                mappedBy="author")
     */
    private $playlists;

    /**
     * @var Array
     *
     * @ORM\OneToMany(targetEntity="PlSharer\RankingBundle\Entity\Vote", mappedBy="caster")
     */
    private $votes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->playlists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
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
     * Set username
     *
     * @param string $username
     * @return MyUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return MyUser
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
     * Set password
     *
     * @param string $password
     * @return MyUser
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MyUser
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     * @return MyUser
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return MyUser
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    
        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return MyUser
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MyUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return MyUser
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return MyUser
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     * @return MyUser
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    
        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean 
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }
    
    /**
     * Set path
     *
     * @param string $path
     * @return User
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

    /**
     * Add playlists
     *
     * @param \PlSharer\AuthBundle\Entity\Playlist $playlists
     * @return User
     */
    public function addPlaylist(\PlSharer\AuthBundle\Entity\Playlist $playlists)
    {
        $this->playlists[] = $playlists;
    
        return $this;
    }

    /**
     * Remove playlists
     *
     * @param \PlSharer\AuthBundle\Entity\Playlist $playlists
     */
    public function removePlaylist(\PlSharer\AuthBundle\Entity\Playlist $playlists)
    {
        $this->playlists->removeElement($playlists);
    }

    /**
     * Get playlists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaylists()
    {
        return $this->playlists;
    }

    /**
     * Add votes
     *
     * @param \PlSharer\AuthBundle\Entity\Vote $votes
     * @return User
     */
    public function addVote(\PlSharer\AuthBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;
    
        return $this;
    }

    /**
     * Remove votes
     *
     * @param \PlSharer\AuthBundle\Entity\Vote $votes
     */
    public function removeVote(\PlSharer\AuthBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }
}