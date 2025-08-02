<?php

namespace Tests\AscensoDigital\PerfilBundle\Controller;

use Tests\TestHelper\FunctionalTestCase;

class MenuLateralExtensibleTest extends FunctionalTestCase
{
    public function testMenuLateralRenderizadoParaUsuarioAutenticado()
    {
        $this->logInAsAdmin(); // ya usa $this->client internamente

        $crawler = $this->client->request('GET', '/mapa-sitio');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), 'La página se debe cargar correctamente.');

        $this->assertCount(1, $crawler->filter('aside.menu-lateral'), 'Debe existir el menú lateral.');

        $this->assertCount(1, $crawler->filter('.toggle-menu'), 'Debe haber un botón para colapsar el menú.');

        $this->assertGreaterThan(0, $crawler->filter('li.activo')->count(), 'Debe haber un menú marcado como activo.');

        // Validar que el menú activo corresponde al de 'Mapa del Sitio'
        $activeLink = $crawler->filter('li.activo a')->first();

        $this->assertStringContainsString('/mapa-sitio', $activeLink->attr('href'), 'El menú activo debe redirigir a /mapa-sitio');
        $this->assertStringContainsString('Mapa del Sitio', $activeLink->text(), 'El texto del menú activo debe ser Mapa del Sitio');

        // Validar que contiene el ícono correcto
        $iconDiv = $activeLink->filter('div.icono');
        $this->assertGreaterThan(0, $iconDiv->count(), 'Debe haber un div con clase icono en el menú activo');

        $icon = $iconDiv->filter('i.fa');
        $this->assertGreaterThan(0, $icon->count(), 'Debe haber un <i> con clase .fa dentro del icono');

        $iconClasses = $icon->attr('class');
        $this->assertStringContainsString('fa-sitemap', $iconClasses, 'El ícono debe contener fa-sitemap');

        // Validar que tiene la clase de color correspondiente
        $iconoClass = $iconDiv->attr('class');
        $this->assertStringContainsString('icono-verde', $iconoClass, 'El menú activo debe tener la clase icono-verde');
    }


    public function testMenuLateralNoVisibleParaAnonimo()
    {
        // NO llamamos a logInAsAdmin(), por lo que está anónimo
        $crawler = $this->client->request('GET', '/mapa-sitio');

        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertNotEquals(200, $statusCode, 'Un usuario anónimo no debe poder acceder directamente.');
    }

    public function testVistaProtegidaSinMenuAsociadoNoRompeRender()
    {
        $this->logInAsAdmin();

        $crawler = $this->client->request('GET', '/index');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), 'La página protegida sin menú asociado debe cargar correctamente.');

        // El menú lateral debe renderizarse igual
        $this->assertCount(1, $crawler->filter('aside.menu-lateral'), 'El menú lateral debe estar presente.');

        // No debe haber ningún menú marcado como activo
        $this->assertCount(0, $crawler->filter('li.activo'), 'Ningún menú debe estar marcado como activo en rutas no asociadas a menú.');
    }


}
