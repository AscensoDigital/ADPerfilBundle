<?php

namespace AscensoDigital\PerfilBundle\Model;

interface UserInterface extends \FOS\UserBundle\Model\UserInterface
{
    /**
     * @return mixed
     */
    public function getPerfils();
}

