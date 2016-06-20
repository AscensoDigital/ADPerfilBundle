<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Menu
 *
 * @ORM\Table(name="ad_perfil_menu")
 * @ORM\Entity(repositoryClass="AscensoDigital\PerfilBundle\Repository\MenuRepository")
 */
class Menu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\SequenceGenerator(sequenceName="menu_id_seq", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=false, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    protected $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=false)
     */
    protected $orden=0;

    /**
     * @var string
     *
     * @ORM\Column(name="icono", type="string", length=50, nullable=true)
     */
    protected $icono;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=150, nullable=true)
     */
    protected $route;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    protected $visible = true;

    /**
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="menuHijos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_superior_id", referencedColumnName="id")
     * })
     */
    protected $menuSuperior;

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
     * @var Color
     *
     * @ORM\ManyToOne(targetEntity="Color")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     * })
     */
    protected $color;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="menuSuperior", cascade={"persist"} )
     */
    protected $menuHijos;



    public function __construct() {
        $this->menuHijos = new ArrayCollection();
    }


    public function __toString() {
        return (is_null($this->getMenuSuperior()) ? '' : $this->getMenuSuperior()->getNombre().' - ').$this->getNombre();
    }

    public function getMenuBase(){
        return is_null($this->getMenuSuperior()) ? $this : $this->getMenuSuperior()->getMenuBase();
    }

    public function getMenuSuperiorSlug() {
        return is_null($this->getMenuSuperior()) ? null : $this->getMenuSuperior()->getSlug();
    }

    public function getSubtitulo() {
        return (is_null($this->getMenuSuperior()) ? '': $this->getNombre());
    }

    private function getTemplate() {
        if(is_null($this->getMenuSuperior())){
            return 'section';
        }
        elseif($this->getMenuSuperior()->getId()!=$this->getMenuBase()->getId()){
            return 'subsubsection';
        }
        else{
            return 'subsection';
        }
    }

    private function getTexBuildFolder() {
        return __DIR__.'/../../../../app/Resources/doc/manual/tex/build';
    }

    public function getTitulo() {
        return (is_null($this->getMenuSuperior()) ? $this->getNombre() : $this->getMenuSuperior()->getNombre());
    }

    public function isActual(Menu $menu = null) {
        if(is_null($menu)){
            return false;
        }
        return $this->getId() == $menu->getId() || $this->isActualMenuSuperior($menu);
    }

    public function isActualMenuSuperior(Menu $menu = null) {
        if(is_null($menu)){
            return false;
        }
        if(is_null($menu->getMenuSuperior())) {
            return false;
        }
        return $this->getId() == $menu->getMenuSuperior()->getId();
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
     * @return Menu
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        $slugify= new Slugify();
        $slugPadre=$this->getMenuSuperiorSlug();
        $this->setSlug((is_null($slugPadre) ? '' : $slugPadre.'_').$slugify->slugify($nombre));
        /** @var Menu $hijo */
        foreach ($this->getMenuHijos()->getValues() as $hijo) {
            $hijo->setNombre($hijo->getNombre());
        }
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
     * @param string $slug
     * @return Menu
     */
    public function setSlug($slug)
    {
        $fs= New Filesystem();
        $destino=$this->getTexBuildFolder() . DIRECTORY_SEPARATOR . $slug.'.at';
        try {
            if($fs->exists($this->getTexBuildFolder() . DIRECTORY_SEPARATOR . $this->slug.'.at')){
                $fs->rename($this->getTexBuildFolder() . DIRECTORY_SEPARATOR . $this->slug . '.at', $destino);
            }
            elseif(!$fs->exists($destino)) {
                $template=file_get_contents(__DIR__.'/../Resources/tex' . DIRECTORY_SEPARATOR . $this->getTemplate().'.tex');
                $template=str_replace('__NOMBRE__',$this->getNombre(),$template);
                $template=str_replace('__DESCRIPCION__',$this->getDescripcion(),$template);
                $fs->dumpFile($destino,$template);
            }
        }
        catch (IOException $e ) {

        }
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $descripcion
     * @return Menu
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
     * @param string $icono
     * @return Menu
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

    /**
     * @param string $route
     * @return Menu
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
     * @param Menu $menuSuperior
     * @return Menu
     */
    public function setMenuSuperior($menuSuperior)
    {
        $this->menuSuperior = $menuSuperior;
        if($menuSuperior){
            $this->setNombre($this->getNombre());
        }
        return $this;
    }

    /**
     * @return Menu
     */
    public function getMenuSuperior()
    {
        return $this->menuSuperior;
    }

    /**
     * @param Permiso $permiso
     * @return Menu
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
     * @param Color $color
     * @return Menu
     */
    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return Color
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param int $orden
     * @return Menu
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
     * @param boolean $visible
     * @return Menu
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $menuHijos
     * @return Menu
     */
    public function setMenuHijos($menuHijos)
    {
        $this->menuHijos = $menuHijos;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMenuHijos()
    {
        return $this->menuHijos;
    }


}
