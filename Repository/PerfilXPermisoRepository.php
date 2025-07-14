<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 21-01-16
 * Time: 7:44
 */

namespace AscensoDigital\PerfilBundle\Repository;


use AscensoDigital\PerfilBundle\Doctrine\FiltroManager;
use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;

class PerfilXPermisoRepository extends EntityRepository {

    public function findAllArray() {
        $pxps = $this->getEntityManager()->createQueryBuilder()
            ->select('adp_prm.nombre')
            ->addSelect('adp_prf.slug')
            ->addSelect('adp_pxp.acceso')
            ->from('ADPerfilBundle:PerfilXPermiso','adp_pxp')
            ->join('adp_pxp.permiso','adp_prm')
            ->join('adp_pxp.perfil', 'adp_prf')
            ->getQuery()->getScalarResult();
        $ret=array();
        foreach ($pxps as $pxp) {
            $ret[$pxp['nombre']][$pxp['slug']]=$pxp['acceso'];
        }
        return $ret;
    }

    public function findArrayIdByPerfil($perfil_id) {
        $prms=$this->getEntityManager()->createQueryBuilder()
            ->select('adp_prm.nombre')
            ->from('ADPerfilBundle:PerfilXPermiso','adp_pxp')
            ->join('adp_pxp.permiso','adp_prm')
            ->where('adp_pxp.perfil=:perfil and adp_pxp.acceso=:acceso')
            ->setParameter(':perfil',$perfil_id)
            ->setParameter(':acceso',true, \PDO::PARAM_BOOL)
            ->getQuery()->getScalarResult();
        $ret=array();
        foreach ($prms as $prm) {
            $ret[]=$prm['nombre'];
        }
        return $ret;
    }

    public function findOneByPermisoNombrePerfilSlug($permisoNombre, $perfilSlug) {
        try {
            return $this->createQueryBuilder('adp_pxp')
                ->join('adp_pxp.permiso', 'adp_prm', Join::WITH, 'adp_prm.nombre=:permisoNombre')
                ->join('adp_pxp.perfil', 'adp_prf', Join::WITH, 'adp_prf.slug=:perfilSlug')
                ->setParameter(':perfilSlug', $perfilSlug)
                ->setParameter(':permisoNombre', $permisoNombre)
                ->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return false;
        }
    }

    /**
     * @param $filtros
     * @return array
     */
    public function findByFiltros(FiltroManager $filtros) {
        $qb=$this->getEntityManager()->createQueryBuilder()
            ->select('adp_pxp')
            ->from('ADPerfilBundle:PerfilXPermiso','adp_pxp')
            ->join('adp_pxp.permiso','adp_prm')
            ->join('adp_pxp.perfil',$filtros->getPerfilTableAlias())
            ->where('adp_pxp.acceso=:acceso')
            ->setParameter(':acceso',true, \PDO::PARAM_BOOL);
        $rs=$filtros->getQueryBuilder($qb)->getQuery()->getResult();
        $ret=array();
        /** @var PerfilXPermiso $pxp */
        foreach($rs as $pxp) {
            $ret[$pxp->getPermiso()->getId()][$pxp->getPerfil()->getId()]=true;
        }
        return $ret;
    }

}
