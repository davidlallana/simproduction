<?php

namespace simProduction\HomeBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Prestamo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Prestamo
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
     * @var float $saldoSolicitado
	 * @Assert\Min(limit = 1, message = "La cantidad solicitada tiene que ser positiva")
     * @ORM\Column(name="saldoSolicitado", type="float")
     */
    private $saldoSolicitado;

    /**
     * @var date $fechaVencimiento
     *
     * @ORM\Column(name="fechaVencimiento", type="date")
     */
    private $fechaVencimiento;

	/**
     * @var float $interes
     *
     * @ORM\Column(name="interes", type="float")
     */
    private $interes;
	
    /**
     * @var integer $plazosRestantes
     *
     * @ORM\Column(name="plazosRestantes", type="integer")
     */
    private $plazosRestantes;

	/**
     * @var float $totalDevolver
     *
     * @ORM\Column(name="totalDevolver", type="float")
     */
    private $totalDevolver;

	/**
     * @var float $plazoDevolver
     *
     * @ORM\Column(name="plazoDevolver", type="float")
     */
    private $plazoDevolver;

    /**
     * @var integer $estado
     *
     * @ORM\Column(name="estado", type="integer")
     */
    private $estado;

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
     * Set saldoSolicitado
     *
     * @param float $saldoSolicitado
     */
    public function setSaldoSolicitado($saldoSolicitado)
    {
        $this->saldoSolicitado = $saldoSolicitado;
    }

    /**
     * Get saldoSolicitado
     *
     * @return float 
     */
    public function getSaldoSolicitado()
    {
        return $this->saldoSolicitado;
    }
	
	/**
     * Set interes
     *
     * @param float $interes
     */
    public function setInteres($interes)
    {
        $this->interes = $interes;
    }

    /**
     * Get interes
     *
     * @return float 
     */
    public function getInteres()
    {
        return $this->interes;
    }

    /**
     * Set fechaVencimiento
     *
     * @param date $fechaVencimiento
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;
    }

    /**
     * Get fechaVencimiento
     *
     * @return date 
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set totalDevolver
     *
     * @param float $totalDevolver
     */
    public function setTotalDevolver($totalDevolver)
    {
        $this->totalDevolver = $totalDevolver;
    }

    /**
     * Get totalDevolver
     *
     * @return float 
     */
    public function getTotalDevolver()
    {
        return $this->totalDevolver;
    }

    /**
     * Set plazoDevolver
     *
     * @param float $plazoDevolver
     */
    public function setPlazoDevolver($plazoDevolver)
    {
        $this->plazoDevolver = $plazoDevolver;
    }

    /**
     * Get plazoDevolver
     *
     * @return float 
     */
    public function getPlazoDevolver()
    {
        return $this->plazoDevolver;
    }

    /**
     * Set plazosRestantes
     *
     * @param integer $plazosRestantes
     */
    public function setPlazosRestantes($plazosRestantes)
    {
        $this->plazosRestantes = $plazosRestantes;
    }

    /**
     * Get plazosRestantes
     *
     * @return integer 
     */
    public function getPlazosRestantes()
    {
        return $this->plazosRestantes;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}