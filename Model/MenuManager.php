<?php

namespace AscensoDigital\PerfilBundle\Model;

use AscensoDigital\PerfilBundle\Entity\Menu;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class MenuManager
{
    private $em;
    private $perfil_id;
    private $menuActual;
    private $initialized = false;

    /** @var Menu[] indexed by ID */
    private $allMenus = [];

    /** @var Menu[] raíz del árbol */
    private $treeRoot = [];

    /** @var Menu[][] submenús por ID padre */
    private $menusByPadre = [];

    public function __construct(Session $session, EntityManager $em, $sessionName)
    {
        $this->em = $em;
        $this->perfil_id = $session->get($sessionName, null);
    }

    private function initializeMenus()
    {
        if ($this->initialized) {
            return;
        }

        $menus = $this->em->getRepository('ADPerfilBundle:Menu')->findAllByPerfil($this->perfil_id);

        foreach ($menus as $menu) {
            $this->allMenus[$menu->getId()] = $menu;
            $menu->setMenuHijos(new \Doctrine\Common\Collections\ArrayCollection());
        }

        foreach ($this->allMenus as $menu) {
            $padre = $menu->getMenuSuperior();
            if ($padre && isset($this->allMenus[$padre->getId()])) {
                $this->allMenus[$padre->getId()]->addMenuHijo($menu);
                $this->menusByPadre[$padre->getId()][] = $menu;
            } else {
                $this->treeRoot[] = $menu;
            }
        }

        $this->initialized = true;
    }

    public function getFullMenuTree()
    {
        $this->initializeMenus();
        return $this->treeRoot;
    }

    public function getMenusByMenuId($menu_id)
    {
        $this->initializeMenus();
        return isset($this->menusByPadre[$menu_id]) ? $this->menusByPadre[$menu_id] : [];
    }

    public function getSubmenusByMenuId($menu_id)
    {
        return $this->getMenusByMenuId($menu_id);
    }

    public function countItems(Menu $menu = null)
    {
        $menu_id = is_null($menu) ? null : $menu->getId();
        return count($this->getMenusByMenuId($menu_id));
    }

    public function getMenuActual()
    {
        return $this->menuActual;
    }

    public function setMenuActual(Menu $menu = null)
    {
        $this->menuActual = $menu;
    }

    public function getSlugActual()
    {
        return is_null($this->menuActual) ? null : $this->menuActual->getSlug();
    }

    public function setMenuActualSinceRoute($route)
    {
        $menus = $this->em->getRepository('ADPerfilBundle:Menu')->findBy(['route' => $route]);
        $this->setMenuActual(isset($menus[0]) ? $menus[0] : null);
    }
}
