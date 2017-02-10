<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * oCompany
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class oCompany
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
     * @ORM\Column(name="email_company", type="string", length=65, nullable=false)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=45, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=45, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", length=100, nullable=false)
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=25, nullable=false)
     */
    private $phone;

    /**
     * @var \AppBundle\Entity\oRole
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\oRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="roleid", referencedColumnName="id")
     * })
     */
    private $role;


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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return oCompany
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return oCompany
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return oCompany
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return oCompany
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return oCompany
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return oCompany
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return \AppBundle\Entity\oRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param \AppBundle\Entity\oRole $role
     *
     * @return oCompany
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
}
