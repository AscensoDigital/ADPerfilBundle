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
        $archivo->setRuta('archivo123.pdf');
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

    public function testSetAndGetFile()
    {
        $archivo = new Archivo();
        $mockFile = $this->createMock(UploadedFile::class);
        $archivo->setFile($mockFile);
        $this->assertSame($mockFile, $archivo->getFile());
    }

    public function testSetAndGetCreador()
    {
        $archivo = new Archivo();
        $creador = $this->createMock(UserInterface::class);
        $archivo->setCreador($creador);
        $this->assertSame($creador, $archivo->getCreador());
    }

    public function testSetAndGetFechaPublicacion()
    {
        $archivo = new Archivo();
        $fecha = new \DateTime('2020-01-01');
        $archivo->setFechaPublicacion($fecha);
        $this->assertSame($fecha, $archivo->getFechaPublicacion());
    }

    public function testSetAndGetMimeType()
    {
        $archivo = new Archivo();
        $archivo->setMimeType('application/pdf');
        $this->assertEquals('application/pdf', $archivo->getMimeType());
    }

    public function testSetAndGetVisible()
    {
        $archivo = new Archivo();
        $archivo->setVisible(true);
        $this->assertTrue($archivo->getVisible());
    }

    public function testGetExtensionOriginalReturnsEmptyWhenFileIsNull()
    {
        $archivo = new Archivo();
        $archivo->setFile(null);
        $this->assertSame('', $archivo->getExtensionOriginal());
    }

    public function testSaveFileStoresCorrectly()
    {
        $archivo = new Archivo();

        $fsMock = $this->getMockBuilder(Filesystem::class)
            ->onlyMethods(['dumpFile'])
            ->getMock();

        $fsMock->expects($this->once())
            ->method('dumpFile');

        $file = 'contenido de archivo de prueba';
        $directorio = 'pruebas';
        $nombre = 'documento.txt';

        // Sobreescribimos el método internamente para usar el Filesystem mock
        $refFs = new \ReflectionClass(Filesystem::class);
        $prop = $refFs->getProperty('fs');
        $prop->setAccessible(true);
        $prop->setValue($archivo, $fsMock);

        // Ejecutar manualmente ya que no está inyectado: usamos una versión dummy de saveFile
        $slugify = new \Cocur\Slugify\Slugify();
        $slugifiedName = $slugify->slugify('documento') . '.txt';

        $archivo->saveFile($directorio, $nombre, $file, true);
        $this->assertEquals('pruebas' . DIRECTORY_SEPARATOR . $slugifiedName, $archivo->getRuta());
        $this->assertTrue($archivo->getVisible());
    }

    public function testUploadReturnsFalseWhenFileIsNull()
    {
        $archivo = new Archivo();
        $archivo->setFile(null);
        $result = $archivo->upload('carpeta', 'nombre.pdf');
        $this->assertFalse($result);
    }

    public function testUploadSucceedsWithValidFile()
    {
        $mockFile = $this->createMock(UploadedFile::class);
        $mockFile->method('getClientOriginalName')->willReturn('miarchivo.pdf');
        $mockFile->method('getClientMimeType')->willReturn('application/pdf');
        $mockFile->expects($this->once())
            ->method('move');

        $archivo = new Archivo();
        $archivo->setFile($mockFile);
        $result = $archivo->upload('pruebas', 'informe.pdf', true);

        $this->assertNotEmpty($result);
        $this->assertTrue($archivo->getVisible());
        $this->assertEquals('application/pdf', $archivo->getMimeType());
    }
}
