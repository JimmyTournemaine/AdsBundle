<?php

namespace JT\AdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Answer
 *
 * @ORM\Table(name="ads_answer")
 * @ORM\Entity
 */
class Answer
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var \JT\AdsBundle\Entity\Issue
     * @ORM\OneToOne(targetEntity="JT\AdsBundle\Entity\Issue", inversedBy="answer")
     */
    private $issue;


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
     * Set content
     *
     * @param string $content
     * @return Answer
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Answer
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
     * Set issue
     *
     * @param \JT\AdsBundle\Entity\Issue $issue
     * @return Answer
     */
    public function setIssue(\JT\AdsBundle\Entity\Issue $issue = null)
    {
        $this->issue = $issue;
		
        return $this;
    }

    /**
     * Get issue
     *
     * @return \JT\AdsBundle\Entity\Issue 
     */
    public function getIssue()
    {
        return $this->issue;
    }
}
