<?php

use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use AscensoDigital\PerfilBundle\ADPerfilBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new DoctrineBundle(),
            new ADPerfilBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if ($this->getEnvironment() === 'test') {
            $loader->load(__DIR__.'/config/config_test.yml');
        } else {
            $loader->load(__DIR__.'/config/config.yml');
        }
    }


    public function getCacheDir()
    {
        return sys_get_temp_dir().'/symfony_app/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/symfony_app/logs';
    }
}
