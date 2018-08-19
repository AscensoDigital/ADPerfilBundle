<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 28-01-16
 * Time: 3:17
 */

namespace AscensoDigital\PerfilBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ColorRepository extends EntityRepository {

    public function getQueryBuilderOrderNombre() {
        return $this->createQueryBuilder('adp_c')
            ->orderBy('adp_c.nombre');
    }

}
