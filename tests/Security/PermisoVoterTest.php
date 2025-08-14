<?php

namespace Tests\Security;

use AscensoDigital\PerfilBundle\Security\PermisoVoter;
use AscensoDigital\PerfilBundle\Entity\Menu;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Repository\MenuRepository;
use AscensoDigital\PerfilBundle\Repository\PerfilXPermisoRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PermisoVoterTest extends TestCase
{
    private function getVoter(array $permisos = [], $perfilId = 1)
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->willReturn($perfilId);

        $repoMenu = $this->createMock(MenuRepository::class);
        $repoMenu->method('findArrayPermisoByPerfil')->willReturn([
            'menu' => $permisos['menu'] ?? [],
        ]);

        $repoPermiso = $this->createMock(PerfilXPermisoRepository::class);
        $repoPermiso->method('findArrayIdByPerfil')->willReturn(
            $permisos['permiso'] ?? []
        );

        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturnMap([
            ['ADPerfilBundle:Menu', $repoMenu],
            ['ADPerfilBundle:PerfilXPermiso', $repoPermiso],
        ]);

        return new PermisoVoter($session, $em, 'perfil');
    }

    private function callSupports($voter, $attribute, $subject)
    {
        $ref = new \ReflectionClass($voter);
        $method = $ref->getMethod('supports');
        $method->setAccessible(true);
        return $method->invoke($voter, $attribute, $subject);
    }

    private function callVote($voter, $attribute, $subject)
    {
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(new \stdClass());
        return $this->invokeVote($voter, $attribute, $subject, $token);
    }

    private function invokeVote($voter, $attribute, $subject, $token)
    {
        $ref = new \ReflectionClass($voter);
        $method = $ref->getMethod('voteOnAttribute');
        $method->setAccessible(true);
        return $method->invoke($voter, $attribute, $subject, $token);
    }

    // === TESTS ===

    public function testSupportsWithInvalidAttribute()
    {
        $voter = $this->getVoter();
        $this->assertFalse($this->callSupports($voter, 'invalid', null));
    }

    public function testSupportsWithInvalidSubject()
    {
        $voter = $this->getVoter();
        $this->assertFalse($this->callSupports($voter, PermisoVoter::MENU, 'string'));
    }

    public function testSupportsWithValidMenuNullSubject()
    {
        $voter = $this->getVoter();
        $this->assertTrue($this->callSupports($voter, PermisoVoter::MENU, null));
    }

    public function testVoteMenuLibrePermitido()
    {
        $menu = new Menu();
        $menu->setSlug('dashboard');
        $voter = $this->getVoter(['menu' => [Permiso::LIBRE => ['dashboard']]]);
        $this->assertTrue($this->callVote($voter, PermisoVoter::MENU, $menu));
    }

    public function testVoteMenuRestrictPermitido()
    {
        $menu = new Menu();
        $menu->setSlug('admin');
        $voter = $this->getVoter(['menu' => [Permiso::RESTRICT => ['admin']]]);
        $this->assertTrue($this->callVote($voter, PermisoVoter::MENU, $menu));
    }

    public function testVoteMenuNoPermitido()
    {
        $menu = new Menu();
        $menu->setSlug('config');
        $voter = $this->getVoter(['menu' => [Permiso::RESTRICT => ['admin']]]);
        $this->assertFalse($this->callVote($voter, PermisoVoter::MENU, $menu));
    }

    public function testVotePermisoPermitido()
    {
        $voter = $this->getVoter(['permiso' => ['PERM_EDIT']]);
        $this->assertTrue($this->callVote($voter, PermisoVoter::PERMISO, 'PERM_EDIT'));
    }

    public function testVotePermisoDenegado()
    {
        $voter = $this->getVoter(['permiso' => ['PERM_VIEW']]);
        $this->assertFalse($this->callVote($voter, PermisoVoter::PERMISO, 'PERM_EDIT'));
    }

    public function testVoteUsuarioAnonimo()
    {
        $voter = $this->getVoter();
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(null);
        $this->assertFalse($this->invokeVote($voter, PermisoVoter::PERMISO, 'PERM_EDIT', $token));
    }

    public function testVotePerfilIdNull()
    {
        $session = $this->createMock(Session::class);
        $session->method('get')->willReturn(null);

        $repoMenu = $this->createMock(MenuRepository::class);
        $repoMenu->method('findArrayPermisoByPerfil')->willReturn([]);

        $repoPermiso = $this->createMock(PerfilXPermisoRepository::class);
        $repoPermiso->method('findArrayIdByPerfil')->willReturn([]);

        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturnMap([
            ['ADPerfilBundle:Menu', $repoMenu],
            ['ADPerfilBundle:PerfilXPermiso', $repoPermiso],
        ]);

        $voter = new PermisoVoter($session, $em, 'perfil');

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(new \stdClass());

        $result = $this->invokeVote($voter, PermisoVoter::PERMISO, 'PERM_EDIT', $token);
        $this->assertFalse($result);
    }

    public function testVoteWithUnknownAttributeThrowsException()
    {
        $voter = $this->getVoter();
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(new \stdClass());

        $this->expectException(\LogicException::class);
        $this->invokeVote($voter, 'desconocido', 'cualquier', $token);
    }

    public function testVoteMenuWithNullSubjectReturnsTrue()
    {
        $voter = $this->getVoter();
        $this->assertTrue($this->callVote($voter, PermisoVoter::MENU, null));
    }

    public function testVoteMenuLibreSlugPermitido()
    {
        $menu = new Menu();
        $menu->setSlug('ayuda');

        $voter = $this->getVoter([
            'menu' => [Permiso::LIBRE => ['ayuda']]
        ]);

        $this->assertTrue($this->callVote($voter, PermisoVoter::MENU, $menu));
    }

    public function testVoteMenuRestrictSlugDenegado()
    {
        $menu = new Menu();
        $menu->setSlug('oculto');

        $voter = $this->getVoter([
            'menu' => [Permiso::RESTRICT => ['admin', 'config']]
        ]);

        $this->assertFalse($this->callVote($voter, PermisoVoter::MENU, $menu));
    }
    public function testVoteMenuLibrePermitidoSinPerfil()
    {
        // Menú público (libre)
        $menu = new Menu();
        $menu->setSlug('publico');

        // Sesión sin perfil activo
        $session = $this->createMock(Session::class);
        $session->method('get')->willReturn(null);

        // El repositorio de Menu devuelve el slug dentro de LIBRE
        $repoMenu = $this->createMock(MenuRepository::class);
        $repoMenu->method('findArrayPermisoByPerfil')
            ->with(null)
            ->willReturn([
                'menu' => [Permiso::LIBRE => ['publico']]
            ]);

        // Repositorio de permisos (no relevante para MENU, pero se mapea igual)
        $repoPermiso = $this->createMock(PerfilXPermisoRepository::class);
        $repoPermiso->method('findArrayIdByPerfil')->willReturn([]);

        // EntityManager con el mapeo de repositorios
        $em = $this->createMock(EntityManager::class);
        $em->method('getRepository')->willReturnMap([
            ['ADPerfilBundle:Menu', $repoMenu],
            ['ADPerfilBundle:PerfilXPermiso', $repoPermiso],
        ]);

        $voter = new PermisoVoter($session, $em, 'perfil');

        // Debe permitir acceso porque el slug está marcado como LIBRE,
        // independiente de que no exista perfil en sesión.
        $this->assertTrue($this->callVote($voter, PermisoVoter::MENU, $menu));
    }

}
