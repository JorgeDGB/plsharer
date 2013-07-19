<?php

namespace PlSharer\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use PlSharer\AuthBundle\Entity\User;
use PlSharer\RankingBundle\Entity\Vote;

/**
 * Playlist
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PlSharer\MusicBundle\Entity\PlaylistRepository")
 */
class Playlist
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="PlSharer\AuthBundle\Entity\User", inversedBy="playlists")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var Array
     *
     * @ORM\ManyToMany(targetEntity="Song", inversedBy="playlists")
     * @ORM\JoinTable(name="playlists_songs")
     */
    private $songs;

    /**
     * @var Array
     *
     * @ORM\ManyToMany(targetEntity="PlSharer\SearchBundle\Entity\Tag", inversedBy="playlists")
     * @ORM\JoinTable(name="playlists_tags")
     */
    private $tags;

    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="decimal")
     */
    private $rating;

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
     * @var Array
     *
     * @ORM\OneToMany(targetEntity="PlSharer\RankingBundle\Entity\Vote", mappedBy="playlist")
     */
    private $votes;


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
     * @return Playlist
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
     * Set author
     *
     * @param string $author
     * @return Playlist
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set songs
     *
     * @param string $songs
     * @return Playlist
     */
    public function setSongs($songs)
    {
        $this->songs = $songs;
    
        return $this;
    }

    /**
     * Get songs
     *
     * @return string 
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Playlist
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
     * Set rating
     *
     * @param float $rating
     * @return Playlist
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return float 
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Playlist
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
     * @return Playlist
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
     * Constructor
     */
    public function __construct()
    {
        $this->songs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add songs
     *
     * @param \PlSharer\MusicBundle\Entity\Song $songs
     * @return Playlist
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
     * Add tags
     *
     * @param \PlSharer\MusicBundle\Entity\Tag $tags
     * @return Playlist
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
     * Add votes
     *
     * @param \PlSharer\MusicBundle\Entity\Vote $votes
     * @return Playlist
     */
    public function addVote(\PlSharer\MusicBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;
    
        return $this;
    }

    /**
     * Remove votes
     *
     * @param \PlSharer\MusicBundle\Entity\Vote $votes
     */
    public function removeVote(\PlSharer\MusicBundle\Entity\Vote $votes)
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
}