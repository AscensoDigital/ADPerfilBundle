<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\PerfilBundle\Entity\Menu;
use AscensoDigital\PerfilBundle\Form\Type\MenuFormType;
use AscensoDigital\PerfilBundle\Model\MenuManager;
use AscensoDigital\PerfilBundle\Model\PerfilManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
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
    public function indexAction(Menu $menu = null, MenuManager $menuManager ) {
        $menuManager->setMenuActual($menu);
        $menu_id=is_null($menu) ? null : $menu->getId();
        $menus=$menuManager->getMenusByMenuId($menu_id);
        $canCreate=$this->isGranted('permiso','ad_perfil-menu-new');
        if(!$canCreate && 1==count($menus)){
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
            'canEdit' => $this->isGranted('permiso','ad_perfil-menu-edit'),
            'canCreate' => $canCreate
        ]);
    }

    /**
     * @param Menu|null $menu
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function breadcrumbsAction(Menu $menu = null, MenuManager $menuManager){
        $menu=is_null($menu) ? $menuManager->getMenuActual() : $menu;
        return $this->render('ADPerfilBundle:Navegacion:breadcrumbs.html.twig', [
            'menu' => $menu,
            'homepage_route' => $this->getParameter('ad_perfil.navegacion.homepage_route'),
            'homepage_name' => $this->getParameter('ad_perfil.navegacion.homepage_name')]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/mapa-sitio", name="ad_perfil_mapa_sitio")
     * @Security("is_granted('permiso','ad_perfil-mn-mapa-sitio')")
     */
    public function mapaSitioAction(Request $request, MenuManager $menuManager, PerfilManager $perfilManager) {
        $menus=$menuManager->getMenusByMenuId(null);
        $perfil=$perfilManager->find($request->getSession()->get($this->getParameter('ad_perfil.session_name')));
        return $this->render('ADPerfilBundle:Navegacion:mapa-sitio.html.twig',['menus' => $menus, 'perfil' => $perfil]);
    }

    /**
     * @param null $menu_id
     * @param bool $lateral Diferencia al menu desplegable principal de las secciones
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuAction($menu_id=null, $lateral=false, MenuManager $menuManager) {
        $menu_id= $lateral===true ? 0 : $menu_id;
        $menus=$menuManager->getMenusByMenuId($menu_id);
        return $this->render('ADPerfilBundle:Navegacion:menu-'.(false===$lateral ? 'nav' : 'li').'.html.twig', [
            'menus' => $menus,
            'menuActual' => $menuManager->getMenuActual()
        ]);
    }

    /**
     * @param Request $request
     * @param Menu|null $menu
     * @param array|null $options
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageTitleAction(Request $request, Menu $menu = null, $options = array(), MenuManager $menuManager){
        $options=array_merge($options,$request->attributes->all());
        $menu=is_null($menu) ? $menuManager->getMenuActual() : $menu;
        $options=is_null($menu) ? [
            'icono' => isset($options['icono']) ? $options['icono'] : $this->getParameter('ad_perfil.navegacion.homepage_icono'),
            'color' => isset($options['color']) ? $options['color'] : $this->getParameter('ad_perfil.navegacion.homepage_color'),
            'title' => isset($options['title']) ? $options['title'] : $this->getParameter('ad_perfil.navegacion.homepage_title'),
            'subtitle' => isset($options['subtitle']) ? $options['subtitle'] : $this->getParameter('ad_perfil.navegacion.homepage_subtitle'),
        ] : [
            'icono' => isset($options['icono']) ? $options['icono'] : $menu->getIcono(),
            'color' => isset($options['color']) ? $options['color'] : $menu->getColor()->getNombre(),
            'title' => isset($options['title']) ? $options['title'] : $menu->getTitulo(),
            'subtitle' => isset($options['subtitle']) ? $options['subtitle'] : $menu->getSubtitulo()
        ];
        return $this->render('ADPerfilBundle:Navegacion:page-title.html.twig', $options);
    }

    /**
     * @param null $menu_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function submenuAction($menu_id=null, MenuManager $menuManager) {
        $menus=$menuManager->getSubmenusByMenuId($menu_id);
        return $this->render('ADPerfilBundle:Navegacion:menu-nav-tab.html.twig', [
            'menus' => $menus,
            'menuActual' => $menuManager->getMenuActual()
        ]);
    }

    /**
     * @param Request $request
     * @param Menu|null $menuSuperior
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/menu/new/{menu_slug}", name="ad_perfil_menu_new", defaults={"menu_slug" : null})
     * @ParamConverter("menuSuperior", class="ADPerfilBundle:Menu", options={"mapping" : {"menu_slug" : "slug" }})
     * @Security("is_granted('permiso','ad_perfil-menu-new')")
     */
    public function createAction(Request $request, Menu $menuSuperior = null, MenuManager $menuManager){
        $menu=new Menu();
        $menu->setMenuSuperior($menuSuperior)
            ->setOrden($menuManager->countItems($menuSuperior)+1);
        $form=$this->createForm(MenuFormType::class,$menu, ['super_admin' => true]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();
            $this->addFlash('success','Se creo correctamente el Menú '.$menu);
            return $this->redirectToRoute('ad_perfil_menu',[ 'menu_slug' => $menuManager->getSlugActual()]);
        }
        return $this->render('ADPerfilBundle:Navegacion:menu-new.html.twig', [
            'form' => $form->createView(),
            'menuSuperior' => $menuSuperior
        ]);
    }

    /**
     * @param Request $request
     * @param Menu|null $menu
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/menu/edit/{menu_slug}", name="ad_perfil_menu_edit", defaults={"menu_slug" : null})
     * @ParamConverter("menu", class="ADPerfilBundle:Menu", options={"mapping" : {"menu_slug" : "slug" }})
     * @Security("is_granted('permiso','ad_perfil-menu-edit')")
     */
    public function editAction(Request $request, Menu $menu) {
        if(is_null($menu)){
            $this->addFlash('danger','Debes haber seleccionado un menu para editar');
            return $this->redirectToRoute('ad_perfil_menu');
        }
        $form=$this->createForm(MenuFormType::class,$menu,['super_admin' => $this->isGranted('permiso','ad_perfil-menu-new')]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
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
