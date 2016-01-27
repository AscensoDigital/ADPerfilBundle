<?php

namespace AscensoDigital\PerfilBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ad_perfil');

        $rootNode
            ->children()
                ->scalarNode('perfil_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('route_redirect')->defaultValue('homepage')->end()
                ->scalarNode('session_name')->defaultValue('ad_perfil.perfil_id')->end()
                ->scalarNode('perfil_manager')->defaultValue('ad_perfil.perfil_manager')->end()
                ->arrayNode('navegacion')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('homepage_title')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('homepage_subtitle')->defaultValue('')->end()
                        ->scalarNode('homepage_route')->defaultValue('homepage')->cannotBeEmpty()->end()
                        ->scalarNode('homepage_name')->defaultValue('Inicio')->cannotBeEmpty()->end()
                        ->scalarNode('homepage_icono')->defaultValue('fa fa-home')->cannotBeEmpty()->end()
                        ->scalarNode('homepage_color')->defaultValue('blanco')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
