<?php

namespace AscensoDigital\PerfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    protected $orden;

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
     * @var Menu
     *
     * @ORM\ManyToOne(targetEntity="Menu")
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

    public function getTitulo() {
        return (is_null($this->getMenuSuperior()) ? $this->getNombre() : $this->getMenuSuperior()->getNombre());
    }

    public function isActual(Menu $menu = null) {
        if(is_null($menu)){
            return false;
        }
        return $this->getId()== $menu->getId();
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


}

