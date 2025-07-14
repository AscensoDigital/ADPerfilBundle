<?php

namespace AscensoDigital\PerfilBundle\tests\Entity;

use AscensoDigital\PerfilBundle\Entity\PerfilXPermiso;
use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Model\PerfilInterface;
use PHPUnit\Framework\TestCase;

class PermisoTest extends TestCase
{
    public function testIdInitiallyNull()
    {
        $permiso = new Permiso();
        $this->assertNull($permiso->getId());
    }

    public function testSetAndGetNombre()
    {
        $permiso = new Permiso();
        $permiso->setNombre('GESTION_USUARIOS');
        $this->assertEquals('GESTION_USUARIOS', $permiso->getNombre());
    }

    public function testSetAndGetDescripcion()
    {
        $permiso = new Permiso();
        $permiso->setDescripcion('Gestión de usuarios');
        $this->assertEquals('Gestión de usuarios', $permiso->getDescripcion());
    }

    public function testToStringCombinaNombreYDescripcion()
    {
        $permiso = new Permiso();
        $permiso->setNombre('GESTION_USUARIOS');
        $permiso->setDescripcion('Gestión de usuarios');
        $this->assertEquals('GESTION_USUARIOS - Gestión de usuarios', (string)$permiso);
    }

    public function testAddPerfilGeneraPerfilXPermiso()
    {
        $perfil = $this->createMock(PerfilInterface::class);
        $permiso = new Permiso();
        $permiso->addPerfil($perfil);

        $coleccion = $permiso->getPerfilXPermisos();
        $this->assertCount(1, $coleccion);
        $this->assertSame($perfil, $coleccion[0]->getPerfil());
        $this->assertTrue($coleccion[0]->isAcceso());
    }

    public function testLoadPerfilsAgregaFaltantes()
    {
        $perfilExistente = $this->createConfiguredMock(PerfilInterface::class, ['getId' => 1]);
        $perfilNuevo = $this->createConfiguredMock(PerfilInterface::class, ['getId' => 2]);

        $pxpExistente = new PerfilXPermiso();
        $pxpExistente->setPerfil($perfilExistente);

        $permiso = new Permiso();
        $permiso->addPerfilXPermiso($pxpExistente);
        $permiso->loadPerfils([$perfilExistente, $perfilNuevo]);

        $this->assertCount(2, $permiso->getPerfilXPermisos());
    }

    public function testSetPerfilAccesoCreaONotModifica()
    {
        $perfil = $this->createConfiguredMock(PerfilInterface::class, ['getSlug' => 'admin']);
        $permiso = new Permiso();
        $permiso->setPerfilAcceso($perfil, true);

        $this->assertCount(1, $permiso->getPerfilXPermisos());
        $this->assertSame(true, $permiso->getPerfilXPermisos()[0]->isAcceso());

        // Ejecutar de nuevo con false para modificar
        $permiso->setPerfilAcceso($perfil, false);
        $this->assertSame(false, $permiso->getPerfilXPermisos()[0]->isAcceso());
    }
}
