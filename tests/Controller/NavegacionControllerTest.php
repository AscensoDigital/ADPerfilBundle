<?php

namespace Tests\AscensoDigital\PerfilBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\UserDummy;
use Tests\TestHelper\FunctionalTestCase;

class NavegacionControllerTest extends FunctionalTestCase
{
    public function testMapaSitioQueryCount()
    {
        $client = static::createClient();
        $client->enableProfiler();

        $container = $client->getContainer();
        $session = $container->get('session');
        $firewallName = 'main';

        $usuario = $container->get('doctrine')->getRepository(UserDummy::class)->findOneBy([]);
        $roles = ['ROLE_ADMIN'];
        $token = new UsernamePasswordToken($usuario, null, $firewallName, $roles);

        // ⚠️ IMPORTANTE: setear el token directo en el token storage
        $container->get('security.token_storage')->setToken($token);

        // Guarda el token serializado en sesión (sin pasar por firewall)
        $session->set('_security_' . $firewallName, serialize($token));

        // Guarda también el ID de perfil simulado
        $sessionName = $container->getParameter('ad_perfil.session_name');
        $session->set($sessionName, 1); // Dummy ID
        $session->save();

        // Setea cookie para vincular la sesión
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
        if (getenv('DEBUG_TESTS')) {
            echo "\n🧪 ID de perfil activo en sesión: " . $session->get($sessionName);
        }
        // Request real
        $client->request('GET', '/mapa-sitio');

        if ($client->getResponse()->getStatusCode() !== 200) {
            $errorFile = __DIR__ . '/../app/logs/error_adperfil.html';
            file_put_contents($errorFile, $client->getResponse()->getContent());
            if (getenv('DEBUG_TESTS')) {
                echo "\n>> ERROR " . $client->getResponse()->getStatusCode() . ": HTML guardado en $errorFile\n";
            }
        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}

