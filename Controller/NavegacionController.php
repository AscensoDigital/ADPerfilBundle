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
     * @param Menu|null $menu
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/index/{menu_slug}", name="ad_perfil_menu", defaults={"menu_slug" : null})
     * @ParamConverter("menu", class="ADPerfilBundle:Menu", options={"mapping" : {"menu_slug" : "slug" }})
     * @Security("is_granted('menu',menu)")
     */
    public function indexAction(Menu $menu = null) {
        $this->get('ad_perfil.menu_manager')->setMenuActual($menu);
        $menu_id=is_null($menu) ? null : $menu->getId();
        $menus=$this->get('ad_perfil.menu_manager')->getMenusByMenuId($menu_id);
        if(!$this->isGranted('ROLE_SUPER_ADMIN') && 1==count($menus)){
            /** @var Menu $mn */
            $mn=$menus[0];
            if($mn->getRoute() != '') {
                return $this->redirectToRoute($mn->getRoute());
            }
            else {
                return $this->redirectToRoute('ad_perfil_menu',['menu_slug' => $mn->getSlug()]);
            }
        }
        return $this->render('ADPerfilBundle:Navegacion:index.html.twig', [
            'menus' => $menus,
            'menuActual' => $menu,
            'title' => is_null($menu) ? $this->getParameter('ad_perfil.navegacion.homepage_name') : $menu->getTitulo(),
            'subtitle' => is_null($menu) ? '' : $menu->getSubtitulo(),
            'canEdit' => $this->isGranted('ROLE_SUPER_ADMIN') or $this->isGranted('permiso','editar-menu')
        ]);
    }

    /**
     * @param Menu|null $menu
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function breadcrumbsAction(Menu $menu = null){
        $menu=is_null($menu) ? $this->get('ad_perfil.menu_manager')->getMenuActual() : $menu;
        return $this->render('ADPerfilBundle:Navegacion:breadcrumbs.html.twig', [
            'menu' => $menu,
            'homepage_route' => $this->getParameter('ad_perfil.navegacion.homepage_route'),
            'homepage_name' => $this->getParameter('ad_perfil.navegacion.homepage_name')]);
    }

    /**
     * @param null $menu_id
     * @param bool $submenu Diferencia al menu desplegable principal de las secciones
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuAction($menu_id=null, $submenu=true) {
        $menu_id= $submenu==false ? 0 : $menu_id;
        $menus=$this->get('ad_perfil.menu_manager')->getMenusByMenuId($menu_id);
        return $this->render('ADPerfilBundle:Navegacion:menu-'.($submenu ? 'nav' : 'li').'.html.twig', [
            'menus' => $menus,
            'menuActual' => $this->get('ad_perfil.menu_manager')->getMenuActual()
        ]);
    }

    /**
     * @param Menu|null $menu
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageTitleAction(Menu $menu = null){
        $menu=is_null($menu) ? $this->get('ad_perfil.menu_manager')->getMenuActual() : $menu;
        $options=is_null($menu) ? [
            'icono' => $this->getParameter('ad_perfil.navegacion.homepage_icono'),
            'color' => $this->getParameter('ad_perfil.navegacion.homepage_color'),
            'title' => $this->getParameter('ad_perfil.navegacion.homepage_title'),
            'subtitle' => $this->getParameter('ad_perfil.navegacion.homepage_subtitle'),
        ] : [
            'icono' => $menu->getIcono(),
            'color' => $menu->getColor()->getNombre(),
            'title' => $menu->getTitulo(),
            'subtitle' => $menu->getSubtitulo()
        ];
        return $this->render('ADPerfilBundle:Navegacion:page-title.html.twig', $options);
    }

    /**
     * @param Request $request
     * @param Menu|null $menuSuperior
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/menu/new/{menu_slug}", name="ad_perfil_menu_new", defaults={"menu_slug" : null})
     * @ParamConverter("menuSuperior", class="ADPerfilBundle:Menu", options={"mapping" : {"menu_slug" : "slug" }})
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function createAction(Request $request, Menu $menuSuperior = null){
        $menu=new Menu();
        $menu->setMenuSuperior($menuSuperior)
            ->setOrden($this->get('ad_perfil.menu_manager')->countItems($menuSuperior)+1);
        $form=$this->createForm(new MenuFormType(),$menu, ['super_admin' => $this->isGranted('ROLE_SUPER_ADMIN')]);
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

    /**
     * @param Request $request
     * @param Menu|null $menuSuperior
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/menu/edit/{menu_slug}", name="ad_perfil_menu_edit", defaults={"menu_slug" : null})
     * @ParamConverter("menu", class="ADPerfilBundle:Menu", options={"mapping" : {"menu_slug" : "slug" }})
     * @Security("has_role('ROLE_SUPER_ADMIN') or is_granted('permiso','editar-menu')")
     */
    public function editAction(Request $request, Menu $menu) {
        if(is_null($menu)){
            $this->addFlash('danger','Debes haber seleccionado un menu para editar');
            return $this->redirectToRoute('ad_perfil_menu');
        }
        $form=$this->createForm(new MenuFormType(),$menu,['super_admin' => $this->isGranted('ROLE_SUPER_ADMIN')]);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            $this->addFlash('success','Se actualizó correctamente el Menú '.$menu);
            return $this->redirectToRoute('ad_perfil_menu',[ 'menu_slug' => $menu->getMenuSuperiorSlug()]);
        }
        return $this->render('ADPerfilBundle:Navegacion:menu-edit.html.twig', [
            'form' => $form->createView(),
            'menu' => $menu
        ]);
    }
}
