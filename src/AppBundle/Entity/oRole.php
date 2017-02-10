<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * oRole
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class oRole
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
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @param string $name
     *
     * @return oRole
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return oRole
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
}