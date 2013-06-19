<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Nivel
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Nivel
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
     * @var integer $nivel
     *
     * @ORM\Column(name="nivel", type="integer")
     */
    private $nivel;

 
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
     * @var integer $dinero
     *
     * @ORM\Column(name="dinero", type="integer")
     */
    private $dinero;

    /**
     * @var integer $produccionFija
     *
     * @ORM\Column(name="produccionFija", type="integer")
     */
    private $produccionFija;

	 /**
     * @var integer $produccionVariable
     *
     * @ORM\Column(name="produccionVariable", type="integer")
     */
    private $produccionVariable;

	
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
     * Set nivel
     *
     * @param integer $nivel
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;
    }

    /**
     * Get nivel
     *
     * @return integer 
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set dinero
     *
     * @param integer $dinero
     */
    public function setDinero($dinero)
    {
        $this->dinero = $dinero;
    }

    /**
     * Get dinero
     *
     * @return integer 
     */
    public function getDinero()
    {
        return $this->dinero;
    }

    /**
     * Set produccionFija
     *
     * @param integer $produccionFija
     */
    public function setProduccionFija($produccionFija)
    {
        $this->produccionFija = $produccionFija;
    }

    /**
     * Get produccionFija
     *
     * @return integer 
     */
    public function getProduccionFija()
    {
        return $this->produccionFija;
    }

    /**
     * Set produccionVariable
     *
     * @param integer $produccionVariable
     */
    public function setProduccionVariable($produccionVariable)
    {
        $this->produccionVariable = $produccionVariable;
    }

    /**
     * Get produccionVariable
     *
     * @return integer 
     */
    public function getProduccionVariable()
    {
        return $this->produccionVariable;
    }

	/**
     * @ORM\ManyToOne(targetEntity="Sector")
     * @ORM\JoinColumn(name="sector", referencedColumnName="id", onDelete="cascade")
     * @return integer
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
	
	public function __toString()
    {
        return  (string)$this->getNivel();
    }
}