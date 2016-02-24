<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Form\Type\PermisosFormType;
use AscensoDigital\PerfilBundle\Form\Type\PermisosPerfilFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PermisoController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/edit/{nombre}", name="ad_perfil_permiso_edit")
     * @Security("is_granted('permiso','per-edit')")
     */
    public function editAction(Request $request, Permiso $permiso) {
        $em=$this->getDoctrine()->getManager();
        $perfils=$this->get('perfil_manager')->findAllOrderRole();
        $permiso->loadPerfils($perfils);
        $form=$this->createForm(PermisosFormType::class,$permiso);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $em->persist($permiso);
            $em->flush();
            return $this->redirectToRoute('ad_perfil_permiso_list');
        }
        return $this->render('ADPerfilBundle:Permiso:formulario.html.twig',array('form' => $form->createView(), 'subtitle' => 'Editar'));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/edit-perfil/{slug}", name="ad_perfil_permiso_edit_perfil")
     * @Security("is_granted('permiso','per-edit')")
     */
    public function editByPerfilAction(Request $request, $slug) {
        $em=$this->getDoctrine()->getManager();
        $permisos=$em->getRepository('ADPerfilBundle:Permiso')->findAllOrderNombre();

        $perfil=$this->get('perfil_manager')->findPerfilBy(['slug' => $slug]);
        $perfil->loadPermisos($permisos);

        $form=$this->createForm(PermisosPerfilFormType::class,$perfil);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $em->persist($perfil);
            $em->flush();
            return $this->redirectToRoute('ad_perfil_permiso_list');
        }
        return $this->render('ADPerfilBundle:Permiso:formularioByPerfil.html.twig',array('form' => $form->createView(), 'subtitle' => 'Editar '.$perfil->getNombre()));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/list", name="ad_perfil_permiso_list")
     * @Security("is_granted('permiso','per-list')")
     */
    public function listAction() {
        return $this->render('ADPerfilBundle:Permiso:list.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/list-table", name="ad_perfil_permiso_list_table")
     * @Security("is_granted('permiso','per-list')")
     */
    public function listTableAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $filtros = Filtro::procesa($request);
        $perfils=$this->get('perfil_manager')->findByFiltro($filtros);
        $permisos=$em->getRepository('ADPerfilBundle:Permiso')->findByFiltro-($filtros);
        $pxps=$em->getRepository('ADPerfilBundle:PerfilXPermiso')->findByFiltros($filtros);
        return $this->render('ADPerfilBundle:Permiso:list-table.html.twig',array('permisos' => $permisos, 'perfils' => $perfils, 'pxps' => $pxps));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/new", name="ad_perfil_permiso_new")
     * @Security("is_granted('permiso','per-new')")
     */
    public function newAction(Request $request) {
        $em=$this->getDoctrine()->getManager();
        $permiso=new Permiso();
        $perfils=$this->get('perfil_manager')->findAllOrderRole();
        $permiso->loadPerfils($perfils);

        $form=$this->createForm(PermisosFormType::class,$permiso);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()) {
            $em->persist($permiso);
            $em->flush();
            $this->addFlash('success','Se registro correctamente el permiso "'.$permiso.'"');
            return $this->redirectToRoute('ad_perfil_permiso_list');
        }
        return $this->render('ADPerfilBundle:Permiso:formulario.html.twig',array('form' => $form->createView(), 'subtitle' => 'Crear'));
    }
}
