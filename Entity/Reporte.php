<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reporte
 *
 * @ORM\Table(name="ad_perfil_reporte")
 * @ORM\Entity
 */
class Reporte
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="reporte_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="nombre", type="integer", nullable=true)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=20, nullable=true)
     */
    protected $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    protected $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=100, nullable=true)
     */
    protected $route;

    /**
     * @var Permiso
     *
     * @ORM\ManyToOne(targetEntity="Permiso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="permiso_id", referencedColumnName="id")
     * })
     */
    protected $permiso;

    /**
     * @var ReporteSeccion
     *
     * @ORM\ManyToOne(targetEntity="ReporteSeccion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reporte_seccion_id", referencedColumnName="id")
     * })
     */
    protected $reporteSeccion;

    /**
     * @var ReporteCategoria
     *
     * @ORM\ManyToOne(targetEntity="ReporteCategoria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reporte_categoria_id", referencedColumnName="id")
     * })
     */
    protected $reporteCategoria;

    /**
     * @var ReporteCriterio
     *
     * @ORM\ManyToOne(targetEntity="ReporteCriterio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reporte_criterio_id", referencedColumnName="id")
     * })
     */
    protected $reporteCriterio;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $nombre
     * @return Reporte
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return int
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $codigo
     * @return Reporte
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $descripcion
     * @return Reporte
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $route
     * @return Reporte
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param Permiso $permiso
     * @return Reporte
     */
    public function setPermiso($permiso)
    {
        $this->permiso = $permiso;
        return $this;
    }

    /**
     * @return Permiso
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * @param ReporteSeccion $reporteSeccion
     * @return Reporte
     */
    public function setReporteSeccion($reporteSeccion)
    {
        $this->reporteSeccion = $reporteSeccion;
        return $this;
    }

    /**
     * @return ReporteSeccion
     */
    public function getReporteSeccion()
    {
        return $this->reporteSeccion;
    }

    /**
     * @param ReporteCategoria $reporteCategoria
     * @return Reporte
     */
    public function setReporteCategoria($reporteCategoria)
    {
        $this->reporteCategoria = $reporteCategoria;
        return $this;
    }

    /**
     * @return ReporteCategoria
     */
    public function getReporteCategoria()
    {
        return $this->reporteCategoria;
    }

    /**
     * @param ReporteCriterio $reporteCriterio
     * @return Reporte
     */
    public function setReporteCriterio($reporteCriterio)
    {
        $this->reporteCriterio = $reporteCriterio;
        return $this;
    }

    /**
     * @return ReporteCriterio
     */
    public function getReporteCriterio()
    {
        return $this->reporteCriterio;
    }


}
