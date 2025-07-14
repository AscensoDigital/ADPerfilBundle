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

    public function countItems($menu_id) {
        $qb=$this->getEntityManager()->createQueryBuilder();
        $qb->select('COUNT(adp_m.id)')
            ->from('ADPerfilBundle:Menu','adp_m');
        if(is_null($menu_id)){
            $qb->where('adp_m.menuSuperior IS NULL');
        }
        else {
            $qb->where('adp_m.menuSuperior=:menu')
                ->setParameter(':menu',$menu_id);
        }
        $qb->andWhere('adp_m.visible=:visible')
            ->setParameter(':visible',true, \PDO::PARAM_BOOL);
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findArrayPermisoByPerfil($perfil_id) {
        $qb=$this->getEntityManager()->createQueryBuilder()
            ->select('adp_m.slug')
            ->addSelect('IDENTITY(adp_m.permiso) as permiso')
            ->from('ADPerfilBundle:Menu','adp_m')
            ->leftJoin('adp_m.permiso','adp_prm')
            ->leftJoin('adp_prm.perfilXPermisos','adp_pxp')
            ->where('adp_m.permiso IS NULL or (adp_pxp.perfil=:perfil AND adp_pxp.acceso=:permitido)')
            ->setParameter(':perfil',$perfil_id)
            ->setParameter(':permitido', true, \PDO::PARAM_BOOL);
        $ms=$qb->getQuery()->getScalarResult();
        $ret=array();
        foreach ($ms as $m) {
            $libre=is_null($m['permiso']) ? Permiso::LIBRE : Permiso::RESTRICT;
            $ret[PermisoVoter::MENU][$libre][]=$m['slug'];
        }
        return $ret;
    }

    public function findByPerfilMenu($perfil_id, $menu_id, $visible=true) {
        $qb=$this->createQueryBuilder('adp_m');
        $qb->addSelect('adp_ms')
            ->addSelect('adp_c')
            ->leftJoin('adp_m.color','adp_c')
            ->leftJoin('adp_m.menuSuperior','adp_ms')
            ->leftJoin('adp_m.permiso','adp_prm')
            ->leftJoin('adp_prm.perfilXPermisos','adp_pxp')
            ->where('adp_m.menuSuperior'.((is_null($menu_id) || $menu_id==0) ? ' IS NULL' : '=:menu'))
            ->andWhere('adp_m.permiso IS NULL or (adp_pxp.perfil=:perfil AND adp_pxp.acceso=:permitido)')
            ->andWhere('adp_m.visible=:visible')
            ->orderBy('adp_m.orden')
            ->setParameter('perfil',$perfil_id)
            ->setParameter(':permitido',true, \PDO::PARAM_BOOL)
            ->setParameter(':visible', $visible, \PDO::PARAM_BOOL);
        if(!is_null($menu_id) && $menu_id>0){
            $qb->setParameter('menu',$menu_id);
        }
        return $qb->getQuery()->getResult();
    }

    public function findOneByRoute($route) {
        return $this->createQueryBuilder('adp_m')
            ->addSelect('adp_ms')
            ->addSelect('adp_c')
            ->leftJoin('adp_m.color','adp_c')
            ->leftJoin('adp_m.menuSuperior','adp_ms')
            ->where('adp_m.route=:route')
            ->setParameter(':route',$route)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    public function getQueryBuilderOrderNombre() {
        return $this->createQueryBuilder('adp_m')
            ->addSelect('adp_ms')
            ->leftJoin('adp_m.menuSuperior','adp_ms')
            ->where('adp_m.visible=:visible')
            ->orderBy('adp_ms.nombre')
            ->addOrderBy('adp_m.nombre')
            ->setParameter(':visible',true, \PDO::PARAM_BOOL);
    }
}
