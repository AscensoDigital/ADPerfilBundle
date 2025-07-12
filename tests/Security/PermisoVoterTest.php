<?php

namespace Tests\Security;

use AscensoDigital\PerfilBundle\Entity\Menu;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use AscensoDigital\PerfilBundle\Repository\MenuRepository;
use AscensoDigital\PerfilBundle\Repository\PerfilXPermisoRepository;
use AscensoDigital\PerfilBundle\Security\PermisoVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Doctrine\ORM\EntityManager;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy;

class PermisoVoterTest extends TestCase
{
    public function testVoteOnAttributeForValidPermission()
    {
        $perfil = $this->createMock(\AscensoDigital\PerfilBundle\Model\PerfilInterface::class);
        $perfil->method('getId')->willReturn(1);
        $perfil->method('getNombre')->willReturn('Perfil Dummy');
        $perfil->method('getSlug')->willReturn('perfil-dummy');


        // Mock de Session
        $session = $this->createMock(Session::class);
        $session->expects($this->once())
            ->method('get')
            ->with('perfil_id')
            ->willReturn($perfil->getId());

        // Mock de MenuRepository
        $menuRepo = $this->createMock(MenuRepository::class);
        $menuRepo->expects($this->once())
            ->method('findArrayPermisoByPerfil')
            ->with($perfil->getId())
            ->willReturn([
                PermisoVoter::MENU => [
                    Permiso::RESTRICT => ['inicio']
                ]
            ]);

        // Mock de PerfilXPermiso repository
        $perfilXPermisoRepo = $this->createMock(PerfilXPermisoRepository::class);
        $perfilXPermisoRepo->expects($this->once())
            ->method('findArrayIdByPerfil')
            ->with($perfil->getId())
            ->willReturn(['ad_perfil-mn-mapa-sitio']);

        // Mock de EntityManager con repositorios encadenados
        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback(function ($entity) use ($menuRepo, $perfilXPermisoRepo) {
                switch ($entity) {
                    case 'ADPerfilBundle:Menu':
                    case Menu::class:
                        return $menuRepo;
                    case 'ADPerfilBundle:PerfilXPermiso':
                    case PerfilXPermiso::class:
                        return $perfilXPermisoRepo;
                    default:
                        return $this->createMock(\Doctrine\Common\Persistence\ObjectRepository::class);
                }
            });

        // Crear el Voter ya con mocks armados
        $voter = new PermisoVoter($session, $em, 'perfil_id');

        // Token de autenticación simulado
        $token = new UsernamePasswordToken('test_user', null, 'main', ['ROLE_USER']);

        $result = $voter->vote($token, 'ad_perfil-mn-mapa-sitio', [PermisoVoter::PERMISO]);
        $this->assertSame(VoterInterface::ACCESS_GRANTED, $result, 'El Voter debe retornar ACCESS_GRANTED.');
    }

    public function testVoteOnAttributeForInvalidPermission()
    {
        $perfil = $this->createMock(\AscensoDigital\PerfilBundle\Model\PerfilInterface::class);
        $perfil->method('getId')->willReturn(1);
        $perfil->method('getNombre')->willReturn('Perfil Dummy');
        $perfil->method('getSlug')->willReturn('perfil-dummy');

        $session = $this->createMock(Session::class);
        $session->expects($this->once())
            ->method('get')
            ->with('perfil_id')
            ->willReturn($perfil->getId());

        $menuRepo = $this->createMock(MenuRepository::class);
        $menuRepo->expects($this->once())
            ->method('findArrayPermisoByPerfil')
            ->with($perfil->getId())
            ->willReturn([
                PermisoVoter::MENU => [
                    Permiso::RESTRICT => ['inicio']
                ]
            ]);

        $perfilXPermisoRepo = $this->createMock(PerfilXPermisoRepository::class);
        $perfilXPermisoRepo->expects($this->once())
            ->method('findArrayIdByPerfil')
            ->with($perfil->getId())
            ->willReturn([]); // no tiene permisos

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback(function ($entity) use ($menuRepo, $perfilXPermisoRepo) {
                switch ($entity) {
                    case 'ADPerfilBundle:Menu':
                    case Menu::class:
                        return $menuRepo;
                    case 'ADPerfilBundle:PerfilXPermiso':
                    case PerfilXPermiso::class:
                        return $perfilXPermisoRepo;
                    default:
                        return $this->createMock(\Doctrine\Common\Persistence\ObjectRepository::class);
                }
            });

        $voter = new PermisoVoter($session, $em, 'perfil_id');
        $token = new UsernamePasswordToken('test_user', null, 'main', ['ROLE_USER']);

        $result = $voter->vote($token, 'invalid-permission', [PermisoVoter::PERMISO]);
        $this->assertSame(VoterInterface::ACCESS_DENIED, $result, 'El Voter debe retornar ACCESS_DENIED para permisos inválidos.');
    }
}
