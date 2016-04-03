<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 28-09-15
 * Time: 15:47
 */

namespace AscensoDigital\PerfilBundle\Util;


class Dia
{
    private $id;
    private $nombre;

    public function __construct($id,$nombre)
    {
        $this->id=$id;
        $this->nombre=$nombre;
    }

    public function __toString()
    {
       return $this->getNombre();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
}
