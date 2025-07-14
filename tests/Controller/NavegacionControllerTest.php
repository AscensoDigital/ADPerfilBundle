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


}

