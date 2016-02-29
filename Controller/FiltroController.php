<?php

namespace AscensoDigital\PerfilBundle\Controller;


use AscensoDigital\PerfilBundle\Form\Type\FiltroFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpFoundation\Request;

class FiltroController extends Controller {

    /* public function filtroNameAction(Request $request) {
        $options=array(
            'route' => 'route_name', # required
            'route_params' => array(), # optional
            'update' => 'id_etiqueta_update', # required
            'filtros' => array(
                'filtro_key1' => array('required'=> true, 'multiple' => false), #arreglo de opciones para sobreescribir las definidas en config del filtro
                'filtro_key2' => array()
            ),
            'auto_filter' => true, # optional define ejecucion automatica si los filtros required estan con valores default: true
            'auto_llenado' => true # optional define si los filtros son guardados para volver a presentarlos default: true
        );
        return $this->filtroAction($request, $options);
    } */

    public function permisoAction(Request $request) {
        $options=array(
            'route' => 'ad_perfil_permiso_list_table',
            'update' => 'table_permisos',
            'filtros' => array(
                'adperfil_perfil' => ['multiple' => true],
                'adperfil_permiso' => []
            ),
        );
        return $this->filtroAction($request, $options);
    }

    protected function filtroAction(Request $request, $options) {
        $options=$this->validateOptions($options);
        $options['perfil']=$this->get('ad_perfil.perfil_manager')->find($request->getSession()->get($this->getParameter('ad_perfil.session_name')));
        
        $form = $this->createForm(FiltroFormType::class, null, $options);

        $filtro_values=$this->get('ad_perfil.filtro_manager')->getFiltros();
        if(true===$options['auto_llenado'] && count($filtro_values)){
            $request->request->set('ad_perfil_filtros', $filtro_values);
            $request->setMethod('post');
            $form->handleRequest($request);
        }

        return $this->render('ADPerfilBundle:Filtro:filtros.html.twig', array(
            'form' => $form->createView(),
            'activos' => $options['filtros']));
    }

    private function validateOptions($options) {
        if(!isset($options['route'])) {
            throw new LogicException('Filtros: Opción "route" obligatoria');
        }
        if(!isset($options['update'])) {
            throw new LogicException('Filtros: Opción "update" obligatoria');
        }
        if(!isset($options['filtros']) || 0==count($options['filtros'])) {
            throw new LogicException('Filtros: Opción "filtros" debe tener al menos 1 filtro');
        }

        $defaults=array(
            'auto_filter' => true,
            'auto_llenado' => true,
            'route_params' => array()
        );
        return $options + $defaults;
    }
}
