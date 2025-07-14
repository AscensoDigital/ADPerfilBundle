<?php

namespace Tests\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\UserDummy;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadUserDummyData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $user = new UserDummy();
        $manager->persist($user);
        $manager->flush();

        // opcional si necesitas referencia por nombre
        $this->addReference('user-dummy', $user);
    }
}
