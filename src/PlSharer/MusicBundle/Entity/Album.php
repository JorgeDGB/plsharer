<?php

namespace PlSharer\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PlSharer\SearchBundle\Entity\Tag;

/**
 * Album
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PlSharer\MusicBundle\Entity\AlbumRepository")
 */
class Album
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
     * @var Artist
     *
     * @ORM\ManyToOne(targetEntity="Artist", inversedBy="albums")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    private $artist;

    /**
     * @var Genre
     *
     * @ORM\ManyToOne(targetEntity="Genre", inversedBy="albums")
     * @ORM\JoinColumn(name="genre_id", referencedColumnName="id")
     */
    private $genre;

    /**
     * @var Tag
     *
     * @ORM\ManyToMany(targetEntity="PlSharer\SearchBundle\Entity\Tag", 
     *                 inversedBy="albums")
     * @ORM\JoinTable(name="albums_tags")
     */
    private $tags;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Song", mappedBy="albums")
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
     * Set title
     *
     * @param string $title
     * @return Album
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
     * Set artist
     *
     * @param string $artist
     * @return Album
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    
        return $this;
    }

    /**
     * Get artist
     *
     * @return string 
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set genre
     *
     * @param string $genre
     * @return Album
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
     * Set tags
     *
     * @param string $tags
     * @return Album
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
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->songs = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add tags
     *
     * @param \PlSharer\MusicBundle\Entity\Tag $tags
     * @return Album
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
     * Add songs
     *
     * @param \PlSharer\MusicBundle\Entity\Song $songs
     * @return Album
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