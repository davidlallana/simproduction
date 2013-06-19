<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Mensaje
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Mensaje
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
     * @var text $mensaje
     *
     * @ORM\Column(name="mensaje", type="text")
     */
    private $mensaje;

    /**
     * @var datetime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var boolean $leido
     *
     * @ORM\Column(name="leido", type="boolean", nullable="true")
     */
    private $leido;
 
	/**
     * @var boolean $borrado1
     *
     * @ORM\Column(name="borrado1", type="boolean", nullable="true", nullable="true")
     */
    private $borrado1;
	
	/**
     * @var boolean $borrado2
     *
     * @ORM\Column(name="borrado2", type="boolean", nullable="true")
     */
    private $borrado2;

	/**
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @ORM\JoinColumn(name="origen", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $origen;
	
	/**
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @ORM\JoinColumn(name="destino", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $destino;
	
	/**
     * @var string $destino2
     *
     * @ORM\Column(name="destino2", type="string", length=255, nullable="true")
     */
    private $destino2;
	
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
     * Set mensaje
     *
     * @param text $mensaje
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    /**
     * Get mensaje
     *
     * @return text 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set fecha
     *
     * @param datetime $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return datetime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set leido
     *
     * @param boolean $leido
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;
    }

    /**
     * Get leido
     *
     * @return boolean 
     */
    public function getLeido()
    {
        return $this->leido;
    }
	
    /**
     * Set borrado1
     *
     * @param boolean $borrado1
     */
    public function setBorrado1($borrado1)
    {
        $this->borrado1 = $borrado1;
    }

    /**
     * Get borrado1
     *
     * @return borrado1 
     */
    public function getBorrado1()
    {
        return $this->borrado1;
    }
	
	/**
     * Set borrado2
     *
     * @param boolean $borrado2
     */
    public function setBorrado2($borrado2)
    {
        $this->borrado2 = $borrado2;
    }

    /**
     * Get borrado2
     *
     * @return borrado2 
     */
    public function getBorrado2()
    {
        return $this->borrado2;
    }
	
	
    public function setOrigen(\simProduction\HomeBundle\Entity\Empresa $origen)
    {
        $this->origen = $origen;
    }
	
    public function getOrigen()
    {
        return $this->origen;
    }
    
	
	public function setDestino(\simProduction\HomeBundle\Entity\Empresa $destino)
    {
        $this->destino = $destino;
    }

    public function getDestino()
    {
        return $this->destino;
    }
	
	public function setDestino2($destino2)
    {
        $this->destino2 = $destino2;
    }

    public function getDestino2()
    {
        return $this->destino2;
    }
	
}