<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\PerfilBundle\Entity\Menu;
use AscensoDigital\PerfilBundle\Form\MenuFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NavegacionController extends Controller
{
    /**
     * @Route("/index/{menu_slug}", name="ad_perfil_menu", defaults={"menu_slug" : null})
     * @ParamConverter("menu", class="ADPerfilBundle:Menu", options={"mapping" : {"menu_slug" : "slug" }})
     * @Security("is_granted('menu',menu)")
     */
    public function indexAction(Menu $menu = null) {
        $this->get('ad_perfil.menu_manager')->setMenuActual($menu);
        $menu_id=is_null($menu) ? null : $menu->getId();
        $menus=$this->get('ad_perfil.menu_manager')->getMenusByMenuId($menu_id);
        return $this->render('ADPerfilBundle:Navegacion:index.html.twig', [
            'menus' => $menus,
            'menuActual' => $menu
        ]);
    }

    public function breadcrumbsAction(Menu $menu = null){
        $menu=is_null($menu) ? $this->get('ad_perfil.menu_manager')->getMenuActual() : $menu;
        return $this->render('ADPerfilBundle:Navegacion:breadcrumbs.html.twig', [
            'menu' => $menu,
            'homepage_route' => $this->getParameter('ad_perfil.navegacion.homepage_route'),
            'homepage_name' => $this->getParameter('ad_perfil.navegacion.homepage_name')]);
    }

    public function menuAction() {
        $menus=$this->get('ad_perfil.menu_manager')->getMenusByMenuId(null);
        return $this->render('ADPerfilBundle:Navegacion:menu.html.twig', [
            'menus' => $menus
        ]);
    }

    public function pageTitleAction(Menu $menu = null){
        $menu=is_null($menu) ? $this->get('ad_perfil.menu_manager')->getMenuActual() : $menu;
        return $this->render('ADPerfilBundle:Navegacion:page-title.html.twig', [
            'icono' => $menu->getIcono(),
            'color' => $menu->getColor()->getNombre(),
            'title' => $menu->getMenuBase()->getNombre(),
            'subtitle' => $menu->getNombre()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/menu/new/{menu_slug}", name="ad_perfil_menu_new", defaults={"menu_slug" : null})
     * @ParamConverter("menuSuperior", class="ADPerfilBundle:Menu", options={"mapping" : {"menu_slug" : "slug" }})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function createAction(Request $request, Menu $menuSuperior = null){
        $menu=new Menu();
        $menu->setMenuSuperior($menuSuperior)
            ->setOrden($this->get('ad_perfil.menu_manager')->countItems($menuSuperior)+1);
        $form=$this->createForm(new MenuFormType(),$menu);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            $this->addFlash('success','Se creo correctamente el Menú '.$menu);
            return $this->redirectToRoute('ad_perfil_menu',[ 'menu_slug' => $this->get('ad_perfil.menu_manager')->getSlugActual()]);
        }
        return $this->render('ADPerfilBundle:Navegacion:menu-new.html.twig', [
            'form' => $form->createView(),
            'menuSuperior' => $menuSuperior
        ]);
    }
}
