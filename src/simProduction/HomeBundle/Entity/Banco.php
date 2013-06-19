<?php

namespace simProduction\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * simProduction\HomeBundle\Entity\Banco
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Banco
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
     * @var float $capital
     *
     * @ORM\Column(name="capital", type="float")
     */
    private $capital;


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
     * Set capital
     *
     * @param float $capital
     */
    public function setCapital($capital)
    {
        $this->capital = $capital;
    }

    /**
     * Get capital
     *
     * @return float 
     */
    public function getCapital()
    {
        return $this->capital;
    }
}