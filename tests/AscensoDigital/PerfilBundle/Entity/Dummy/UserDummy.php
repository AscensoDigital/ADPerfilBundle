<?php

namespace Tests\AscensoDigital\PerfilBundle\Entity\Dummy;

use AscensoDigital\PerfilBundle\Model\UserInterface as ADUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_dummy")
 */
class UserDummy implements ADUserInterface, UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id = 1;

    public function __toString()
    {
       return $this->getUsername();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return 'admin';
    }

    public function eraseCredentials()
    {
    }
}
