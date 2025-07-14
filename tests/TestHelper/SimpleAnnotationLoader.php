<?php

namespace Tests\TestHelper;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;
use ReflectionClass;
use ReflectionMethod;

class SimpleAnnotationLoader extends AnnotationClassLoader
{
    protected $reader;

    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
        parent::__construct($reader);
    }

    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        // Este método es requerido por la clase base, pero no se necesita implementar nada adicional aquí.
    }

    public function load($classOrFile, $type = null)
    {
        $collection = new RouteCollection();

        // Protege contra entrada incorrecta: si es FQCN, usa Reflection
        if (!is_file($classOrFile) && class_exists($classOrFile)) {
            $ref = new \ReflectionClass($classOrFile);
            $classOrFile = $ref->getFileName();
        }

        // Verifica que el archivo exista antes de leerlo
        if (!is_file($classOrFile)) {
            throw new \InvalidArgumentException("Archivo no encontrado: $classOrFile");
        }

        $className = $this->findClassName($classOrFile);
        if (!$className) {
            return $collection;
        }

        if (!class_exists($className, false)) {
            require_once $classOrFile;
        }

        if (!class_exists($className)) {
            return $collection;
        }

        $class = new \ReflectionClass($className);

        foreach ($class->getMethods() as $method) {
            foreach ($this->reader->getMethodAnnotations($method) as $annot) {
                if ($annot instanceof \Symfony\Component\Routing\Annotation\Route ||
                    $annot instanceof \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route) {

                    $defaults = $annot->getDefaults();
                    $defaults['_controller'] = $class->getName() . '::' . $method->getName();

                    $route = new Route(
                        $annot->getPath(),
                        $defaults,
                        $annot->getRequirements(),
                        $annot->getOptions(),
                        $annot->getHost(),
                        $annot->getSchemes(),
                        $annot->getMethods(),
                        $annot instanceof \Symfony\Component\Routing\Annotation\Route ? $annot->getCondition() : ''
                    );

                    $collection->add($annot->getName(), $route);
                }
            }
        }

        return $collection;
    }


    private function findClassName($filename)
    {
        // Extrae namespace + class del archivo
        $contents = file_get_contents($filename);
        $namespace = '';
        if (preg_match('#^namespace\s+(.+?);$#sm', $contents, $m)) {
            $namespace = $m[1] . '\\';
        }
        if (preg_match('#class\s+([^\s]+)#', $contents, $m)) {
            return $namespace . $m[1];
        }
        return null;
    }
}
