<?php

namespace Tests\AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Form\Type\CsvPermisosType;
use AscensoDigital\PerfilBundle\Util\CsvPermisos;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class CsvPermisosTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $type = new CsvPermisosType();
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return [
            new PreloadedExtension([$type], []),
            new ValidatorExtension($validator),
        ];
    }

    public function testFormCreatesWithValidData()
    {
        $model = new CsvPermisos();
        $form = $this->factory->create(CsvPermisosType::class, $model);

        $this->assertTrue($form->isSynchronized());
        $this->assertInstanceOf(CsvPermisos::class, $form->getData());
    }

    public function testFormViewContainsFileField()
    {
        $form = $this->factory->create(CsvPermisosType::class);
        $view = $form->createView();
        $this->assertArrayHasKey('file', $view->children);
    }

    public function testFileFieldConfiguration()
    {
        $form = $this->factory->create(CsvPermisosType::class);
        $config = $form->get('file')->getConfig();

        $this->assertEquals('file', $config->getType()->getInnerType()->getName());
        $this->assertEquals('Csv Permisos', $config->getOption('label'));
    }

    public function testInvalidSubmissionIsRejected()
    {
        $form = $this->factory->create(CsvPermisosType::class, new CsvPermisos());
        $form->submit(['file' => null]);

        $this->assertFalse($form->isValid());
    }
}
