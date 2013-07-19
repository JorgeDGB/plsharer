<?php

namespace PlSharer\SearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PlSharer\MusicBundle\Entity\Album;
use PlSharer\MusicBundle\Entity\Song;
use PlSharer\MusicBundle\Entity\Playlist;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PlSharer\SearchBundle\Entity\TagRepository")
 */
class Tag
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
     * @ORM\ManyToMany(targetEntity="PlSharer\MusicBundle\Entity\Album", mappedBy="tags")
     */
    private $albums;

    /**
     * @var Array
     *
     * @ORM\ManyToMany(targetEntity="PlSharer\MusicBundle\Entity\Song", mappedBy="tags")
     */
    private $songs;

    /**
     * @var Array
     *
     * @ORM\ManyToMany(targetEntity="PlSharer\MusicBundle\Entity\Playlist", mappedBy="tags")
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
     * Set name
     *
     * @param string $name
     * @return Tag
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
        $this->playlists = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add albums
     *
     * @param \PlSharer\SearchBundle\Entity\Album $albums
     * @return Tag
     */
    public function addAlbum(\PlSharer\SearchBundle\Entity\Album $albums)
    {
        $this->albums[] = $albums;
    
        return $this;
    }

    /**
     * Remove albums
     *
     * @param \PlSharer\SearchBundle\Entity\Album $albums
     */
    public function removeAlbum(\PlSharer\SearchBundle\Entity\Album $albums)
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
     * @param \PlSharer\SearchBundle\Entity\Song $songs
     * @return Tag
     */
    public function addSong(\PlSharer\SearchBundle\Entity\Song $songs)
    {
        $this->songs[] = $songs;
    
        return $this;
    }

    /**
     * Remove songs
     *
     * @param \PlSharer\SearchBundle\Entity\Song $songs
     */
    public function removeSong(\PlSharer\SearchBundle\Entity\Song $songs)
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

    /**
     * Add playlists
     *
     * @param \PlSharer\SearchBundle\Entity\Playlist $playlists
     * @return Tag
     */
    public function addPlaylist(\PlSharer\SearchBundle\Entity\Playlist $playlists)
    {
        $this->playlists[] = $playlists;
    
        return $this;
    }

    /**
     * Remove playlists
     *
     * @param \PlSharer\SearchBundle\Entity\Playlist $playlists
     */
    public function removePlaylist(\PlSharer\SearchBundle\Entity\Playlist $playlists)
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