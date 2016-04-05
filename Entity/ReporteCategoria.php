<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReporteCategoria
 *
 * @ORM\Table(name="ad_perfil_reporte_categoria")
 * @ORM\Entity
 */
class ReporteCategoria
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="reporte_categoria_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    protected $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    protected $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="icono", type="string", length=50, nullable=true)
     */
    protected $icono;


    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $nombre
     * @return ReporteCategoria
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param int $orden
     * @return ReporteCategoria
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * @param string $icono
     * @return ReporteCategoria
     */
    public function setIcono($icono)
    {
        $this->icono = $icono;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcono()
    {
        return $this->icono;
    }
}
