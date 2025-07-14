<?php

namespace Tests\AscensoDigital\PerfilBundle\Entity\Dummy;

use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="perfil_dummy")
 */
class PerfilDummy implements PerfilInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO") // ✅ esto sí autogenera el ID
     */
    protected $id;


    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre = 'Perfil Dummy';

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug = 'perfil-dummy';


    public function __toString()
    {
       return $this->getNombre();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getLabel()
    {
        return $this->getNombre();
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }

}
