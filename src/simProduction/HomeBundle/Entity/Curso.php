<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Curso
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Curso
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
     * @var integer $precio
     *
     * @ORM\Column(name="precio", type="integer")
     */
    private $precio; 

	
	/**
     * @ORM\ManyToOne(targetEntity="CT")
     * @ORM\JoinColumn(name="CT", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $CT;
	
    public function setCT(\simProduction\HomeBundle\Entity\CT $CT)
    {
        $this->CT = $CT;
    }

    public function getCT()
    {
        return $this->CT;
    }
	
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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