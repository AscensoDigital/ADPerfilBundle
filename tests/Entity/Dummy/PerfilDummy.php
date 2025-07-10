<?php

namespace Tests\AscensoDigital\PerfilBundle\Entity\Dummy;

use AscensoDigital\PerfilBundle\Entity\Perfil;

class PerfilDummy extends Perfil
{

    public function getNombre()
    {
        return 'Perfil Dummy';
    }
}
