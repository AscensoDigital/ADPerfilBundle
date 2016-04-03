<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReporteCriterio
 *
 * @ORM\Table(name="ad_perfil_reporte_criterio")
 * @ORM\Entity
 */
class ReporteCriterio
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="reporte_criterio_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=20, nullable=false, unique=true)
     */
    protected $nombre;

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
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=100, nullable=false)
     */
    protected $titulo;


    public function __toString()
    {
        return $this->getNombre();
    }

    public function hasMetodo(){
        return !is_null($this->getMetodo());
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
     * @return ReporteCriterio
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
     * @param string $repositorio
     * @return ReporteCriterio
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
     * @return ReporteCriterio
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

    /**
     * @param string $titulo
     * @return ReporteCriterio
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }
}
