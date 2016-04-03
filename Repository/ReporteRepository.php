<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 01-04-16
 * Time: 18:58
 */

namespace AscensoDigital\PerfilBundle\Repository;


use AscensoDigital\PerfilBundle\Entity\Reporte;
use Doctrine\ORM\EntityRepository;

class ReporteRepository extends EntityRepository
{
    public function findArrayByPerfil($perfil_id){
        $reps=$this->createQueryBuilder('rp')
            ->addSelect('rpc')
            ->addSelect('rps')
            ->addSelect('rpxc')
            ->addSelect('rpcr')
            ->join('rp.reporteCategoria','rpc')
            ->join('rp.reporteSeccion','rps')
            ->leftJoin('rp.reporteCriterio','rpcr')
            ->leftJoin('rp.permiso','per')
            ->leftJoin('per.perfilXPermisos','pxp')
            ->where('pxp.perfil=:perfil')
            ->orderBy('rps.orden')
            ->addOrderBy('rpc.orden')
            ->addOrderBy('rp.orden')
            ->setParameter(':perfil',$perfil_id)
            ->getQuery()->getResult();
        $ret=array();
        /** @var Reporte $rep */
        foreach ($reps as $rep) {
            if(!is_null($rep->getReporteCriterio()) && !isset($ret['criterio'][$rep->getReporteCriterio()->getNombre()])){
                $ret['criterio'][$rep->getReporteCriterio()->getNombre()]=$rep->getReporteCriterio();
            }
            $ret['reportes'][$rep->getReporteSeccion()->getNombre()]['categorias'][$rep->getReporteCategoria()->getNombre()][]=$rep;
            $ret['reportes'][$rep->getReporteSeccion()->getNombre()]['style']=$rep->getReporteSeccion()->getStyle();
        }
        return $ret;
    }
    
    public function findOneByCodigo($codigo){
        return $this->createQueryBuilder('rp')
            ->addSelect('per')
            ->addSelect('rpcr')
            ->addSelect('rpxc')
            ->leftJoin('rp.permiso','per')
            ->leftJoin('rp.reporteCriterio','rpcr')
            ->leftJoin('rp.reporteXCriterios','rpxc')
            ->where('rp.codigo=:codigo')
            ->setParameter(':codigo',$codigo)
            ->getQuery()->getOneOrNullResult();
    }
}
