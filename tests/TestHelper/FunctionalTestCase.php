<?php

namespace Tests\TestHelper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
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
        /** @var EntityManagerInterface $em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        // Regenerar esquema
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);

        echo "\n🔧 Esquema creado con " . count($metadata) . " entidades.\n";

        // Verifica si Menu está en metadata
        $found = false;
        foreach ($metadata as $meta) {
            if ($meta->getName() === \AscensoDigital\PerfilBundle\Entity\Menu::class) {
                $found = true;
                break;
            }
        }
        echo $found ? "✅ Entidad Menu encontrada.\n" : "❌ Menu no está en metadata.\n";

        // Cargar fixtures reales y de test
        $loader = new Loader();
        $loader->loadFromDirectory(__DIR__ . '/../../DataFixtures/ORM');
        $loader->loadFromDirectory(__DIR__ . '/../Fixtures');

        $executor = new ORMExecutor($em, new ORMPurger());
        $executor->execute($loader->getFixtures(), true);

        // Buscar perfil de test
        $perfil = $em->getRepository(PerfilDummy::class)->findOneBy([]);
        if (!$perfil) {
            echo "❌ No se pudo cargar el perfil de test.\n";
            return;
        }

        echo "✅ Perfil Dummy ID: " . $perfil->getId() . "\n";

        // Simula login con ROLE_ADMIN
        $session = $client->getContainer()->get('session');
        $firewallName = 'main';

        $token = new UsernamePasswordToken('admin', null, $firewallName, ['ROLE_ADMIN']);
        $session->set('_security_' . $firewallName, serialize($token));

        $sessionName = $client->getContainer()->getParameter('ad_perfil.session_name');
        $session->set($sessionName, $perfil->getId());
        $session->save();

        $client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );

        echo "📂 Test DB path: $path\n";
        echo "📄 Exists? " . (file_exists($path) ? 'YES' : 'NO') . "\n";
    }
}
