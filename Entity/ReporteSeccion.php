<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReporteSeccion
 *
 * @ORM\Table(name="ad_perfil_reporte_seccion")
 * @ORM\Entity
 */
class ReporteSeccion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="reporte_seccion_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=200, nullable=false)
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
     * @ORM\Column(name="style", type="string", length=10, nullable=true)
     */
    protected $style;


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
     * @return ReporteSeccion
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
     * @return ReporteSeccion
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
     * @param string $style
     * @return ReporteSeccion
     */
    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }
}
