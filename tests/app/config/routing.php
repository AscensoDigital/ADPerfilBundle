<?php

use Symfony\Component\Routing\Route;
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

/* foreach ($routes as $name => $route) {
    if (strpos($route->getPath(), 'mapa') !== false || strpos($name, 'mapa') !== false) {
        echo "\n🔎 Ruta cargada: $name => " . $route->getPath() . "\n";
        echo "   Controller: " . $route->getDefault('_controller') . "\n";
    }
} */

// 🛠️ Rutas dummy necesarias solo para test
$routes->add('fos_user_security_login', new Route(
    '/login-dummy',
    ['_controller' => 'FrameworkBundle:Template:template', 'template' => '@Twig/Exception/error.html.twig']
));

$routes->add('fos_user_security_logout', new Route(
    '/logout-dummy',
    ['_controller' => 'FrameworkBundle:Template:template', 'template' => '@Twig/Exception/error.html.twig']
));

return $routes;
