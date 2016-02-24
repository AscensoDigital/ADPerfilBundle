<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 21-01-16
 * Time: 7:44
 */

namespace AscensoDigital\PerfilBundle\Repository;


use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use Doctrine\ORM\EntityRepository;

class PerfilXPermisoRepository extends EntityRepository {

    public function findArrayIdByPerfil($perfil_id) {
        $prms=$this->getEntityManager()->createQueryBuilder()
            ->select('adp_prm.nombre')
            ->from('ADPerfilBundle:PerfilXPermiso','adp_pxp')
            ->join('adp_pxp.permiso','adp_prm')
            ->where('adp_pxp.perfil=:perfil')
            ->setParameter(':perfil',$perfil_id)
            ->getQuery()->getScalarResult();
        $ret=array();
        foreach ($prms as $prm) {
            $ret[]=$prm['nombre'];
        }
        return $ret;
    }

    /**
     * @param $filtros
     * @return array
     */
    public function findByFiltros($filtros) {
        $qb=$this->getEntityManager()->createQueryBuilder()
            ->select('adp_pxp')
            ->from('ADPerfilBundle:PerfilXPermiso','adp_pxp')
            ->join('adp_pxp.permiso','adp_prm')
            ->join('adp_pxp.perfil','adp_prf')
            ->where('adp_prf.acceso=:acceso')
            ->setParameter(':acceso','true');
        $rs=Filtro::getQueryBuilderFiltros($qb,$filtros)->getQuery()->getResult();
        $ret=array();
        /** @var PerfilXPermiso $pxp */
        foreach($rs as $pxp) {
            $ret[$pxp->getPermiso()->getId()][$pxp->getPerfil()->getId()]=true;
        }
        return $ret;
    }

}
