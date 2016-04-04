<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reporte
 *
 * @ORM\Table(name="ad_perfil_reporte")
 * @ORM\Entity(repositoryClass="AscensoDigital\PerfilBundle\Repository\ReporteRepository")
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
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=200, nullable=false)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=20, nullable=false, unique=true)
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
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    protected $orden;

    /**
     * @var string
     *
     * @ORM\Column(name="repositorio", type="string", length=100, nullable=true)
     */
    protected $repositorio;

    /**
     * @var string
     *
     * @ORM\Column(name="metodo", type="string", length=100, nullable=true)
     */
    protected $metodo;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ReporteXCriterio", mappedBy="reporte", cascade={"persist"} )
     */
    protected $reporteXCriterios;



    public function __construct() {
        $this->reporteXCriterios = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNombre();
    }

    public function getNombreReporte(){
        return $this->getCodigo().'-'.$this->getNombre();
    }

    /**
     * @param $criterio_valor
     * @return ReporteXCriterio|null
     */
    public function getReporteEstatico($criterio_valor) {
        if(0==$this->getReporteXCriterios()->count()){
            return null;
        }
        /** @var ReporteXCriterio $rxp */
        foreach ($this->getReporteXCriterios()->getValues() as $rxp) {
            if($rxp->getCriterioId()==$criterio_valor){
                return $rxp;
            }
        }
        return null;
    }

    public function hasCriterio() {
        return !is_null($this->getReporteCriterio());
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
     * @return Reporte
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

    /**
     * @param int $orden
     * @return Reporte
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
     * @param \Doctrine\Common\Collections\Collection $reporteXCriterios
     * @return Reporte
     */
    public function setReporteXCriterios($reporteXCriterios)
    {
        $this->reporteXCriterios = $reporteXCriterios;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReporteXCriterios()
    {
        return $this->reporteXCriterios;
    }

    /**
     * @param string $repositorio
     * @return Reporte
     */
    public function setRepositorio($repositorio)
    {
        $this->repositorio = $repositorio;
        return $this;
    }

    /**
     * @return string
     */
    public function getRepositorio()
    {
        return $this->repositorio;
    }

    /**
     * @param string $metodo
     * @return Reporte
     */
    public function setMetodo($metodo)
    {
        $this->metodo = $metodo;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetodo()
    {
        return $this->metodo;
    }
}
