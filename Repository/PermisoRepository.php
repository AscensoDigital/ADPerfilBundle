<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 26-01-16
 * Time: 18:29
 */

namespace AscensoDigital\PerfilBundle\Repository;


use AscensoDigital\PerfilBundle\Doctrine\FiltroManager;
use Doctrine\ORM\EntityRepository;

class PermisoRepository extends EntityRepository {

    public function findAllOrderNombre()
    {
        return $this->getQueryBuilderOrderNombre()->getQuery()->getResult();
    }

    public function findByFiltro(FiltroManager $filtros) {
        $qb=$this->getEntityManager()->createQueryBuilder()
            ->select('pr')
            ->from('ADPerfilBundle:Permiso','adp_prm')
            ->orderBy('adp_prm.nombre');
        $exclude=array('adp_prf');
        $filtros->addExcludes($exclude);
        return $filtros->getQueryBuilder($qb)->getQuery()->getResult();
    }

    public function getQueryBuilderOrderNombre() {
        return $this->createQueryBuilder('adp_prm')
            ->orderBy('adp_prm.nombre');
    }
}
