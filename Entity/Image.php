<?php

namespace JT\AdsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JT\CommonBundle\Entity\Upload;

/**
 * Image
 *
 * @ORM\Table(name="ads_image")
 * @ORM\Entity
 */
class Image extends Upload
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
     * @see JT\CommonBundle\Entity\Image::getUploadDir()
     */
    public function getUploadDir()
    {
    	return 'uploads/images';
    }
    
    /**
     * @var UploadedFile
     * @Assert\Image()
     */
    public $file;
    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

}
