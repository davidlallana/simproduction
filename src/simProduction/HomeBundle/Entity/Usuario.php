<?php

namespace simProduction\HomeBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Usuario
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields = "username", message = "El nombre de usuario ya existe. Intenta con otro.")
 * @UniqueEntity(fields = "email", message = "El email escrito ya existe en la base de datos.")
 * 
 */
class Usuario implements UserInterface
{
	
	/********************* metodos para el logueo de usuario *******************/
	function equals(\Symfony\Component\Security\Core\User\UserInterface $usuario)
	{
		return $this->getUsername() == $usuario->getUsername();
	}	

	function eraseCredentials()
	{
		//se deja en blanco
	}

	function getRoles()
	{
        return array($this->rol);
	}

	/***************************************************************************/
	
	public function serialize()
    {
       return serialize($this->getId());
    }
    
    public function unserialize($data)
    {
        $this->id = unserialize($data);
    }
	
	/**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\MinLength(limit = 6, message = "El nombre de usuario debe tener al menos 6 caracteres.")
	 * 
     */
    private $username;
   
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable="true")
	 * @Assert\Email( message = "Inserte un email válido.")
	 * 
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
	 * @Assert\MinLength(limit = 8, message = "La contraseña debe tener al menos 8 caracteres.")
	 * 
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable="true")
	 * 
     */
    private $salt;
	
	 /**
     * @var string $rol
     *
     * @ORM\Column(name="rol", type="string", length=255)
	 * 
     */
    private $rol;
	
	 /**
     * @var boolean $aceptacion
     *
     * @ORM\Column(name="aceptacion", type="boolean", nullable="false")
	 * 
     */
    private $aceptacion;
		
    /**
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Set username
     *
     * @param string $username
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
		$this->password = hash('sha1', $password);
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set rol
     *
     * @param string $rol
     */
    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    /**
     * Get rol
     *
     * @return string 
     */
    public function getRol()
    {
        return $this->rol;
    }

	
	/**
     * Set aceptacion
     *
     * @param boolean $aceptacion
     */
    public function setAceptacion($aceptacion)
    {
        $this->aceptacion = $aceptacion;
    }

    /**
     * Get aceptacion
     *
     * @return boolean 
     */
    public function getAceptacion()
    {
        return $this->aceptacion;
    }

	

	public function __toString()
	{
		return $this->getUsername();
	}

	public function __construct()
	{
	}


}