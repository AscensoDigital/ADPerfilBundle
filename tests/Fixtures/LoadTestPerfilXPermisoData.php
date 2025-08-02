<?php

namespace Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager; // ✅ correcto para Symfony 2.x
use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy;

class LoadTestPerfilXPermisoData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var PerfilDummy $perfil */
        $perfil = $this->getReference(LoadTestPerfilData::TEST_PERFIL_REFERENCE);

        /** @var Permiso|null $permiso */
        $permiso = $manager->getRepository(Permiso::class)->findOneBy(['nombre' => 'ad_perfil-mn-mapa-sitio']);
        if (!$permiso) {
            throw new \RuntimeException('❌ Permiso "ad_perfil-mn-mapa-sitio" no encontrado. Asegúrate de cargar LoadPermisoData antes.');
        }

        $pxp = new PerfilXPermiso();
        $pxp->setPerfil($perfil);
        $pxp->setPermiso($permiso);
        $pxp->setAcceso(true);
        $manager->persist($pxp);

        /** @var Permiso|null $permisoCrear */
        $permisoCrear = $manager->getRepository(Permiso::class)->findOneBy(['nombre' => 'ad_perfil-menu-new']);
        if (!$permisoCrear) {
            throw new \RuntimeException('❌ Permiso "ad_perfil-menu-new" no encontrado. Asegúrate de cargar LoadPermisoData antes.');
        }

        $pxpCrear = new PerfilXPermiso();
        $pxpCrear->setPerfil($perfil);
        $pxpCrear->setPermiso($permisoCrear);
        $pxpCrear->setAcceso(true);
        $manager->persist($pxpCrear);

        $permisos = [
            'per-config-index',
            'per-per-new',
            'per-per-list',
            'per-per-load',
            'per-per-edit',
            'per-menu-new',
            'per-rep-list',
            'per-rep-new',
            'per-rep-edit',
            'per-rep-load',
        ];

        foreach ($permisos as $ref) {
            /** @var Permiso $permiso */
            $permiso = $this->getReference($ref);
            $pxp = new PerfilXPermiso();
            $pxp->setPerfil($perfil);
            $pxp->setPermiso($permiso);
            $pxp->setAcceso(true);
            $manager->persist($pxp);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LoadTestPerfilData::class,
        ];
    }
}
