<?php

namespace Tests\AscensoDigital\PerfilBundle\Controller\Navegacion;

use Tests\TestHelper\FunctionalTestCase;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy;

class LoginRedirectControllerTest extends FunctionalTestCase
{
    public function testRedirectsToRouteInitIfDefined()
    {
        // login con perfil dummy que ya tiene routeInit seteado en fixture
        $this->logInAsAdmin();

        $this->client->request('GET', '/login-redirect');

        $this->assertTrue($this->client->getResponse()->isRedirect(), 'Debe redirigir');
        $this->assertStringEndsWith('/mapa-sitio', $this->client->getResponse()->headers->get('Location'));
    }

    public function testRedirectsToDefaultRouteIfRouteInitIsNull()
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();

        /** @var PerfilDummy $perfil */
        $perfil = $em->getRepository(PerfilDummy::class)->findOneBy([]);
        $perfil->setRouteInit(null);
        $em->flush();

        $this->logInAsAdmin();

        $this->client->request('GET', '/login-redirect');

        $router = $this->client->getContainer()->get('router');
        $expected = $router->generate($this->client->getContainer()->getParameter('ad_perfil.route_redirect'));

        $this->assertTrue($this->client->getResponse()->isRedirect(), 'Debe redirigir por fallback');
        $this->assertSame($expected, $this->client->getResponse()->headers->get('Location'));
    }


    public function testRedirectsToRouteRedirectIfPerfilIdInvalid()
    {
        $this->logInAsAdmin();

        $session = $this->client->getContainer()->get('session');
        $session->set($this->client->getContainer()->getParameter('ad_perfil.session_name'), 999999);
        $session->save();
        $this->client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie($session->getName(), $session->getId()));

        $this->client->request('GET', '/login-redirect');

        $router = $this->client->getContainer()->get('router');
        $expected = $router->generate($this->client->getContainer()->getParameter('ad_perfil.route_redirect'));

        $this->assertTrue($this->client->getResponse()->isRedirect(), 'Debe redirigir por fallback si perfil inválido');
        $this->assertSame($expected, $this->client->getResponse()->headers->get('Location'));
    }

}
