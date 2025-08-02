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

    public function testGetMenusByMenuIdExecutesSingleQuery()
    {
        $container = self::$kernel->getContainer();
        $em = $container->get('doctrine')->getManager();

        // 🔍 Logger SQL para verificar cantidad de consultas
        $logger = new DebugStack();
        $em->getConnection()->getConfiguration()->setSQLLogger($logger);

        // 🧪 Login simulado
        $result = $this->logInAsAdmin();

        /** @var \AscensoDigital\PerfilBundle\Model\MenuManager $menuManager */
        $menuManager = $container->get('ad_perfil.menu_manager');

        // ⚠️ limpiar el logger antes de ejecutar
        $logger->queries = [];

        // 🔧 Acceder a los menús hijos de un menú padre (e.g., ID 1)
        $menus = $menuManager->getMenusByMenuId(1);

        // Validaciones
        $this->assertIsArray($menus);
        foreach ($menus as $menu) {
            $this->assertInstanceOf(Menu::class, $menu);
        }

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

    public function testMenusRaizCoincidenConFullTree()
    {
        $container = self::$kernel->getContainer();

        // Simula login (esto setea el perfil en sesión de forma correcta)
        $this->logInAsAdmin();

        /** @var \AscensoDigital\PerfilBundle\Model\MenuManager $manager */
        $manager = $container->get('ad_perfil.menu_manager');

        // Verificamos que la estructura raíz no esté vacía
        $menusRaiz = $manager->getMenusByMenuId(null);
        $fullTree = $manager->getFullMenuTree();

        $this->assertNotEmpty($menusRaiz, 'Los menús raíz no deben estar vacíos');
        $this->assertEquals($fullTree, $menusRaiz, 'getMenusByMenuId(null) debe retornar lo mismo que getFullMenuTree()');

        // Validar que ningún menú raíz tenga padre
        foreach ($menusRaiz as $menu) {
            $this->assertInstanceOf(Menu::class, $menu);
            $this->assertNull($menu->getMenuSuperior(), 'Los menús raíz no deben tener menú superior');
        }
    }

}
