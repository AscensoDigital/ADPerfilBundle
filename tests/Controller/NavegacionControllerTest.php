<?php

namespace Tests\AscensoDigital\PerfilBundle\Controller;

use Tests\TestHelper\FunctionalTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NavegacionControllerTest extends FunctionalTestCase
{
public function testMapaSitioQueryCount()
{
$client = static::createClient();
$client->enableProfiler();

/** @var SessionInterface $session */
$session = self::$kernel->getContainer()->get('session');
$session->set('perfil_id', 1);
$session->save();

$client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

$crawler = $client->request('GET', '/mapa-sitio');

$this->assertEquals(200, $client->getResponse()->getStatusCode());

$profile = $client->getProfile();
$queryCount = count($profile->getCollector('db')->getQueries());

echo "\nNúmero de consultas: $queryCount\n";

$this->assertLessThan(10, $queryCount, 'Demasiadas consultas');
}
}
