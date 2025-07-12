<?php

namespace Tests\TestHelper;

use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Route;
use Doctrine\Common\Annotations\AnnotationReader;

class SimpleAnnotationLoader extends AnnotationClassLoader
{
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        // puedes dejar vacío o agregar lógica según tus anotaciones personalizadas
    }
}
