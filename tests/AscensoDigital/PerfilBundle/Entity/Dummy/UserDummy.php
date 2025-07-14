<?php

namespace Tests\AscensoDigital\PerfilBundle\Entity\Dummy;

use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use AscensoDigital\PerfilBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_dummy")
 */
class UserDummy extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy")
     * @ORM\JoinTable(name="user_dummy_perfil_dummy")
     */
    protected $perfils;

    public function __construct()
    {
        parent::__construct();
        $this->perfils = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPerfils(): Collection
    {
        return $this->perfils;
    }

    public function addPerfil(PerfilInterface $perfil): self
    {
        if (!$this->perfils->contains($perfil)) {
            $this->perfils[] = $perfil;
        }

        return $this;
    }

    public function __toString(): string
    {
        return 'UserDummy#' . $this->id;
    }
}
