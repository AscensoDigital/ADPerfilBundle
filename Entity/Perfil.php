<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 20-01-16
 * Time: 13:43
 */

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

abstract class Perfil
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AscensoDigital\PerfilBundle\Entity\PerfilXPermiso", mappedBy="perfil", cascade={"persist"} )
     */
    protected $perfilXPermisos;

    public abstract function getNombre();
    
    public function __construct() {
        $this->perfilXPermisos = new ArrayCollection();
    }

    public function loadPermisos($ps) {
        /** @var Permiso $p */
        foreach($ps as $p) {
            $encontrado=false;
            foreach($this->getPerfilXPermisos() as $pxp) {
                if($pxp->getPermiso()->getId()==$p->getId()){
                    $encontrado=true;
                    break;
                }
            }
            if(!$encontrado) {
                $pxpn=new PerfilXPermiso();
                $pxpn->setPermiso($p);
                $this->addPerfilXPermiso($pxpn);
            }
        }
    }

    /**
     * Add perfilXPermisos
     *
     * @param PerfilXPermiso $perfilXPermisos
     * @return Perfil
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
