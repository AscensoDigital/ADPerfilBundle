<?php

namespace Tests\TestHelper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy;

abstract class FunctionalTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        $path = __DIR__ . '/../app/cache/test/test.sqlite';
        if (file_exists($path)) {
            @chmod($path, 0666);
            unlink($path);
        }

        $client = static::createClient();

        // ⚠️ Forzar recompilación del contenedor (evita errores en modo test)
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->remove($client->getContainer()->getParameter('kernel.cache_dir'));

        /** @var EntityManagerInterface $em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        // Regenerar esquema
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);

        if (getenv('DEBUG_TESTS')) {
            echo "\n🔧 Esquema creado con " . count($metadata) . " entidades.\n";
        }

        // Verifica si Menu está en metadata
        $found = false;
        foreach ($metadata as $meta) {
            if ($meta->getName() === \AscensoDigital\PerfilBundle\Entity\Menu::class) {
                $found = true;
                break;
            }
        }
        if (getenv('DEBUG_TESTS')) {
            echo $found ? "✅ Entidad Menu encontrada.\n" : "❌ Menu no está en metadata.\n";
        }

        // Cargar fixtures reales y de test
        $loader = new Loader();
        $loader->loadFromDirectory(__DIR__ . '/../../DataFixtures/ORM');
        $loader->loadFromDirectory(__DIR__ . '/../Fixtures');

        $executor = new ORMExecutor($em, new ORMPurger());
        $executor->execute($loader->getFixtures(), true);

        // Confirmación de perfil
        $perfil = $em->getRepository(PerfilDummy::class)->findOneBy([]);
        if (!$perfil) {
            if (getenv('DEBUG_TESTS')) {
                echo "❌ No se pudo cargar el perfil de test.\n";
            }
            return;
        }

        if (getenv('DEBUG_TESTS')) {
            echo "✅ Perfil Dummy ID: " . $perfil->getId() . "\n";
            echo "📂 Test DB path: $path\n";
            echo "📄 Exists? " . (file_exists($path) ? 'YES' : 'NO') . "\n";
        }
    }

    protected function logInAsAdmin($client): void
    {
        $container = $client->getContainer();
        $session = $container->get('session');
        $firewallName = 'main';

        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $user = $em->getRepository(UserDummy::class)->findOneBy([]);
        $perfil = $em->getRepository(PerfilDummy::class)->findOneBy([]);

        $token = new UsernamePasswordToken($user, null, $firewallName, ['ROLE_ADMIN']);
        $container->get('security.token_storage')->setToken($token);

        $session->set('_security_' . $firewallName, serialize($token));
        $sessionName = $container->getParameter('ad_perfil.session_name');
        $session->set($sessionName, $perfil->getId());
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
    }
}
