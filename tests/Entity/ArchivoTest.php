<?php

namespace Tests\AscensoDigital\PerfilBundle\Entity;

use AscensoDigital\PerfilBundle\Entity\Archivo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AscensoDigital\PerfilBundle\Model\UserInterface;

class ArchivoTest extends TestCase
{
    public function testDefaultValues()
    {
        $archivo = new Archivo();
        $this->assertFalse($archivo->getVisible());
    }

    public function testSetAndGetTitulo()
    {
        $archivo = new Archivo();
        $archivo->setTitulo('Documento');
        $this->assertEquals('Documento', $archivo->getTitulo());
    }

    public function testToStringReturnsTitulo()
    {
        $archivo = new Archivo();
        $archivo->setTitulo('Informe');
        $this->assertEquals('Informe', (string)$archivo);
    }

    public function testGetArraySerialize()
    {
        $archivo = new Archivo();
        $archivo->setTitulo('Manual');

        $refClass = new \ReflectionClass($archivo);
        $property = $refClass->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($archivo, 1);

        $array = $archivo->getArraySerialize();

        $this->assertEquals([
            'titulo' => 'Manual',
            'id' => 1,
        ], $array);
    }

    public function testGetExtensionOriginal()
    {
        $archivo = new Archivo();
        $mockFile = $this->createMock(UploadedFile::class);
        $mockFile->method('getClientOriginalName')->willReturn('documento.pdf');
        $archivo->setFile($mockFile);

        $this->assertEquals('.pdf', $archivo->getExtensionOriginal());
    }

    public function testGetNombre()
    {
        $archivo = new Archivo();
        $archivo->setRuta('uploads/archivo123.pdf');
        $this->assertEquals('archivo123.pdf', $archivo->getNombre());
    }

    public function testGetUploadPaths()
    {
        $archivo = new Archivo();
        $archivo->setRuta('archivo.pdf');

        $this->assertEquals('uploads', $archivo->getUploadDir());
        $this->assertStringEndsWith('/web/uploads/archivo.pdf', $archivo->getPath());
        $this->assertStringEndsWith('/web/uploads', $archivo->getUploadRootDir());
    }
}
