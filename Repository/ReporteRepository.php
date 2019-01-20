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
use Doctrine\ORM\NonUniqueResultException;

class ReporteRepository extends EntityRepository
{
    public function executeSql($sql) {
        $conn=$this->getEntityManager()->getConnection();
        return $conn->fetchAll($sql);
    }

    public function findArrayByPerfil($perfil_id){
        $reps=$this->createQueryBuilder('rp')
            ->addSelect('rpc')
            ->addSelect('rps')
            ->addSelect('rpcr')
            ->join('rp.reporteCategoria','rpc')
            ->join('rp.reporteSeccion','rps')
            ->leftJoin('rp.reporteCriterio','rpcr')
            ->leftJoin('rp.permiso','per')
            ->leftJoin('per.perfilXPermisos','pxp')
            ->where('rp.permiso IS NULL OR (pxp.perfil=:perfil AND pxp.acceso=:permitido)')
            ->orderBy('rps.orden')
            ->addOrderBy('rpc.orden')
            ->addOrderBy('rp.orden')
            ->setParameter(':perfil',$perfil_id)
            ->setParameter(':permitido','true')
            ->getQuery()->getResult();
        $ret=array('criterios' => array(), 'reportes' => array());
        /** @var Reporte $rep */
        foreach ($reps as $rep) {
            if(!is_null($rep->getReporteCriterio()) && !isset($ret['criterios'][$rep->getReporteCriterio()->getNombre()])){
                $ret['criterios'][$rep->getReporteCriterio()->getNombre()]=$rep->getReporteCriterio();
            }
            if(!isset($ret['reportes'][$rep->getReporteSeccion()->getNombre()]['categorias'][$rep->getReporteCategoria()->getNombre()]['icono'])){
                $ret['reportes'][$rep->getReporteSeccion()->getNombre()]['categorias'][$rep->getReporteCategoria()->getNombre()]['icono']= $rep->getReporteCategoria()->getIcono();
            }
            $ret['reportes'][$rep->getReporteSeccion()->getNombre()]['categorias'][$rep->getReporteCategoria()->getNombre()]['reportes'][]=$rep;
            $ret['reportes'][$rep->getReporteSeccion()->getNombre()]['style']=$rep->getReporteSeccion()->getStyle();
        }
        return $ret;
    }
    
    public function findOneByCodigo($codigo){
        $codigo= is_array($codigo) ? $codigo['codigo'] : $codigo;
        try {
            return $this->createQueryBuilder('rp')
                ->addSelect('per')
                ->addSelect('rpc')
                ->addSelect('rps')
                ->addSelect('rpcr')
                ->addSelect('rpxc')
                ->join('rp.reporteCategoria', 'rpc')
                ->join('rp.reporteSeccion', 'rps')
                ->leftJoin('rp.permiso', 'per')
                ->leftJoin('rp.reporteCriterio', 'rpcr')
                ->leftJoin('rp.reporteXCriterios', 'rpxc')
                ->where('rp.codigo=:codigo')
                ->setParameter(':codigo', $codigo)
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
