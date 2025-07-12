<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new AscensoDigital\PerfilBundle\ADPerfilBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
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
        return __DIR__ . '/../app/cache/' . $this->environment;
    }

    public function getLogDir()
    {
        return __DIR__ . '/../app/logs';
    }

    public function getKernelDir()
    {
        return __DIR__;
    }

    public function registerRoutes(\Symfony\Component\Routing\RouteCollectionBuilder $routes)
    {
        $routes->import(__DIR__.'/config/routing.php', '/', 'php');
    }

}
