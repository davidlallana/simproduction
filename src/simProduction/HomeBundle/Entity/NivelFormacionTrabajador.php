<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\NivelFormacionTrabajador
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class NivelFormacionTrabajador
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
     * @var boolean $formado
     *
     * @ORM\Column(name="formado", type="boolean")
     */
    private $formado;
  

	/**
     * @ORM\ManyToOne(targetEntity="Formacion")
     * @ORM\JoinColumn(name="formacion", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $formacion;
	
    public function setFormacion(\simProduction\HomeBundle\Entity\Formacion $formacion)
    {
        $this->formacion = $formacion;
    }

    public function getFormacion()
    {
        return $this->formacion;
    }
	
	/**
     * @ORM\ManyToOne(targetEntity="Trabajador")
     * @ORM\JoinColumn(name="trabajador", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $trabajador;
	
    public function setTrabajador(\simProduction\HomeBundle\Entity\Trabajador $trabajador)
    {
        $this->trabajador = $trabajador;
    }

    public function getTrabajador()
    {
        return $this->trabajador;
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
     * Set formado
     *
     * @param boolean $formado
     */
    public function setFormado($formado)
    {
        $this->formado = $formado;
    }

    /**
     * Get formado
     *
     * @return boolean 
     */
    public function getFormado()
    {
        return $this->formado;
    }
	
}