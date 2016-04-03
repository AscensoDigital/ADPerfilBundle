<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 20-01-16
 * Time: 19:17
 */

namespace AscensoDigital\PerfilBundle\Model;


use AscensoDigital\PerfilBundle\Entity\Menu;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class MenuManager {

    private $seccion;

    /**
     * @var Menu
     */
    private $menuActual;
    
    /**
     * @var EntityManager
     */
    private $em;
    
    private $perfil_id;

    public function __construct(Session $session, EntityManager $em, $sessionName)
    {
        $this->em = $em;
        $this->perfil_id = $session->get($sessionName,null);
    }

    public function countItems(Menu $menu = null) {
        $menu_id=is_null($menu) ? null : $menu->getId();
        $this->setMenuActual($menu);
        $cant=$this->em->getRepository('ADPerfilBundle:Menu')->countItems($menu_id);
        return $cant;
    }

    public function getMenusByMenuId($menu_id){
        $menu_sav_id=is_null($menu_id) ?
            (is_null($this->getMenuActual()) ?
                0 :
                (is_null($this->getMenuActual()->getMenuSuperior()) ?
                    0 :
                    ($this->getMenuActual()->isVisible() ?
                        $this->getMenuActual()->getMenuSuperior()->getId() :
                        (is_null($this->getMenuActual()->getMenuSuperior()->getMenuSuperior()) ?
                            $this->getMenuActual()->getMenuSuperior()->getId() :
                            $this->getMenuActual()->getMenuSuperior()->getMenuSuperior()->getId()) ))) :
            $menu_id;
        if(!isset($this->seccion[$menu_sav_id])) {
            $this->seccion[$menu_sav_id] = $this->em->getRepository('ADPerfilBundle:Menu')->findByPerfilMenu($this->perfil_id, $menu_sav_id);
        }
        return $this->seccion[$menu_sav_id];
    }

    public function getMenuActual(){
        return $this->menuActual;
    }

    public function getSlugActual(){
        return is_null($this->menuActual) ? null : $this->menuActual->getSlug();
    }

    public function setMenuActual(Menu $menu = null){
        $this->menuActual=$menu;
    }

    public function getsubmenusByMenuId($menu_id){
        $menu_sav_id=is_null($menu_id) ?
            (is_null($this->getMenuActual()) ?
                0 :
                (is_null($this->getMenuActual()->getMenuSuperior()) ?
                    0 :
                    $this->getMenuActual()->getMenuSuperior()->getId() )) :
            $menu_id;
        if(!isset($this->seccion[$menu_sav_id])) {
            $this->seccion[$menu_sav_id] = $this->em->getRepository('ADPerfilBundle:Menu')->findByPerfilMenu($this->perfil_id, $menu_sav_id, false);
        }
        return $this->seccion[$menu_sav_id];
    }

    public function setMenuActualSinceRoute($route) {
        $menus=$this->em->getRepository('ADPerfilBundle:Menu')->findBy(['route' => $route]);
        $this->setMenuActual(isset($menus[0]) ? $menus[0] : null);
    }
}
