<?php

namespace JT\AdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JT\UserBundle\Entity\User;

/**
 * Ads
 *
 * @ORM\Table(name="ads_ads")
 * @ORM\Entity(repositoryClass="JT\AdsBundle\Entity\Repository\AdsRepository")
 */
class Ads
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
     * @var string
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=128)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="JT\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $seller;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = false;

    /**
     * @ORM\ManyToMany(targetEntity="JT\AdsBundle\Entity\Image", cascade={"remove","persist"})
     * @ORM\JoinTable(name="ads_ads_image",
     *      joinColumns={@ORM\JoinColumn(name="ads_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true, onDelete="CASCADE")}
     *      )
     **/
    private $images = null;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
    
    /**
     * @var array of JT\AdsBundle\Entity\Issue
     * @ORM\OneToMany(targetEntity="JT\AdsBundle\Entity\Issue", mappedBy="ads", cascade={"remove"})
     */
    private $issues;


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
     * @return Ads
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
     * Set price
     *
     * @param string $price
     * @return Ads
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Ads
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
     * Set seller
     *
     * @param string $seller
     * @return Ads
     */
    public function setSeller(User $seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return string 
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Ads
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
     * Set slug
     *
     * @param string $slug
     * @return Ads
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add images
     *
     * @param \JT\AdsBundle\Entity\Image $images
     * @return Ads
     */
    public function addImage(\JT\AdsBundle\Entity\Image $image)
    {
    	$this->images[] = $image;
    	$image->setAds($this);
        return $this;
    }

    /**
     * Remove images
     *
     * @param \JT\AdsBundle\Entity\Image $images
     */
    public function removeImage(\JT\AdsBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add issues
     *
     * @param \JT\AdsBundle\Entity\Issue $issues
     * @return Ads
     */
    public function addIssue(\JT\AdsBundle\Entity\Issue $issues)
    {
        $this->issues[] = $issues;
		$issues->setAds($this);
        return $this;
    }

    /**
     * Remove issues
     *
     * @param \JT\AdsBundle\Entity\Issue $issues
     */
    public function removeIssue(\JT\AdsBundle\Entity\Issue $issues)
    {
        $this->issues->removeElement($issues);
    }

    /**
     * Get issues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Ads
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Ads
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
}
