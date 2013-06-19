<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\PerfilTrabajador
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PerfilTrabajador
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
     * @var integer $salarioBase
     *
     * @ORM\Column(name="salarioBase", type="integer")
     */
    private $salarioBase;

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
     * Set salarioBase
     *
     * @param integer $salarioBase
     */
    public function setSalarioBase($salarioBase)
    {
        $this->salarioBase = $salarioBase;
    }

    /**
     * Get salarioBase
     *
     * @return integer 
     */
    public function getSalarioBase()
    {
        return $this->salarioBase;
    }
	
	public function __toString()
    {
        return  (string)$this->getNombre();
    }
}