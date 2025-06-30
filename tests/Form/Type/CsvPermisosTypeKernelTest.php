<?php

namespace Tests\AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Util\CsvPermisos;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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
            UPLOAD_ERR_OK,
            true
        );

        // Crear objeto directamente
        $csvPermisos = new CsvPermisos();

        // Obtener el contenedor de servicios y el form factory
        $container = self::$kernel->getContainer();
        $formFactory = $container->get('form.factory');

        $form = $formFactory->create('AscensoDigital\PerfilBundle\Form\Type\CsvPermisosType', $csvPermisos);
        $form->handleRequest(new Request([], [], [], [], ['form[file]' => $uploadedFile]));

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
        $this->assertInstanceOf(UploadedFile::class, $csvPermisos->getFile());
    }
}
