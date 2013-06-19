<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Sector
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Sector
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
     * @var text $descripcion
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var integer $precioExcep
     *
     * @ORM\Column(name="precioExcep", type="integer")
     */
    private $precioExcep;
	
	/**
     * @var integer $mp
     *
     * @ORM\Column(name="mp", type="integer")
     */
    private $mp;

	 /**
     * @var string $producto
     *
     * @ORM\Column(name="producto", type="string", length=255)
     */
    private $producto;
	
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
     * Set producto
     *
     * @param string $producto
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
    }

    /**
     * Get producto
     *
     * @return string 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set descripcion
     *
     * @param text $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Get descripcion
     *
     * @return text 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set precioExcep
     *
     * @param integer $precioExcep
     */
    public function setPrecioExcep($precioExcep)
    {
        $this->precioExcep = $precioExcep;
    }

    /**
     * Get precioExcep
     *
     * @return integer 
     */
    public function getPrecioExcep()
    {
        return $this->precioExcep;
    }
	
	/**
     * Set mp
     *
     * @param integer $mp
     */
    public function setMp($mp)
    {
        $this->mp = $mp;
    }

    /**
     * Get mp
     *
     * @return integer 
     */
    public function getMp()
    {
        return $this->mp;
    }
	
	
	public function __toString()
    {
        return $this->getNombre();
    }
}