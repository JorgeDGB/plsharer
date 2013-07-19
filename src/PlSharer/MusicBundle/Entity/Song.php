<?php

namespace PlSharer\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use PlSharer\SearchBundle\Entity\Tag;

/**
 * Song
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PlSharer\MusicBundle\Entity\SongRepository")
 */
class Song
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="length", type="integer")
     */
    private $length;

    /**
     * @var Album
     *
     * @ORM\ManyToMany(targetEntity="Album", inversedBy="songs")
     * @ORM\JoinTable(name="songss_albums")
     */
    private $albums;

    /**
     * @var Genre
     *
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="songs")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year", type="date")
     */
    private $year;

    /**
     * @var Array
     *
     * @ORM\ManyToMany(targetEntity="PlSharer\SearchBundle\Entity\Tag", inversedBy="songs")
     * @ORM\JoinTable(name="songs_tags")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @var UploadedFile
     *
     * @Assert\File(
     *     maxSize="6M",
     *     maxSizeMessage="El archivo es muy pesado ({{ size }}). El tamaño máximo permitido es {{ limit }}.",
     *     notFoundMessage="El archivo de imagen no pudo ser encontrado.",
     *     notReadableMessage="El archivo no es legible o no tiene permisos de lectura.",
     *     uploadIniSizeErrorMessage="El archivo es muy pesado ({{ size }}). El tamaño máximo permitido (php.ini) es {{ limit }}.",
     *     uploadFormSizeErrorMessage="El archivo es muy pesado. El navegador que está usando no soporta este tamaño de archivo.",
     *     uploadErrorMessage="El archivo no pudo ser cargado. Por favor, vuelva a intentarlo.")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="youtube", type="string", length=255)
     */
    private $youtube;

    /**
     * @var string
     *
     * @ORM\Column(name="grooveshark", type="string", length=255)
     */
    private $grooveshark;

    /**
     * @var Array
     *
     * @ORM\ManyToMany(targetEntity="Playlist", mappedBy="songs")
     */
    private $playlists;


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
     * Set title
     *
     * @param string $title
     * @return Song
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set length
     *
     * @param integer $length
     * @return Song
     */
    public function setLength($length)
    {
        $this->length = $length;
    
        return $this;
    }

    /**
     * Get length
     *
     * @return integer 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set albums
     *
     * @param string $albums
     * @return Song
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;
    
        return $this;
    }

    /**
     * Get albums
     *
     * @return string 
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Set genre
     *
     * @param string $genre
     * @return Song
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    
        return $this;
    }

    /**
     * Get genre
     *
     * @return string 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set year
     *
     * @param \DateTime $year
     * @return Song
     */
    public function setYear($year)
    {
        $this->year = $year;
    
        return $this;
    }

    /**
     * Get year
     *
     * @return \DateTime 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Song
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return Song
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
     * Set youtube
     *
     * @param string $youtube
     * @return Song
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;
    
        return $this;
    }

    /**
     * Get youtube
     *
     * @return string 
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set grooveshark
     *
     * @param string $grooveshark
     * @return Song
     */
    public function setGrooveshark($grooveshark)
    {
        $this->grooveshark = $grooveshark;
    
        return $this;
    }

    /**
     * Get grooveshark
     *
     * @return string 
     */
    public function getGrooveshark()
    {
        return $this->grooveshark;
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
     * Constructor
     */
    public function __construct()
    {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->playlists = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set path
     *
     * @param string $path
     * @return Song
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
     * Add albums
     *
     * @param \PlSharer\MusicBundle\Entity\Album $albums
     * @return Song
     */
    public function addAlbum(\PlSharer\MusicBundle\Entity\Album $albums)
    {
        $this->albums[] = $albums;
    
        return $this;
    }

    /**
     * Remove albums
     *
     * @param \PlSharer\MusicBundle\Entity\Album $albums
     */
    public function removeAlbum(\PlSharer\MusicBundle\Entity\Album $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Add tags
     *
     * @param \PlSharer\MusicBundle\Entity\Tag $tags
     * @return Song
     */
    public function addTag(\PlSharer\MusicBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \PlSharer\MusicBundle\Entity\Tag $tags
     */
    public function removeTag(\PlSharer\MusicBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Add playlists
     *
     * @param \PlSharer\MusicBundle\Entity\Playlist $playlists
     * @return Song
     */
    public function addPlaylist(\PlSharer\MusicBundle\Entity\Playlist $playlists)
    {
        $this->playlists[] = $playlists;
    
        return $this;
    }

    /**
     * Remove playlists
     *
     * @param \PlSharer\MusicBundle\Entity\Playlist $playlists
     */
    public function removePlaylist(\PlSharer\MusicBundle\Entity\Playlist $playlists)
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
}