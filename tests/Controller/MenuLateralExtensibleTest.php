<?php

namespace Tests\AscensoDigital\PerfilBundle\Controller;

use Tests\TestHelper\FunctionalTestCase;

class MenuLateralExtensibleTest extends FunctionalTestCase
{
    public function testMenuLateralRenderizadoParaUsuarioAutenticado()
    {
        $this->logInAsAdmin();

        $crawler = $this->client->request('GET', '/mapa-sitio');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), 'La página se debe cargar correctamente.');

        $this->assertCount(1, $crawler->filter('ul.menu-lateral'), 'Debe existir el menú lateral.');

        $this->assertCount(1, $crawler->filter('.toggle-menu'), 'Debe haber un botón para colapsar el menú.');

        $this->assertGreaterThan(0, $crawler->filter('li.activo')->count(), 'Debe haber un menú marcado como activo.');

        // Validar que el menú activo corresponde al de 'Mapa del Sitio'
        $activeLink = $crawler->filter('li.activo a')->first();

        $this->assertStringContainsString('/mapa-sitio', $activeLink->attr('href'), 'El menú activo debe redirigir a /mapa-sitio');
        $this->assertStringContainsString('Mapa del Sitio', $activeLink->text(), 'El texto del menú activo debe ser Mapa del Sitio');

        // Validar que contiene el ícono correcto
        $iconDiv = $activeLink->filter('span.icono');
        $this->assertGreaterThan(0, $iconDiv->count(), 'Debe haber un span con clase icono en el menú activo');

        $icon = $iconDiv->filter('i.fa');
        $this->assertGreaterThan(0, $icon->count(), 'Debe haber un <i> con clase .fa dentro del icono');

        $iconClasses = $icon->attr('class');
        $this->assertStringContainsString('fa-sitemap', $iconClasses, 'El ícono debe contener fa-sitemap');

        $iconoClass = $iconDiv->attr('class');
        $this->assertStringContainsString('icono-verde', $iconoClass, 'El menú activo debe tener la clase icono-verde');
    }

    public function testMenuLateralNoVisibleParaAnonimo()
    {
        $crawler = $this->client->request('GET', '/mapa-sitio');
        $statusCode = $this->client->getResponse()->getStatusCode();
        $this->assertNotEquals(200, $statusCode, 'Un usuario anónimo no debe poder acceder directamente.');
    }

    public function testVistaProtegidaSinMenuAsociadoNoRompeRender()
    {
        $this->logInAsAdmin();
        $crawler = $this->client->request('GET', '/index');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), 'La página protegida sin menú asociado debe cargar correctamente.');

        $this->assertCount(1, $crawler->filter('ul.menu-lateral'), 'El menú lateral debe estar presente.');
        $this->assertCount(0, $crawler->filter('li.activo'), 'Ningún menú debe estar marcado como activo en rutas no asociadas a menú.');
    }

    public function testTodosLosMenusTienenHrefValido()
    {
        $this->logInAsAdmin();
        $crawler = $this->client->request('GET', '/mapa-sitio');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), 'La vista debe cargar correctamente.');

        $links = $crawler->filter('ul.menu-lateral a');
        $this->assertGreaterThan(0, $links->count(), 'Debe haber al menos un enlace en el menú lateral.');

        foreach ($links as $a) {
            $href = $a->getAttribute('href');
            $this->assertNotEmpty($href, 'El enlace del menú no debe estar vacío.');
            $this->assertNotEquals('#', $href, 'El enlace del menú no debe ser "#" a menos que sea necesario.');
            $this->assertStringStartsWith('/', $href, 'El href del enlace debe empezar con "/" o ser ruta válida.');
        }
    }

    public function testSubmenusRenderizadosCorrectamente()
    {
        $this->logInAsAdmin();
        $crawler = $this->client->request('GET', '/mapa-sitio');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), 'La vista debe cargar correctamente.');

        // Verifica que hay al menos un submenú renderizado
        $submenuItems = $crawler->filter('ul.menu-lateral ul.submenu li.submenu-item');
        $this->assertGreaterThan(0, $submenuItems->count(), 'Debe haber al menos un submenú renderizado dentro de la estructura.');

        foreach ($submenuItems as $item) {
            $a = $item->getElementsByTagName('a')->item(0);
            $this->assertNotNull($a, 'Cada submenú debe contener un enlace <a>.');

            $href = $a->getAttribute('href');
            $this->assertNotEmpty($href, 'El href del submenú no debe estar vacío.');
            $this->assertNotEquals('#', $href, 'El href del submenú no debe ser "#" salvo que sea intencional.');
            $this->assertStringStartsWith('/', $href, 'El href del submenú debe comenzar con "/".');

            $text = trim($a->textContent);
            $this->assertNotEmpty($text, 'El texto del submenú no debe estar vacío.');
        }
    }

}
