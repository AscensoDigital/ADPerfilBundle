<?php

namespace Tests\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\UserDummy;

class LoadTestPerfilXUserData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var UserDummy $user */
        $user = $this->getReference('user-dummy');
        /** @var PerfilDummy $perfil */
        $perfil = $this->getReference('test-perfil');

        $user->addPerfil($perfil);
        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            \Tests\Fixtures\LoadTestUserDummyData::class,
            \Tests\Fixtures\LoadTestPerfilData::class,
        ];
    }
}
