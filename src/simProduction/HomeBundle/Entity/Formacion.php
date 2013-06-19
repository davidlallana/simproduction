<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Formacion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Formacion
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
	
   
	public function __toString()
    {
        return $this->getNombre();
    }
}