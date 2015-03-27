<?php

namespace JT\AdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JT\UserBundle\Entity\User;

/**
 * Rating
 *
 * @ORM\Table("ads_rating")
 * @ORM\Entity
 */
class Rating
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
     * @ORM\OneToOne(targetEntity="JT\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="ratio", type="decimal", nullable=true)
     */
    private $ratio;


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
     * Set ratio
     *
     * @param string $ratio
     * @return Rating
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return string 
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Set user
     *
     * @param \JT\UserBundle\Entity\User $user
     * @return Rating
     */
    public function setUser(\JT\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \JT\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set votes
     *
     * @param \JT\AdsBundle\Entity\Vote $votes
     * @return Rating
     */
    public function setVotes(\JT\AdsBundle\Entity\Vote $votes = null)
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * Get votes
     *
     * @return \JT\AdsBundle\Entity\Vote 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
