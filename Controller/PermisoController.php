<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\PerfilBundle\Doctrine\FiltroManager;
use AscensoDigital\PerfilBundle\Entity\Perfil;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Form\Type\PermisosFormType;
use AscensoDigital\PerfilBundle\Form\Type\PermisosPerfilFormType;
use AscensoDigital\PerfilBundle\Model\PerfilManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PermisoController extends Controller
{
    /**
     * @param Request $request
     * @param Permiso $permiso
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/edit/{nombre}", name="ad_perfil_permiso_edit")
     * @Security("is_granted('permiso','ad_perfil-per-edit')")
     */
    public function editAction(Request $request, Permiso $permiso, PerfilManager $perfilManager) {
        $em=$this->getDoctrine()->getManager();
        $perfils=$perfilManager->findAllOrderRole();
        $permiso->loadPerfils($perfils);
        $form=$this->createForm(PermisosFormType::class,$permiso);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($permiso);
            $em->flush();
            return $this->redirectToRoute('ad_perfil_permiso_list');
        }
        return $this->render('ADPerfilBundle:Permiso:formulario.html.twig',array('form' => $form->createView(), 'subtitle' => 'Editar'));
    }

    /**
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/edit-perfil/{slug}", name="ad_perfil_permiso_edit_perfil")
     * @Security("is_granted('permiso','ad_perfil-per-edit')")
     */
    public function editByPerfilAction(Request $request, $slug, PerfilManager $perfilManager) {
        $em=$this->getDoctrine()->getManager();
        $permisos=$em->getRepository('ADPerfilBundle:Permiso')->findAllOrderNombre();

        /** @var Perfil $perfil */
        $perfil=$perfilManager->findPerfilBy(['slug' => $slug]);
        $perfil->loadPermisos($permisos);

        $form=$this->createForm(PermisosPerfilFormType::class,$perfil);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($perfil);
            $em->flush();
            return $this->redirectToRoute('ad_perfil_permiso_list');
        }
        return $this->render('ADPerfilBundle:Permiso:formularioByPerfil.html.twig',array('form' => $form->createView(), 'subtitle' => 'Editar '.$perfil->getNombre()));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/list", name="ad_perfil_permiso_list")
     * @Security("is_granted('permiso','ad_perfil-per-list')")
     */
    public function listAction() {
        return $this->render('ADPerfilBundle:Permiso:list.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/list-table", name="ad_perfil_permiso_list_table")
     * @Security("is_granted('permiso','ad_perfil-per-list')")
     */
    public function listTableAction(FiltroManager $filtroManager, PerfilManager $perfilManager) {
        $em = $this->getDoctrine()->getManager();
        $perfils=$perfilManager->findByFiltro($filtroManager);
        $permisos=$em->getRepository('ADPerfilBundle:Permiso')->findByFiltro($filtroManager);
        $pxps=$em->getRepository('ADPerfilBundle:PerfilXPermiso')->findByFiltros($filtroManager);
        return $this->render('ADPerfilBundle:Permiso:list-table.html.twig',array('permisos' => $permisos, 'perfils' => $perfils, 'pxps' => $pxps));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/new", name="ad_perfil_permiso_new")
     * @Security("is_granted('permiso','ad_perfil-per-new')")
     */
    public function newAction(Request $request, PerfilManager $perfilManager) {
        $em=$this->getDoctrine()->getManager();
        $permiso=new Permiso();
        $perfils=$perfilManager->findAllOrderRole();
        $permiso->loadPerfils($perfils);

        $form=$this->createForm(PermisosFormType::class,$permiso);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($permiso);
            $em->flush();
            $this->addFlash('success','Se registro correctamente el permiso "'.$permiso.'"');
            return $this->redirectToRoute('ad_perfil_permiso_list');
        }
        return $this->render('ADPerfilBundle:Permiso:formulario.html.twig',array('form' => $form->createView(), 'subtitle' => 'Crear'));
    }
}
