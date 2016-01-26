<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 21-01-16
 * Time: 7:21
 */

namespace AscensoDigital\PerfilBundle\Repository;


use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Security\PermisoVoter;
use Doctrine\ORM\EntityRepository;

class MenuRepository extends EntityRepository {

    public function findArrayPermisoByPerfil($perfil_id) {
        $ms=$this->getEntityManager()->createQueryBuilder()
            ->select('adp_m.route')
            ->addSelect('adp_m.slug')
            ->addSelect('IDENTITY(adp_m.permiso) as permiso')
            ->from('ADPerfilBundle:Menu','adp_m')
            ->leftJoin('adp_m.permiso','adp_prm')
            ->leftJoin('adp_prm.perfilXPermisos','adp_pxp')
            ->where('adp_m.permiso IS NULL or (adp_pxp.perfil=:perfil AND adp_pxp.acceso=:permitido)')
            ->setParameter(':perfil',$perfil_id)
            ->setParameter(':permitido','true')
            ->getQuery()->getScalarResult();
        $ret=array();
        foreach ($ms as $m) {
            $libre=is_null($m['permiso']) ? Permiso::LIBRE : Permiso::RESTRICT;
            $ret[PermisoVoter::ROUTE][$libre][]=$m['route'];
            $ret[PermisoVoter::MENU][$libre][]=$m['slug'];
        }
        return $ret;
    }

    public function findByPerfilMenu($perfil_id, $menu_id) {
        $qb=$this->createQueryBuilder('adp_m');
        $qb->leftJoin('adp_m.permiso','adp_prm')
            ->leftJoin('adp_prm.perfilXPermisos','adp_pxp')
            ->where('adp_m.menuSuperior'.(is_null($menu_id) ? ' IS NULL' : '=:menu'))
            ->andWhere('adp_m.permiso IS NULL or (adp_pxp.perfil=:perfil AND adp_pxp.acceso=:permitido)')
            ->setParameter('perfil',$perfil_id)
            ->setParameter(':permitido','true');
        if(!is_null($menu_id)){
            $qb->setParameter('menu',$menu_id);
        }
        return $qb->getQuery()->getResult();
    }
}