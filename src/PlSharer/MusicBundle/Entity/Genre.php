<?php

namespace PlSharer\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PlSharer\MusicBundle\Entity\GenreRepository")
 */
class Genre
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Array
     *
     * @ORM\OneToMany(targetEntity="Album", mappedBy="genre")
     */
    private $albums;

    /**
     * @var Array
     *
     * @ORM\OneToMany(targetEntity="Song", mappedBy="genre")
     */
    private $songs;


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
     * Set name
     *
     * @param string $name
     * @return Genre
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
     * Constructor
     */
    public function __construct()
    {
        $this->albums = new \Doctrine\Common\Collections\ArrayCollection();
        $this->songs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add albums
     *
     * @param \PlSharer\MusicBundle\Entity\Album $albums
     * @return Genre
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
     * Get albums
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Add songs
     *
     * @param \PlSharer\MusicBundle\Entity\Song $songs
     * @return Genre
     */
    public function addSong(\PlSharer\MusicBundle\Entity\Song $songs)
    {
        $this->songs[] = $songs;
    
        return $this;
    }

    /**
     * Remove songs
     *
     * @param \PlSharer\MusicBundle\Entity\Song $songs
     */
    public function removeSong(\PlSharer\MusicBundle\Entity\Song $songs)
    {
        $this->songs->removeElement($songs);
    }

    /**
     * Get songs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSongs()
    {
        return $this->songs;
    }
}