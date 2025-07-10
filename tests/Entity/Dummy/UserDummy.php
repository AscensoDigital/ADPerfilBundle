<?php

namespace Tests\AscensoDigital\PerfilBundle\Entity\Dummy;

use AscensoDigital\PerfilBundle\Model\UserInterface;

class UserDummy implements UserInterface
{
    public function getId()
    {
        return 1;
    }
}
