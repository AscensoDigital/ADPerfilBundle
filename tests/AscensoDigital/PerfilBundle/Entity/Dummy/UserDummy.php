<?php

namespace Tests\AscensoDigital\PerfilBundle\Entity\Dummy;

use AscensoDigital\PerfilBundle\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_dummy")
 */
class UserDummy implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id = 1;

    public function getId()
    {
        return $this->id;
    }
}
