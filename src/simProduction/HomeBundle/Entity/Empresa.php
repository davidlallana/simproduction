<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Empresa
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Empresa
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
     * @ORM\Column(name="nombre", type="string", length=28)
     */
    private $nombre;

    /**
     * @var float $saldo
     *
     * @ORM\Column(name="saldo", type="float")
     */
    private $saldo;

  
    /**
     * @var string $logo
     *
     * @ORM\Column(name="logo", type="string")
     */
    private $logo;

    /**
     * @var integer $minerales
     *
     * @ORM\Column(name="minerales", type="integer")
     */
    private $minerales;

	
    /**
     * @var integer $herramientas
     *
     * @ORM\Column(name="herramientas", type="integer")
     */
    private $herramientas;

    /**
     * @var integer $vehiculos
     *
     * @ORM\Column(name="vehiculos", type="integer")
     */
    private $vehiculos;
  
	/**
     * @var integer $salarios
     *
     * @ORM\Column(name="salarios", type="integer")
     */
    private $salarios;
 
	/**
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario", referencedColumnName="id", unique=true, onDelete="cascade")
     * @return string
     */
    private $usuario;
	
    public function setUsuario(\simProduction\HomeBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

	/**
     * @ORM\ManyToOne(targetEntity="Nivel")
     * @ORM\JoinColumn(name="nivel", referencedColumnName="id", onDelete="cascade")
     * @return string
     */
    private $nivel;
	
    public function setNivel(\simProduction\HomeBundle\Entity\Nivel $nivel)
    {
        $this->nivel = $nivel;
    }

    public function getNivel()
    {
        return $this->nivel;
    }

	/**
     * @ORM\ManyToOne(targetEntity="Sector")
     * @ORM\JoinColumn(name="sector", referencedColumnName="id", onDelete="cascade")
     * @return string
     */
    private $sector;
	
    public function setSector(\simProduction\HomeBundle\Entity\Sector $sector)
    {
        $this->sector = $sector;
    }

    public function getSector()
    {
        return $this->sector;
    }
	
	/**
     * @ORM\OneToOne(targetEntity="Cuadrante")
     * @ORM\JoinColumn(name="cuadrante", referencedColumnName="id", unique=true)
     * @return integer
     */
    private $cuadrante;
	
    public function setCuadrante(\simProduction\HomeBundle\Entity\Cuadrante $cuadrante)
    {
        $this->cuadrante = $cuadrante;
    }

    public function getCuadrante()
    {
        return $this->cuadrante;
    }
	
	/**
     * @var float $produccion
     *
     * @ORM\Column(name="produccion", type="float")
     */
    private $produccion;
	
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
     * Set saldo
     *
     * @param float $saldo
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    /**
     * Get saldo
     *
     * @return float 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }


    /**
     * Set logo
     *
     * @param string $logo
     */
    public function setLogo($logo)
    {

        $this->logo = $logo;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set minerales
     *
     * @param integer $minerales
     */
    public function setMinerales($minerales)
    {
        $this->minerales = $minerales;
    }

    /**
     * Get minerales
     *
     * @return integer 
     */
    public function getMinerales()
    {
        return $this->minerales;
    }

    /**
     * Set herramientas
     *
     * @param integer $herramientas
     */
    public function setHerramientas($herramientas)
    {
        $this->herramientas = $herramientas;
    }

    /**
     * Get herramientas
     *
     * @return integer 
     */
    public function getHerramientas()
    {
        return $this->herramientas;
    }

    /**
     * Set vehiculos
     *
     * @param integer $vehiculos
     */
    public function setVehiculos($vehiculos)
    {
        $this->vehiculos = $vehiculos;
    }

    /**
     * Get vehiculos
     *
     * @return integer 
     */
    public function getVehiculos()
    {
        return $this->vehiculos;
    }
	
	/**
     * Set salarios
     *
     * @param integer $salarios
     */
    public function setSalarios($salarios)
    {
        $this->salarios = $salarios;
    }

    /**
     * Get salarios
     *
     * @return integer 
     */
    public function getSalarios()
    {
        return $this->salarios;
    }
	
	 /**
     * Set produccion
     *
     * @param float $produccion
     */
    public function setProduccion($produccion)
    {
        $this->produccion = $produccion;
    }

    /**
     * Get produccion
     *
     * @return float 
     */
    public function getProduccion()
    {
        return $this->produccion;
    }
	
	
	public function __toString()
    {
        return $this->getNombre();
    }

}