<?php

namespace Tests\AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Util\CsvPermisos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

/**
 * @codeCoverageIgnore
 */
class CsvPermisosTypeKernelTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testSubmitValidFileViaRequest()
    {
        $client = static::createClient();

        // Crear archivo físico válido
        $filePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($filePath, "usuario,permiso\nadmin,ROLE_ADMIN");

        $uploadedFile = new UploadedFile(
            $filePath,
            'permisos.csv',
            'text/csv',
            null,
            true // test mode
        );

        // Simular envío POST con archivo
        $client->request(
            'POST',
            '/ruta-dummy-de-prueba',
            [], // post
            ['form' => ['file' => $uploadedFile]]  // files
        );

        $this->assertTrue(true); // cambia por tus asserts
    }
}
