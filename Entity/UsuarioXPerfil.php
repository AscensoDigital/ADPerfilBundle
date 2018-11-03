<?php

namespace AscensoDigital\PerfilBundle\Entity;

use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use AscensoDigital\PerfilBundle\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;


abstract class UsuarioXPerfil
{
    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="AscensoDigital\PerfilBundle\Model\UserInterface", inversedBy="usuarioXPerfils")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $usuario;

    /**
     * @var PerfilInterface
     *
     * @ORM\ManyToOne(targetEntity="AscensoDigital\PerfilBundle\Model\PerfilInterface", inversedBy="usuarioXPerfils")
     * @ORM\JoinColumn(onDelete="cascade")
     */
    protected $perfil;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="AscensoDigital\PerfilBundle\Model\UserInterface")
     */
    protected $modificador;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $active;


    public function getUsuario(): ?UserInterface
    {
        return $this->usuario;
    }

    public function setUsuario(?UserInterface $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getPerfil(): ?PerfilInterface
    {
        return $this->perfil;
    }

    public function setPerfil(?PerfilInterface $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }

    public function getModificador(): ?UserInterface
    {
        return $this->modificador;
    }

    public function setModificador(?UserInterface $modificador): self
    {
        $this->modificador = $modificador;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
