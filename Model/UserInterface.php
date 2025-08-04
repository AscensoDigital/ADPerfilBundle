<?php

namespace AscensoDigital\PerfilBundle\Model;

interface UserInterface extends \FOS\UserBundle\Model\UserInterface
{
    /**
     * @return mixed
     */
    public function getPerfils();

    /**
     * Retorna el objeto que implementa PerfilInterface que tiene el perfilId, o false si no esta el perfil del Id solicitado.
     * @param int $perfilId: id del perfil a buscar
     * @return PerfilInterface|false
     */
    public function getPerfil($perfilId);
}

