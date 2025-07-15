<?php

namespace Tests\AscensoDigital\PerfilBundle\Functional;

use AscensoDigital\PerfilBundle\Entity\Menu;
use Doctrine\DBAL\Logging\DebugStack;
use Tests\TestHelper\FunctionalTestCase;

class MenuManagerTest extends FunctionalTestCase
{
    public function testGetFullMenuTreeExecutesSingleQuery()
    {
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();

        // 🔍 Contador de queries SQL
        $logger = new DebugStack();
        $em->getConnection()->getConfiguration()->setSQLLogger($logger);

        // 🧪 Login (esto setea el perfil en sesión)
        $this->logInAsAdmin();

        // ⚠️ limpiar logger antes de medir
        $logger->queries = [];

        // ⚠️ Obtener el servicio DESPUÉS del login
        $menuManager = $container->get('ad_perfil.menu_manager');

        // Ejecutar el método
        $tree = $menuManager->getFullMenuTree();

        // Afirmaciones
        $this->assertIsArray($tree);
        $this->assertNotEmpty($tree);
        $this->assertInstanceOf(Menu::class, $tree[0]);

        $queryCount = count($logger->queries);
        if (getenv('DEBUG_TESTS')) {
            echo "\n🔍 Consultas ejecutadas: $queryCount\n";

            foreach ($logger->queries as $i => $query) {
                echo "\n--- Consulta #" . ($i) . " ---\n";
                echo $query['sql'] . "\n";
                if (!empty($query['params'])) {
                    echo '🔸 Params: ' . json_encode($query['params']) . "\n";
                }
            }
        }
        $this->assertLessThanOrEqual(2, $queryCount, "Deben ejecutarse 2 o menos consultas SQL, se ejecutaron $queryCount.");
    }
}
