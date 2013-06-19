<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Tarea
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Tarea
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
     * @var integer $tipo
     *
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo;

    /**
     * @var integer $idAfectado
     *
     * @ORM\Column(name="idAfectado", type="integer", nullable="true")
     */
    private $idAfectado; 


    /**
     * @var datetime $fecha
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

	/**
     * @ORM\ManyToOne(targetEntity="Prestamo")
     * @ORM\JoinColumn(name="prestamo", referencedColumnName="id", onDelete="cascade")
     * @return string
     */
    private $prestamo;
	
    public function setPrestamo(\simProduction\HomeBundle\Entity\Prestamo $prestamo)
    {
        $this->prestamo = $prestamo;
    }

    public function getPrestamo()
    {
        return $this->prestamo;
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
     * Set tipo
     *
     * @param integer $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set idAfectado
     *
     * @param integer $idAfectado
     */
    public function setIdAfectado($idAfectado)
    {
        $this->idAfectado = $idAfectado;
    }

    /**
     * Get idAfectado
     *
     * @return integer 
     */
    public function getIdAfectado()
    {
        return $this->idAfectado;
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
}