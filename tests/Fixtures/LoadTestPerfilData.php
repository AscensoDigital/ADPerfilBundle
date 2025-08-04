<?php

namespace Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager; // ✅ correcto para Symfony 2.x
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy;

class LoadTestPerfilData extends AbstractFixture
{
    public const TEST_PERFIL_REFERENCE = 'test-perfil';

    public function load(ObjectManager $manager)
    {
        $perfil = new PerfilDummy();
        $perfil->setRouteInit('ad_perfil_mapa_sitio');
        $manager->persist($perfil);
        $manager->flush();

        // Guardamos referencia para otros fixtures (como PerfilXPermiso)
        $this->addReference(self::TEST_PERFIL_REFERENCE, $perfil);
    }
}
