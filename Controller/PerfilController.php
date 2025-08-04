<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PerfilController extends Controller
{
    /**
     * @param Request $request
     * @param $perfil_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/change-perfil/{perfil_id}", name="ad_perfil_perfil_change")
     */
    public function changeAction(Request $request, $perfil_id){
        $user = $this->getUser();
        if ($user && $user->getPerfil($perfil_id)) {
            $perfil = $user->getPerfil($perfil_id);
            $request->getSession()->set($this->getParameter('ad_perfil.session_name'), $perfil_id);
            $this->removeFiltros($request);
            $this->addFlash('success', 'Ha cambiado a su perfil de <strong>' . $perfil->getNombre() . '</strong>');

            $routeInit = $perfil->getRouteInit();
            if ($routeInit) {
                return $this->redirectToRoute($routeInit);
            }

            return $this->redirectToRoute($this->getParameter('ad_perfil.route_redirect'));
        }

        $this->addFlash($user ? 'danger' : 'warning', $user ? 'No tiene acceso al Perfil seleccionado' : 'Se cerró la sesión, ingrese nuevamente.');
        return $this->redirectToRoute($this->getParameter('ad_perfil.route_redirect'));
    }

    public function showActiveAction(Request $request) {
        $perfilStr='Perfil';
        $multiple=$request->getSession()->get('ad_perfil.perfil_multiple',null);
        if($this->getUser()){
            $perfil_id=$request->getSession()->get($this->getParameter('ad_perfil.session_name'));
            if(!is_null($perfil_id)) {
                /** @var PerfilInterface $perfil */
                $perfil=$this->getUser()->getPerfil($perfil_id);
                if(!$perfil) {
                    $this->addFlash('danger', 'No tiene acceso al Perfil seleccionado');
                    $request->getSession()->remove($this->getParameter('ad_perfil.session_name'));
                }
                else{
                    $perfilStr=$perfil->getSlug();
                }
            }
        }
        return $this->render('@ADPerfilBundle/Perfil/showActive.html.twig', ['perfil'=> $perfilStr,'multiple' => $multiple]);
    }

    public function showOptionAction(){
        $perfils= $this->getUser() ? $this->getUser()->getPerfils() : array();
        return $this->render('@ADPerfilBundle/Perfil/showOption.html.twig',['perfils' => $perfils, 'sesionName' => $this->getParameter('ad_perfil.session_name')]);
    }

    private function removeFiltros(Request $request) {
        foreach($request->getSession()->all() as $key_ses => $data) {
            if(strpos($key_ses,'filtros_')!==false) {
                $request->getSession()->remove($key_ses);
            }
        }
    }
}
