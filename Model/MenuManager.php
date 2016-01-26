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

    private $em;
    private $perfil_id;
    private $sessionName;

    public function __construct(Session $session, EntityManager $em, $sessionName)
    {
        $this->em = $em;
        $this->sessionName=$sessionName;
        $this->perfil_id = $session->get($sessionName,null);
    }

    public function countItems(Menu $menu = null) {
        $menu_id=is_null($menu) ? null : $menu->getId();
        $this->setMenuActual($menu);
        $cant=$this->em->getRepository('ADPerfilBundle:Menu')->countItems($menu_id);
        return $cant;
    }

    public function getMenusByMenuId($menu_id){
        $menu_sav_id=is_null($menu_id) ? 0 : $menu_id;
        if(!isset($this->seccion[$menu_sav_id])) {
            $this->seccion[$menu_sav_id] = $this->em->getRepository('ADPerfilBundle:Menu')->findByPerfilMenu($this->perfil_id, $menu_id);
        }
        return $this->seccion[$menu_sav_id];
    }

    public function getMenuActual($params=array()){
        if(is_null($this->menuActual)) {
            $menuActual = $this->em->getRepository('ADPerfilBundle:Menu')->findBy($params);
            $this->menuActual=isset($menuActual[0]) ? $menuActual[0] : null;
        }
        return $this->menuActual;
    }

    public function getSlugActual(){
        return is_null($this->menuActual) ? null : $this->menuActual->getSlug();
    }

    public function setMenuActual(Menu $menu = null){
        $this->menuActual=$menu;
    }

}