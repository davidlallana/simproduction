<?php

namespace simProduction\HomeBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Lote
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Lote
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
     * @ORM\JoinColumn(name="empresa", referencedColumnName="id", onDelete="cascade")
     * @return string
     */
    private $empresa;
	
    public function setEmpresa(\simProduction\HomeBundle\Entity\Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    public function getEmpresa()
    {
        return $this->empresa;
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
}