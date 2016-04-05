<?php

namespace AscensoDigital\PerfilBundle\Controller;

use AscensoDigital\ComponentBundle\Util\StrUtil;
use AscensoDigital\PerfilBundle\Entity\Archivo;
use AscensoDigital\PerfilBundle\Entity\Reporte;
use AscensoDigital\PerfilBundle\Entity\ReporteXCriterio;
use AscensoDigital\PerfilBundle\Form\Type\ReporteFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReporteController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/reporte/edit/{codigo}", name="ad_perfil_reporte_edit")
     * @Security("is_granted('permiso','ad_perfil-rep-edit')")
     * @ParamConverter("reporte", class="ADPerfilBundle:Reporte", options={"repository_method" : "findOneByCodigo" })
     */
    public function editAction(Request $request, Reporte $reporte) {
        if(is_null($reporte)){
            $this->addFlash('warning','No existe el reporte que se desea modificar');
            return $this->redirectToRoute('ad_perfil_reportes');
        }
        $em=$this->getDoctrine()->getManager();
        $form=$this->createForm(new ReporteFormType(),$reporte);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($reporte);
            $em->flush();
            $this->addFlash('success','Se creo correctamente el reporte: '.$reporte);
            return $this->redirectToRoute('ad_perfil_reportes');
        }
        return $this->render('ADPerfilBundle:Reporte:new.html.twig',['form' => $form->createView()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/reportes", name="ad_perfil_reportes")
     * @Security("is_granted('permiso','ad_perfil-mn-reporte')")
     */
    public function listAction()
    {
        $data=$this->get('ad_perfil.reporte_manager')->getDataReportesForList();
        return $this->render('ADPerfilBundle:Reporte:list.html.twig', array(
            'data' => $data,
            'canEdit' => $this->isGranted('permiso','ad_perfil-rep-edit'),
            'canLoad' => $this->isGranted('permiso','ad_perfil-rep-load-estatico')));
    }

    /**
     * @param Request $request
     * @param Reporte $reporte
     * @Route("/reporte/load-estatico/{codigo}", name="ad_perfil_reporte_load_estatico")
     * @Security("is_granted('permiso','ad_perfil-rep-load-estatico')")
     * @ParamConverter("reporte", class="ADPerfilBundle:Reporte", options={"repository_method" : "findOneByCodigo" })
     */
    public function loadEstaticoAction(Request $request, Reporte $reporte){
        $em=$this->getDoctrine()->getManager();
        $reporteXCriterio=new ReporteXCriterio();
        $reporteXCriterio->setReporte($reporte);
        $form=$this->createForm(new ReporteFormType(),$reporteXCriterio,[
            'criterio_choices' => $this->get('ad_perfil.reporte_manager')->getCriterioChoices($reporte->getReporteCriterio())
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var ReporteXCriterio $rxp */
            $rxp=$em->getRepository('ADPerfilBundle:ReporteXCriterio')->findOneBy(['reporte' => $reporte->getId(), 'criterioId' => $reporteXCriterio->getCriterioId()]);
            if($rxp) {
                $rxp->setArchivo($reporteXCriterio->getArchivo());
            }
            else {
                $rxp=$reporteXCriterio;
            }

            /** @var Archivo $archivo */
            $archivo = $rxp->getArchivo();
            $directorio = "reportes/estaticos";
            $criterio_nombre=$this->get('ad_perfil.reporte_manager')->getCriterioNombre($reporte->getReporteCriterio(),$rxp->getCriterioId());
            $nombre = $reporte->getNombreReporte().'-'.$criterio_nombre.'-'.date('Y_m_d_H_i_s');
            $archivo->upload($directorio, $nombre);
            $archivo->setCreador($this->getUser());
            $archivo->setTitulo($nombre);
            $em->persist($archivo);
            $em->persist($rxp);
            $em->flush();
            $this->addFlash('success','Se registro correctamente el archivo estatico para el reporte: '.$reporte);
            return $this->redirectToRoute('ad_perfil_reportes');
        }
        return $this->render('ADPerfilBundle:Reporte:new.html.twig',['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/reporte/new", name="ad_perfil_reporte_new")
     * @Security("is_granted('permiso','ad_perfil-rep-new')")
     */
    public function newAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $rp=new Reporte();
        $form=$this->createForm(new ReporteFormType(),$rp);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($rp);
            $em->flush();
            $this->addFlash('success','Se creo correctamente el reporte: '.$rp);
            return $this->redirectToRoute('ad_perfil_reportes');
        }
        return $this->render('ADPerfilBundle:Reporte:new.html.twig',['form' => $form->createView()]);
    }

    /**
     * @param Reporte $reporte
     * @param $criterio_valor
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/reporte/{reporte_id}/criterio/{criterio_valor}", name="ad_perfil_reporte", defaults={"criterio_valor" : null})
     * @ParamConverter("reporte", class="ADPerfilBundle:Reporte", options={"repository_method" : "findOneByCodigo", "mapping" : {"reporte_id" : "codigo"} })
     */
    public function reporteAction(Reporte $reporte, $criterio_valor){
        if(is_null($reporte)){
            $this->addFlash('warning','No existe el reporte solicitado');
            return $this->redirectToRoute('ad_perfil_reportes');
        }
        if(!is_null($reporte->getPermiso())) {
            if (!$this->isGranted('permiso', $reporte->getPermiso()->getNombre())) {
                $this->addFlash('danger', 'No tienes permisos para ver este reporte');
                return $this->redirectToRoute('ad_perfil_reportes');
            }
        }
        $estatico=$reporte->getReporteEstatico($criterio_valor);
        if(!is_null($estatico)){
            return $this->downloadFileAction($estatico->getArchivo()->getId());
        }
        $metodo=$reporte->getMetodo();
        if($reporte->hasCriterio()){
            $data=$this->getDoctrine()->getRepository($reporte->getRepositorio())->$metodo($criterio_valor);
            $criterio_nombre=$this->get('ad_perfil.reporte_manager')->getCriterioNombre($reporte->getReporteCriterio(),$criterio_valor);
            $nombre=$reporte->getNombreReporte().'-'.$criterio_nombre;
        }
        else {
            $data=$this->getDoctrine()->getRepository($reporte->getRepositorio())->$metodo();
            $nombre=$reporte->getNombreReporte();
        }
        return $this->generarReporte($data, $nombre);
    }
    
    /**
     * @param $data
     * @param $nombre_base
     * @param bool $return_reporte
     * @return Response
     */
    protected function generarReporte($data,$nombre_base,$return_reporte=true) {
        $contenido=$this->renderView('ADPerfilBundle:Reporte:reporte.csv.twig', array('data' => $data));
        $file=StrUtil::formatReport($contenido);
        $reporte=$this->saveReporte($nombre_base, $file);
        if($return_reporte){
            $response=new Response($file);
            $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=ISO-8859-1');
            $response->headers->set('Content-Disposition', 'attachment;filename='.$reporte['nombre']);
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            return $response;
        }
        else{
            return new Response(json_encode(array('id' => $reporte['archivo']->getId())));
        }
    }

    /**
     * @param $nombre_base
     * @param $file
     * @return array
     */
    protected function saveReporte($nombre_base,$file) {
        $fecha=new \DateTime();
        $nombre=$nombre_base.'-'.$fecha->format('Y_m_d_H_i_s').'.csv';
        $archivo= new Archivo();
        $archivo->setMimeType('text/vnd.ms-excel')
            ->setTitulo(str_replace('-',' ',$nombre_base).' - '.$fecha->format('Y-m-d H-i-s'))
            ->setCreador($this->getUser())
            ->saveFile('reportes',$nombre,$file);
        $em=$this->getDoctrine()->getManager();
        $em->persist($archivo);
        $em->flush();
        return array('nombre' =>$nombre, 'archivo' => $archivo);
    }

    /**
     * @param $id
     * @return Response
     */
    protected function downloadFileAction($id) {
        $em = $this->getDoctrine()->getManager();
        /** @var Archivo $file */
        $file = $em->getRepository('ADPerfilBundle:Archivo')->find($id);
        if(is_null($file)){
            throw new NotFoundHttpException('Archivo No encontrado');
        }
        $response = new BinaryFileResponse($file->getPath());
        // Give the file a name:
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$file->getNombre());

        return $response;
    }
}
