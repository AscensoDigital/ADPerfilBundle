<?php

namespace AscensoDigital\PerfilBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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

        $this->addFiltroSection($rootNode);

        return $treeBuilder;
    }

    private function addFiltroSection(ArrayNodeDefinition $rootNode) {
        $rootNode
            ->fixXmlConfig('filtro')
            ->children()
                ->arrayNode('filtros')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('type')
                            ->defaultValue('Symfony\Bridge\Doctrine\Form\Type\EntityType')
                            ->cannotBeEmpty()
                            ->info('Clase del tipo de form que será el filtro.')
                            ->example('Symfony\Bridge\Doctrine\Form\Type\EntityType (see http://symfony.com/doc/current/reference/forms/types.html)')
                        ->end()
                        ->arrayNode('table_alias')
                            ->cannotBeEmpty()
                            ->info('String con alias del "entity" usado para filtrar')
                            ->example('eg: para entity "Pais" alias "p"')
                        ->end()
                        ->scalarNode('field')
                            ->defaultValue('id')
                            ->cannotBeEmpty()
                            ->info('Nombre del "field" que se filtra de la tabla con "table_alias"')
                            ->example('eg: id')
                        ->end()
                        ->scalarNode('operator')
                            ->defaultValue('eq')
                            ->cannotBeEmpty()
                            ->info('Valor para el operador de comparación')
                            ->example('eg para equal: eq (see http://www.doctrine-project.org/api/orm/2.5/class-Doctrine.ORM.Query.Expr.html)')
                        ->end()
                        ->scalarNode('query_builder_method')
                            ->info('Nombre del metodo usado para generar la opción query_builder')
                            ->example('eg: getQueryBuilderFindAll')
                        ->end()
                        ->booleanNode('perfil')
                            ->defaultFalse()
                            ->info('Determina si el filtro requiere el objeto perfil')
                        ->end()
                        ->booleanNode('user')
                            ->defaultFalse()
                            ->info('Determina si el filtro requiere el objeto user')
                        ->end()
                        ->arrayNode('options')
                            ->defaultValue(array(
                                'required' => false
                            ))
                            ->info('Lista de opciones según "type" del filtro.')
                            ->prototype('variable')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
