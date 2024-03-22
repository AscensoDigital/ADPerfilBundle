<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 26-01-16
 * Time: 18:29
 */

namespace AscensoDigital\PerfilBundle\Repository;


use AscensoDigital\PerfilBundle\Doctrine\FiltroManager;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class PermisoRepository extends EntityRepository {

    public function findAllOrderNombre()
    {
        return $this->getQueryBuilderOrderNombre()->getQuery()->getResult();
    }

    public function findArrayAllByNombreJoinPerfils() {
        $rs = $this->getQueryBuilderOrderNombre()
            ->addSelect('adp_pxp')
            ->leftJoin('adp_prm.perfilXPermisos', 'adp_pxp')
            ->getQuery()
            ->getResult();
        $ret = [];
        /** @var Permiso $r */
        foreach ($rs as $r) {
            $ret[$r->getNombre()] = $r;
        }
        return $ret;
    }

    public function findByFiltro(FiltroManager $filtros) {
        $qb=$this->getEntityManager()->createQueryBuilder()
            ->select('adp_prm')
            ->from('ADPerfilBundle:Permiso','adp_prm')
            ->orderBy('adp_prm.nombre');
        $exclude=array($filtros->getPerfilField());
        return $filtros->getQueryBuilder($qb,$exclude)->getQuery()->getResult();
    }

    public function findByNombreParcial($nombre) {
        return $this->createQueryBuilder('adp_prm')
            ->where('adp_prm.nombre LIKE :nombre')
            ->setParameter(':nombre',$nombre.'%')
            ->getQuery()->getResult();
    }

    public function findOneByNombreJoinPerfils($nombre) {
        try {
            return $this->getQueryBuilderOrderNombre()
                ->addSelect('adp_pxp')
                ->leftJoin('adp_prm.perfilXPermisos', 'adp_pxp')
                ->where('adp_prm.nombre=:nombre')
                ->setParameter(':nombre', $nombre)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    public function getQueryBuilderOrderNombre() {
        return $this->createQueryBuilder('adp_prm')
            ->orderBy('adp_prm.nombre');
    }
}
