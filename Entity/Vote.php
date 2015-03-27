<?php

namespace JT\AdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JT\UserBundle\Entity\User;
use JT\AdsBundle\Entity\Rating;

/**
 * Vote
 *
 * @ORM\Table("ads_vote")
 * @ORM\Entity(repositoryClass="JT\AdsBundle\Entity\Repository\VoteRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @var \JT\UserBundle\Entity\User
     *
     * @ORM\ManyToMany(targetEntity="JT\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="decimal", scale=1)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="review", type="string", length=255)
     */
    private $review;
    
    /**
     * @var array 
     * 
     * @ORM\ManyToOne(targetEntity="JT\AdsBundle\Entity\Rating")
     */
    private $rating;
    
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set note
     *
     * @param string $note
     * @return Vote
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set review
     *
     * @param string $review
     * @return Vote
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return string 
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Add user
     *
     * @param \JT\UserBundle\Entity\User $user
     * @return Vote
     */
    public function addUser(\JT\UserBundle\Entity\User $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \JT\UserBundle\Entity\User $user
     */
    public function removeUser(\JT\UserBundle\Entity\User $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
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

    /**
     * Set rating
     *
     * @param \JT\AdsBundle\Entity\Rating $rating
     * @return Vote
     */
    public function setRating(\JT\AdsBundle\Entity\Rating $rating = null)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return \JT\AdsBundle\Entity\Rating 
     */
    public function getRating()
    {
        return $this->rating;
    }
}
