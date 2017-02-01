<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UCI\AuditoriaBundle\Driver\ClassList\Traza;
/**
 * Escuela
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\DemoBundle\Entity\EscuelaRepository")
 * @Traza(descripcion="Traceando Escuela")
 */
class Escuela
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidaEst", type="integer")
     */
    private $cantidaEst;


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
     * @return Escuela
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
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
     * Set tipo
     *
     * @param string $tipo
     * @return Escuela
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set cantidaEst
     *
     * @param integer $cantidaEst
     * @return Escuela
     */
    public function setCantidaEst($cantidaEst)
    {
        $this->cantidaEst = $cantidaEst;
    
        return $this;
    }

    /**
     * Get cantidaEst
     *
     * @return integer 
     */
    public function getCantidaEst()
    {
        return $this->cantidaEst;
    }
}
