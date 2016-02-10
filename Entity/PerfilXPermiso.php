<?php

namespace AscensoDigital\PerfilBundle\Entity;

use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * PerfilXPermiso
 *
 * @ORM\Table(name="ad_perfil_perfil_x_permiso")
 * @ORM\Entity(repositoryClass="AscensoDigital\PerfilBundle\Repository\PerfilXPermisoRepository")
 */
class PerfilXPermiso
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="perfil_x_permiso_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="acceso", type="boolean", nullable=false)
     */
    protected $acceso = false;

    /**
     * @var PerfilInterface
     *
     * @ORM\ManyToOne(targetEntity="AscensoDigital\PerfilBundle\Model\PerfilInterface", inversedBy="perfilXPermisos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="perfil_id", referencedColumnName="id")
     * })
     */
    protected $perfil;

    /**
     * @var Permiso
     *
     * @ORM\ManyToOne(targetEntity="Permiso", inversedBy="perfilXPermisos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="permiso_id", referencedColumnName="id")
     * })
     */
    protected $permiso;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $acceso
     * @return PerfilXPermiso
     */
    public function setAcceso($acceso)
    {
        $this->acceso = $acceso;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAcceso()
    {
        return $this->acceso;
    }

    /**
     * @param PerfilInterface $perfil
     * @return PerfilXPermiso
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
        return $this;
    }

    /**
     * @return PerfilInterface
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * @param Permiso $permiso
     * @return PerfilXPermiso
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

}
