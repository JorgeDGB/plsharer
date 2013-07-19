<?php

namespace PlSharer\RankingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use PlSharer\MusicBundle\Entity\Playlist;
use PlSharer\AuthBundle\Entity\User;

/**
 * Vote
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PlSharer\RankingBundle\Entity\VoteRepository")
 */
class Vote
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="PlSharer\AuthBundle\Entity\User", inversedBy="votes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $caster;

    /**
     * @var Playlist
     *
     * @ORM\ManyToOne(targetEntity="PlSharer\MusicBundle\Entity\Playlist", inversedBy="votes")
     * @ORM\JoinColumn(name="playlist_id", referencedColumnName="id")
     */
    private $playlist;

    /**
     * @var integer
     *
     * @ORM\Column(name="stars", type="integer")
     */
    private $stars;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;


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
     * Set caster
     *
     * @param string $caster
     * @return Vote
     */
    public function setCaster($caster)
    {
        $this->caster = $caster;
    
        return $this;
    }

    /**
     * Get caster
     *
     * @return string 
     */
    public function getCaster()
    {
        return $this->caster;
    }

    /**
     * Set playlist
     *
     * @param string $playlist
     * @return Vote
     */
    public function setPlaylist($playlist)
    {
        $this->playlist = $playlist;
    
        return $this;
    }

    /**
     * Get playlist
     *
     * @return string 
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * Set stars
     *
     * @param integer $stars
     * @return Vote
     */
    public function setStars($stars)
    {
        $this->stars = $stars;
    
        return $this;
    }

    /**
     * Get stars
     *
     * @return integer 
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Vote
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
}