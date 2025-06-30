<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 22-03-24
 * Time: 10:28
 */

namespace AscensoDigital\PerfilBundle\Util;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class CsvPermisos
{
    /**
     * @var UploadedFile $file
     * @Assert\NotBlank
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"text/csv","text/plain"},
     *     mimeTypesMessage = "Cargar solo archivos formato .csv"
     * )
     */
    protected $file;

    /**
     * Sets file
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
}