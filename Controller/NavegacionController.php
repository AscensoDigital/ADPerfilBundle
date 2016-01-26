<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\PerfilBundle\Entity\Menu;
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
    public function indexAction(Request $request, Menu $menu = null) {
        $em = $this->getDoctrine()->getManager();
        $menu_id=is_null($menu) ? null : $menu->getId();
        $perfil_id=$request->getSession()->get($this->getParameter('ad_perfil.session_name'));
        $menus=$em->getRepository('ADPerfilBundle:Menu')->findByPerfilMenu($perfil_id, $menu_id);
        return $this->render('ADPerfilBundle:Navegacion:index.html.twig', [
            'menus' => $menus,
            'menuActual' => $menu
        ]);
    }

    public function breadcrumbsAction($menu){
        return $this->render('ADPerfilBundle:Navegacion:breadcrumbs.html.twig', [
            'menu' => $menu,
            'homepage_route' => $this->getParameter('ad_perfil.navegacion.homepage_route'),
            'homepage_name' => $this->getParameter('ad_perfil.navegacion.homepage_name')]);
    }

    public function createAction(Request $request){

    }
}
