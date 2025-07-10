<?php

namespace Tests\TestHelper;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadataFactory;

abstract class FunctionalTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);

        // 🔐 Cargar solo entidades válidas, evitando UserInterface
        $metadataFactory = new ClassMetadataFactory();
        $metadataFactory->setEntityManager($em);
        $validMetadata = [];

        foreach ($em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames() as $className) {
            if ($className === 'AscensoDigital\PerfilBundle\Model\UserInterface') {
                continue;
            }
            try {
                $validMetadata[] = $metadataFactory->getMetadataFor($className);
            } catch (\Exception $e) {
                // Clase inválida (abstracta, interfaz, etc.)
            }
        }

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($validMetadata);
    }
}
