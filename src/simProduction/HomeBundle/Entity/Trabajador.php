<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Trabajador
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Trabajador
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string $apellidos
     *
     * @ORM\Column(name="apellidos", type="string", length=255)
     */
    private $apellidos;

    /**
     * @var integer $salario
     *
     * @ORM\Column(name="salario", type="integer")
     */
    private $salario;   
	
	/**
     * @var date $incorporacion
     *
     * @ORM\Column(name="incorporacion", type="date")
     */
    private $incorporacion;

	/**
     * @ORM\ManyToOne(targetEntity="Sector")
     * @ORM\JoinColumn(name="sector", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $sector;
	
	
	/**
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @ORM\JoinColumn(name="empresa", referencedColumnName="id", onDelete="cascade")
     * @return string
     */
    private $empresa;	
	
	/**
     * @ORM\ManyToOne(targetEntity="PerfilTrabajador")
     * @ORM\JoinColumn(name="PerfilTrabajador", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $PerfilTrabajador;
	
    public function setPerfilTrabajador(\simProduction\HomeBundle\Entity\PerfilTrabajador $PerfilTrabajador)
    {
        $this->PerfilTrabajador = $PerfilTrabajador;
    }

    public function getPerfilTrabajador()
    {
        return $this->PerfilTrabajador;
    }
	
	
    public function setEmpresa(\simProduction\HomeBundle\Entity\Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    public function getEmpresa()
    {
        return $this->empresa;
    }
	
    public function setSector(\simProduction\HomeBundle\Entity\Sector $sector)
    {
        $this->sector = $sector;
    }

    public function getSector()
    {
        return $this->sector;
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
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set salario
     *
     * @param integer $salario
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;
    }

    /**
     * Get salario
     *
     * @return integer 
     */
    public function getSalario()
    {
        return $this->salario;
    }  
	
	/**
     * Set incorporacion
     *
     * @param date $incorporacion
     */
    public function setIncorporacion($incorporacion)
    {
        $this->incorporacion = $incorporacion;
    }

    /**
     * Get incorporacion
     *
     * @return date 
     */
    public function getIncorporacion()
    {
        return $this->incorporacion;
    }
	
	
	public function __toString()
    {
        return  (string)$this->getId();
    }
}