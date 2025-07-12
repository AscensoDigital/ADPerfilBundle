<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 04-01-16
 * Time: 18:29
 */

namespace AscensoDigital\PerfilBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use AscensoDigital\PerfilBundle\DependencyInjection\ADPerfilExtension;

class ADPerfilBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {

    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new ADPerfilExtension();
        }

        return $this->extension;
    }
}
