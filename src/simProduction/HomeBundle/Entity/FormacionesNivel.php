<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\FormacionesNivel
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FormacionesNivel
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
     * @var integer $numEmpleados
     *
     * @ORM\Column(name="numEmpleados", type="integer")
     */
    private $numEmpleados;



    /**
     * @var boolean $cumpleRequisito
     *
     * @ORM\Column(name="cumpleRequisito", type="boolean")
     */
    private $cumpleRequisito;

	/**
     * @ORM\ManyToOne(targetEntity="Nivel")
     * @ORM\JoinColumn(name="nivel_empresa", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $nivel;
	
    public function setNivel(\simProduction\HomeBundle\Entity\Nivel $nivel)
    {
        $this->nivel = $nivel;
    }

    public function getNivel()
    {
        return $this->nivel;
    }
	
	/**
     * @ORM\ManyToOne(targetEntity="PerfilTrabajador")
     * @ORM\JoinColumn(name="perfilTrabajador", referencedColumnName="id", onDelete="cascade")
     * @return integer
     */
    private $perfilTrabajador;
	
    public function setPerfilTrabajador(\simProduction\HomeBundle\Entity\PerfilTrabajador $perfilTrabajador)
    {
        $this->perfilTrabajador = $perfilTrabajador;
    }

    public function getPerfilTrabajador()
    {
        return $this->perfilTrabajador;
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
     * Set numEmpleados
     *
     * @param integer $numEmpleados
     */
    public function setNumEmpleados($numEmpleados)
    {
        $this->numEmpleados = $numEmpleados;
    }

    /**
     * Get numEmpleados
     *
     * @return integer 
     */
    public function getNumEmpleados()
    {
        return $this->numEmpleados;
    }


    /**
     * Set cumpleRequisito
     *
     * @param boolean $cumpleRequisito
     */
    public function setCumpleRequisito($cumpleRequisito)
    {
        $this->cumpleRequisito = $cumpleRequisito;
    }

    /**
     * Get cumpleRequisito
     *
     * @return boolean 
     */
    public function getCumpleRequisito()
    {
        return $this->cumpleRequisito;
    }
}