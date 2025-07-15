<?php

namespace Tests\AscensoDigital\PerfilBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestHelper\FunctionalTestCase;

class NavegacionControllerTest extends FunctionalTestCase
{
    public function testMapaSitioQueryCount()
    {
        $context = $this->logInAsAdmin(); // ahora disponible $context['user'], $context['perfil']

        $this->client->request('GET', '/mapa-sitio');

        if ($this->client->getResponse()->getStatusCode() !== 200) {
            $errorFile = __DIR__ . '/../app/logs/error_adperfil.html';
            file_put_contents($errorFile, $this->client->getResponse()->getContent());
            if (getenv('DEBUG_TESTS')) {
                echo "\n>> ERROR " . $this->client->getResponse()->getStatusCode() . ": HTML guardado en $errorFile\n";
            }
        }

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testMapaSitioContenidoValido()
    {
        $this->logInAsAdmin();
        $crawler = $this->client->request('GET', '/mapa-sitio');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(), 'Respuesta HTTP no fue 200');

        // Verifica que exista al menos un <li>
        $liCount = $crawler->filter('li')->count();
        $this->assertGreaterThan(0, $liCount, 'No se encontraron elementos <li> en el mapa del sitio');

        // Verifica que exista el nombre del menú cargado por fixtures
        $this->assertStringContainsString('Mapa Del Sitio', $this->client->getResponse()->getContent(), 'No se encontró el texto del menú principal');
    }
}

