<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\CT
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CT
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
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
   
   private $descripcion;

    /**
     * @var string $imagen
     *
     * @ORM\Column(name="imagen", type="string")
     */
    private $imagen;
	
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
     * Set descripcion
     *
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }
	
	/**
     * Set imagen
     *
     * @param string $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }
	
	public function __toString()
    {
        return $this->getNombre();
    }
}