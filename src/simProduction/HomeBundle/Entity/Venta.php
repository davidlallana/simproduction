<?php

namespace simProduction\HomeBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Venta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Venta
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
     * @var date $fecha
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var integer $cantidad
	 * @Assert\Min(limit = 1, message = "La cantidad tiene que ser positiva")
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad;

    /**
     * @var integer $precio
	 * @Assert\Min(limit = 1, message = "El precio tiene que ser positivo")
     * @ORM\Column(name="precio", type="integer")
     */
    private $precio;

	/**
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @ORM\JoinColumn(name="empresaVendedora", referencedColumnName="id", onDelete="cascade")
     * @return string
     */
    private $empresaVendedora;
	
    public function setEmpresaVendedora(\simProduction\HomeBundle\Entity\Empresa $empresaVendedora)
    {
        $this->empresaVendedora = $empresaVendedora;
    }

    public function getEmpresaVendedora()
    {
        return $this->empresaVendedora;
    }

	/**
     * @ORM\ManyToOne(targetEntity="Empresa")
     * @ORM\JoinColumn(name="empresaCompradora", referencedColumnName="id", onDelete="cascade")
     * @return string
     */
    private $empresaCompradora;
	
    public function setEmpresaCompradora(\simProduction\HomeBundle\Entity\Empresa $empresaCompradora)
    {
        $this->empresaCompradora = $empresaCompradora;
    }

    public function getEmpresaCompradora()
    {
        return $this->empresaCompradora;
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
     * Set fecha
     *
     * @param date $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return date 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set precio
     *
     * @param integer $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    /**
     * Get precio
     *
     * @return integer 
     */
    public function getPrecio()
    {
        return $this->precio;
    }
}