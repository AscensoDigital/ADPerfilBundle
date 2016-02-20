<?php

namespace AscensoDigital\PerfilBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 20-01-16
 * Time: 19:18
 */
class ADPerfilExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $container->setParameter('ad_perfil.config', $config);

        $container->setParameter('ad_perfil.perfil_class',$config['perfil_class']);
        
        $container->setParameter('ad_perfil.session_name',$config['session_name']);
        $container->setParameter('ad_perfil.route_redirect',$config['route_redirect']);

        $container->setParameter('ad_perfil.navegacion.homepage_route',$config['navegacion']['homepage_route']);
        $container->setParameter('ad_perfil.navegacion.homepage_name',$config['navegacion']['homepage_name']);
        $container->setParameter('ad_perfil.navegacion.homepage_icono',$config['navegacion']['homepage_icono']);
        $container->setParameter('ad_perfil.navegacion.homepage_color',$config['navegacion']['homepage_color']);
        $container->setParameter('ad_perfil.navegacion.homepage_title',$config['navegacion']['homepage_title']);
        $container->setParameter('ad_perfil.navegacion.homepage_subtitle',$config['navegacion']['homepage_subtitle']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'ad_perfil';
    }
}
