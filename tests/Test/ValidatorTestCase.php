<?php

namespace Tests\Test;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

abstract class ValidatorTestCase extends TestCase
{
    protected function createValidator(): ValidatorInterface
    {
        // Cargar metadatos vía anotaciones con Reader
        $metadataFactory = new LazyLoadingMetadataFactory(
            new AnnotationLoader(new AnnotationReader())
        );

        // Crear validador sin llamar a enableAnnotationMapping()
        return Validation::createValidatorBuilder()
            ->setMetadataFactory($metadataFactory)
            ->getValidator();
    }
}
