<?php

namespace Tests\AscensoDigital\PerfilBundle\Form\Type;

use AscensoDigital\PerfilBundle\Entity\Permiso;
use AscensoDigital\PerfilBundle\Form\Type\PermisosFormType;
use AscensoDigital\PerfilBundle\Form\Type\PerfilXPermisoType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Validation;

class PermisosFormTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        return [
            new PreloadedExtension([new PermisosFormType(), new PerfilXPermisoType()], []),
            new ValidatorExtension($validator),
        ];
    }

    public function testFormContainsExpectedFields()
    {
        $form = $this->factory->create(PermisosFormType::class);

        $view = $form->createView();
        $this->assertArrayHasKey('nombre', $view->children);
        $this->assertArrayHasKey('descripcion', $view->children);
        $this->assertArrayHasKey('perfilXPermisos', $view->children);
    }

    public function testDescripcionFieldHasLabel()
    {
        $form = $this->factory->create(PermisosFormType::class);
        $view = $form->createView();
        $this->assertEquals('Descripción', $view->children['descripcion']->vars['label']);
    }

    public function testPerfilXPermisosIsCollectionType()
    {
        $form = $this->factory->create(PermisosFormType::class);
        $config = $form->get('perfilXPermisos')->getConfig();

        $this->assertInstanceOf(CollectionType::class, $config->getType()->getInnerType());
        $this->assertEquals(PerfilXPermisoType::class, $config->getOption('entry_type'));
        $this->assertFalse($config->getOption('by_reference'));
    }

    public function testSubmitValidDataHydratesEntity()
    {
        $permiso = new Permiso();
        $form = $this->factory->create(PermisosFormType::class, $permiso);

        $formData = [
            'nombre' => 'GESTION',
            'descripcion' => 'Gestión avanzada',
            'perfilXPermisos' => [],
        ];

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('GESTION', $permiso->getNombre());
        $this->assertEquals('Gestión avanzada', $permiso->getDescripcion());
    }
}
