<?php

namespace AscensoDigital\PerfilBundle\Model;

interface PerfilInterface
{
    public function getId();
    public function getNombre();
    public function getSlug();
    public function getLabel();

    /**
     * Retorna la ruta inicial asociada al perfil, o null si no hay una definida.
     * @return string|null
     */
    public function getRouteInit();
}
