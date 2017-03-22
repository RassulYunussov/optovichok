<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * oProduct
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class oProduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=50, nullable=true)
     */
    private $photo;

    /**
     * @var string
     * @ORM\Column(name="header", type="string", length=255, nullable=false)
     */
    private $header;

    /**
     * @var \AppBundle\Entity\oProductCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\oProductCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \AppBundle\Entity\oUser
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\oUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $userid;


    /**
     * @return \AppBundle\Entity\oUser
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param \AppBundle\Entity\oUser $userid
     * @return oProduct
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
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
     * Set photo
     *
     * @param string $photo
     *
     * @return oProduct
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return oProduct
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @return \AppBundle\Entity\oProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \AppBundle\Entity\oProductCategory $category
     * @return oProduct
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return oProduct
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
