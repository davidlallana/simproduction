<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Cuadrante
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cuadrante
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
     * @var integer $coordX
     *
     * @ORM\Column(name="coordX", type="integer")
     */
    private $coordX;

    /**
     * @var integer $coordY
     *
     * @ORM\Column(name="coordY", type="integer")
     */
    private $coordY;

    /**
     * @var boolean $ocupado
     *
     * @ORM\Column(name="ocupado", type="boolean")
     */
    private $ocupado;


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
     * Set coordX
     *
     * @param integer $coordX
     */
    public function setCoordX($coordX)
    {
        $this->coordX = $coordX;
    }

    /**
     * Get coordX
     *
     * @return integer 
     */
    public function getCoordX()
    {
        return $this->coordX;
    }

    /**
     * Set coordY
     *
     * @param integer $coordY
     */
    public function setCoordY($coordY)
    {
        $this->coordY = $coordY;
    }

    /**
     * Get coordY
     *
     * @return integer 
     */
    public function getCoordY()
    {
        return $this->coordY;
    }

    /**
     * Set ocupado
     *
     * @param boolean $ocupado
     */
    public function setOcupado($ocupado)
    {
        $this->ocupado = $ocupado;
    }

    /**
     * Get ocupado
     *
     * @return boolean 
     */
    public function getOcupado()
    {
        return $this->ocupado;
    }
	
	public function __toString()
    {
        return  (string)$this->getId();
    }
}