<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Permiso
 *
 * @ORM\Table(name="ad_perfil_permiso")
 * @ORM\Entity
 */
class Permiso
{
    const LIBRE = 'libre';
    const RESTRICT = 'restrict';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="permiso_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false, unique=true)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    protected $descripcion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AscensoDigital\PerfilBundle\Entity\PerfilXPermiso", mappedBy="permiso", cascade={"persist"} )
     */
    protected $perfilXPermisos;

    public function __construct() {
        $this->perfilXPermisos = new ArrayCollection();
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
     * @return Permiso
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
     * @param string $descripcion
     * @return Permiso
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
     * Add perfilXPermisos
     *
     * @param PerfilXPermiso $perfilXPermisos
     * @return Permiso
     */
    public function addPerfilXPermiso(PerfilXPermiso $perfilXPermisos)
    {
        $this->perfilXPermisos[] = $perfilXPermisos;

        return $this;
    }

    /**
     * Remove perfilXPermisos
     *
     * @param PerfilXPermiso $perfilXPermisos
     */
    public function removePerfilXPermiso(PerfilXPermiso $perfilXPermisos)
    {
        $this->perfilXPermisos->removeElement($perfilXPermisos);
    }

    /**
     * Get perfilXPermisos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerfilXPermisos()
    {
        return $this->perfilXPermisos;
    }
}

