<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\MovimientoFinanciero
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MovimientoFinanciero
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
     * @var string $motivo
     *
     * @ORM\Column(name="motivo", type="string", length=255)
     */
    private $motivo;

    /**
     * @var float $saldo
     *
     * @ORM\Column(name="saldo", type="float")
     */
    private $saldo;

    /**
     * @var float $operacion
     *
     * @ORM\Column(name="operacion", type="float")
     */
    private $operacion;

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
     * Set motivo
     *
     * @param string $motivo
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    /**
     * Get motivo
     *
     * @return string 
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     */
    public function setSaldo($saldo)
    {
        $this->saldo= $saldo;
    }

    /**
     * Get saldo
     *
     * @return float 
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set operacion
     *
     * @param float $operacion
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;
    }

    /**
     * Get operacion
     *
     * @return float 
     */
    public function getOperacion()
    {
        return $this->operacion;
    }
}