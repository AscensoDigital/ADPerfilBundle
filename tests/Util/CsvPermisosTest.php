<?php

namespace Tests\AscensoDigital\PerfilBundle\Util;

use AscensoDigital\PerfilBundle\Util\CsvPermisos;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\Test\ValidatorTestCase;

class CsvPermisosTest extends ValidatorTestCase
{
    public function testSetAndGetFileWithValidCsvFile()
    {
        // Creamos un archivo CSV simulado válido
        $tempPath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tempPath, "usuario,permiso\nuser1,ROLE_ADMIN");
        $file = new UploadedFile($tempPath, 'permisos.csv', 'text/csv', null, true);

        $csvPermisos = new CsvPermisos();
        $csvPermisos->setFile($file);

        $this->assertSame($file, $csvPermisos->getFile());
    }

    public function testSetFileToNull()
    {
        $csvPermisos = new CsvPermisos();
        $csvPermisos->setFile(null);
        $this->assertNull($csvPermisos->getFile());
    }

    public function testValidationFailsForInvalidFileType()
    {
        // Archivo con extensión .html para que Symfony detecte MIME real
        $tempPath = tempnam(sys_get_temp_dir(), 'invalid');
        $htmlContent = "<html><body>Contenido HTML</body></html>";
        file_put_contents($tempPath, $htmlContent);
        rename($tempPath, $tempPath . '.html');
        $tempPath .= '.html';

        $file = new UploadedFile(
            $tempPath,
            'archivo.html',
            null, // MIME nulo para que Symfony lo detecte automáticamente con finfo
            null,
            UPLOAD_ERR_OK,
            true
        );

        $csvPermisos = new CsvPermisos();
        $csvPermisos->setFile($file);

        $validator = $this->createValidator();
        $violations = $validator->validate($csvPermisos);

        $this->assertGreaterThan(0, count($violations), "Expected validation violations but found none.");
        $this->assertSame("Cargar solo archivos formato .csv", $violations[0]->getMessage());
    }

}
