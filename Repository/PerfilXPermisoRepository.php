<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 21-01-16
 * Time: 7:44
 */

namespace AscensoDigital\PerfilBundle\Repository;


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

}
