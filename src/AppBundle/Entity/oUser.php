<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class oUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set login
     *
     * @param string $email
     *
     * @return oUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set login
     *
     * @param string $username
     *
     * @return oUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set login
     *
     * @param string $password
     *
     * @return oUser
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set login
     *
     * @param string $password
     *
     * @return oUser
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /**
     * Get username
     *
     * @return \AppBundle\Entity\oRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set login
     *
     * @param \AppBundle\Entity\oRole $role
     *
     * @return oUser
     */
    public function setRole(\AppBundle\Entity\oRole $role)
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return array($this->role->getCode());
    }

    public function eraseCredentials()
    {
    }
}