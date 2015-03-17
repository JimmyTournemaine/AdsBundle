<?php

namespace JT\AdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Issue
 *
 * @ORM\Table(name="ads_issue")
 * @ORM\Entity
 */
class Issue
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
     * @ORM\Column(name="title", type="string", length=64)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    /**
     * @var ArrayCollection
     * 
     * @ORM\ManyToOne(targetEntity="JT\AdsBundle\Entity\Ads", inversedBy="issues")
     */
    private $ads;
    
    /**
     * @var JT\AdsBundle\Entity\Answer
     * 
     * @ORM\OneToOne(targetEntity="JT\AdsBundle\Entity\Answer", mappedBy="issue", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $answer = null;

    /**
     * @var \DateTime
     * 
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var JT\UserBundle\Entity\User
     * 
     * @ORM\ManyToOne(targetEntity="JT\UserBundle\Entity\User")
     */
    private $questioner;


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
     * @return Issue
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
     * Set content
     *
     * @param string $content
     * @return Issue
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
     * @return Issue
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
     * Set questioner
     *
     * @param \stdClass $questioner
     * @return Issue
     */
    public function setQuestioner($questioner)
    {
        $this->questioner = $questioner;

        return $this;
    }

    /**
     * Get questioner
     *
     * @return \stdClass 
     */
    public function getQuestioner()
    {
        return $this->questioner;
    }

    /**
     * Set answer
     *
     * @param \JT\AdsBundle\Entity\JTAdsBundle:Answer $answer
     * @return Issue
     */
    public function setAnswer(\JT\AdsBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;
		$answer->setIssue($this);
        return $this;
    }

    /**
     * Get answer
     *
     * @return \JT\AdsBundle\Entity\JTAdsBundle:Answer 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set ads
     *
     * @param \JT\AdsBundle\Entity\Ads $ads
     * @return Issue
     */
    public function setAds(\JT\AdsBundle\Entity\Ads $ads = null)
    {
        $this->ads = $ads;

        return $this;
    }

    /**
     * Get ads
     *
     * @return \JT\AdsBundle\Entity\Ads 
     */
    public function getAds()
    {
        return $this->ads;
    }
}
