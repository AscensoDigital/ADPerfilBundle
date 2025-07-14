<?php

namespace Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExtensionLoadTest extends WebTestCase
{
    public function testADPerfilExtensionLoadsProperly()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        // Parámetros principales
        $this->assertTrue($container->hasParameter('ad_perfil.perfil_class'), 'Falta ad_perfil.perfil_class');
        $this->assertEquals(
            'Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy',
            $container->getParameter('ad_perfil.perfil_class')
        );

        $this->assertTrue($container->hasParameter('ad_perfil.session_name'), 'Falta ad_perfil.session_name');
        $this->assertEquals('perfil_id', $container->getParameter('ad_perfil.session_name'));

        $this->assertTrue($container->hasParameter('ad_perfil.route_redirect'), 'Falta ad_perfil.route_redirect');
        $this->assertEquals('ad_perfil_menu', $container->getParameter('ad_perfil.route_redirect'));

        $this->assertTrue($container->hasParameter('ad_perfil.csv_permisos_path'), 'Falta ad_perfil.csv_permisos_path');
        $this->assertStringContainsString('permisos.csv', $container->getParameter('ad_perfil.csv_permisos_path'));

        // Parámetros de navegación
        $this->assertTrue($container->hasParameter('ad_perfil.navegacion.homepage_route'), 'Falta homepage_route');
        $this->assertEquals('ad_perfil_menu', $container->getParameter('ad_perfil.navegacion.homepage_route'));

        $this->assertTrue($container->hasParameter('ad_perfil.navegacion.homepage_name'), 'Falta homepage_name');
        $this->assertEquals('Inicio', $container->getParameter('ad_perfil.navegacion.homepage_name'));

        $this->assertTrue($container->hasParameter('ad_perfil.navegacion.homepage_icono'), 'Falta homepage_icono');
        $this->assertEquals('fa fa-home', $container->getParameter('ad_perfil.navegacion.homepage_icono'));

        $this->assertTrue($container->hasParameter('ad_perfil.navegacion.homepage_color'), 'Falta homepage_color');
        $this->assertEquals('blanco', $container->getParameter('ad_perfil.navegacion.homepage_color'));

        $this->assertTrue($container->hasParameter('ad_perfil.navegacion.homepage_title'), 'Falta homepage_title');
        $this->assertEquals('Test', $container->getParameter('ad_perfil.navegacion.homepage_title'));

        $this->assertTrue($container->hasParameter('ad_perfil.navegacion.homepage_subtitle'), 'Falta homepage_subtitle');
        $this->assertEquals('', $container->getParameter('ad_perfil.navegacion.homepage_subtitle'));
    }
}
