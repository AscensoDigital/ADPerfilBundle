<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 26-01-16
 * Time: 18:29
 */

namespace AscensoDigital\PerfilBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PermisoRepository extends EntityRepository {

    public function getQueryBuilderOrderDescripcion() {
        return $this->createQueryBuilder('adp_prm')
            ->orderBy('adp_prm.descripcion');
    }
}