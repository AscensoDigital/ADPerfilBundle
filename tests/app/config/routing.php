<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\AnnotationFileLoader;
use Symfony\Component\Config\FileLocator;
use Tests\TestHelper\SimpleAnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;

$routes = new RouteCollection();

$loader = new AnnotationFileLoader(
    new FileLocator(__DIR__ . '/../../../Controller'),
    new SimpleAnnotationLoader(new AnnotationReader())
);

foreach ([
             'FiltroController.php',
             'NavegacionController.php',
             'PerfilController.php',
             'PermisoController.php',
             'ReporteController.php',
         ] as $file) {
    $routes->addCollection($loader->load(__DIR__ . '/../../../Controller/' . $file));
}

return $routes;
