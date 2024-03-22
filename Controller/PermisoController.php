<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\PerfilBundle\Entity\Perfil;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Form\Type\CsvPermisosType;
use AscensoDigital\PerfilBundle\Form\Type\PermisosFormType;
use AscensoDigital\PerfilBundle\Form\Type\PermisosPerfilFormType;
use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use AscensoDigital\PerfilBundle\Util\CsvPermisos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
    public function editAction(Request $request, Permiso $permiso) {
        $em=$this->getDoctrine()->getManager();
        $perfils=$this->get('ad_perfil.perfil_manager')->findAllOrderRole();
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
    public function editByPerfilAction(Request $request, $slug) {
        $em=$this->getDoctrine()->getManager();
        $permisos=$em->getRepository('ADPerfilBundle:Permiso')->findAllOrderNombre();

        /** @var Perfil $perfil */
        $perfil=$this->get('ad_perfil.perfil_manager')->findPerfilBy(['slug' => $slug]);
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
    public function listTableAction() {
        $em = $this->getDoctrine()->getManager();
        $filtros = $this->get('ad_perfil.filtro_manager');
        $perfils=$this->get('ad_perfil.perfil_manager')->findByFiltro($filtros);
        $permisos=$em->getRepository('ADPerfilBundle:Permiso')->findByFiltro($filtros);
        $pxps=$em->getRepository('ADPerfilBundle:PerfilXPermiso')->findByFiltros($filtros);
        return $this->render('ADPerfilBundle:Permiso:list-table.html.twig',array('permisos' => $permisos, 'perfils' => $perfils, 'pxps' => $pxps));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/new", name="ad_perfil_permiso_new")
     * @Security("is_granted('permiso','ad_perfil-per-new')")
     */
    public function newAction(Request $request) {
        $em=$this->getDoctrine()->getManager();
        $permiso=new Permiso();
        $perfils=$this->get('ad_perfil.perfil_manager')->findAllOrderRole();
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/permisos/load", name="ad_perfil_permiso_load")
     * @Security("is_granted('permiso','ad_perfil-per-load')")
     */
    public function loadAction(Request $request) {
        $em=$this->getDoctrine()->getManager();
        $csvPermisos = new CsvPermisos();

        $form=$this->createForm(CsvPermisosType::class, $csvPermisos);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if (($gestor = $csvPermisos->getFile()->openFile()) !== false) {
                $readEncabezado = true;
                $perfils = $this->get('ad_perfil.perfil_manager')->findAllOrderRole();
                $permisos = $em->getRepository('ADPerfilBundle:Permiso')->findArrayAllByNombreJoinPerfils();
                $arrPerfilSlugs = [];
                $countPermisos = 0;
                while (($datos = $gestor->fgetcsv(';')) !== false) {
                    // $numero = count($datos);
                    // var_dump($datos);
                    if($readEncabezado && $datos[0]!== "sep=") {
                        foreach ($datos as $key => $perfilSlug) {
                            if($key>1) {
                                /** @var PerfilInterface $perfil */
                                foreach ($perfils as $perfil) {
                                    if($perfil->getSlug()==$perfilSlug) {
                                        $arrPerfilSlugs[$key] = $perfil;
                                    }
                                }
                            }
                        }
                        $readEncabezado = false;
                        continue;
                    }

                    /** @var Permiso $permiso */
                    $permiso = $permisos[$datos[0]];
                    if($permiso) {
                        foreach ($datos as $key => $acceso) {
                            if(isset($arrPerfilSlugs[$key])) {
                                $boolAcceso = $acceso == 1;
                                $permiso->setPerfilAcceso($arrPerfilSlugs[$key], $boolAcceso);
                            }
                        }
                        $em->persist($permiso);
                        $countPermisos++;
                        unset($permisos[$datos[0]]);
                    }
                }
                $em->flush();
                $this->addFlash('success',"Se registro correctamente $countPermisos permisos.");
                return $this->redirectToRoute('ad_perfil_permiso_list');
            }
            else {
                $this->addFlash('danger','No se pudo arbir el archivo cargado.');
            }
        }
        return $this->render('ADPerfilBundle:Permiso:load.html.twig',array('form' => $form->createView()));
    }
}
