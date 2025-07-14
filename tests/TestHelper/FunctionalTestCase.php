<?php

namespace Tests\TestHelper;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy;
use Tests\AscensoDigital\PerfilBundle\Entity\Dummy\UserDummy;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManagerInterface;

abstract class FunctionalTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;

    protected function setUp(): void
    {
        $path = __DIR__ . '/../app/cache/test/test.sqlite';
        if (file_exists($path)) {
            @chmod($path, 0666);
            unlink($path);
        }

        $this->client = static::createClient();

        // ⚠️ Forzar recompilación del contenedor (evita errores en modo test)
        $fs = new \Symfony\Component\Filesystem\Filesystem();
        $fs->remove($this->client->getContainer()->getParameter('kernel.cache_dir'));

        $container = $this->client->getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadata);

        if (getenv('DEBUG_TESTS')) {
            echo "\n🔧 Esquema creado con " . count($metadata) . " entidades.";
        }

        $menuFound = false;
        foreach ($metadata as $meta) {
            if ($meta->getName() === 'AscensoDigital\\PerfilBundle\\Entity\\Menu') {
                $menuFound = true;
                if (getenv('DEBUG_TESTS')) {
                    echo "\n✅ Entidad Menu encontrada.";
                }
                break;
            }
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

    protected function logInAsAdmin(): array
    {
        $container = $this->client->getContainer();
        $session = $container->get('session');
        $firewallName = 'main';

        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();

        /** @var UserDummy $user */
        $user = $em->getRepository(UserDummy::class)->find(1);
        $perfil = $em->getRepository(PerfilDummy::class)->findOneBy([]);
        if (getenv('DEBUG_TESTS')) {
            echo "\nUSER ID: " . $user->getId();
        }

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $container->get('security.token_storage')->setToken($token);
        $session->set('_security_' . $firewallName, serialize($token));

        $sessionName = $container->getParameter('ad_perfil.session_name');
        $session->set($sessionName, $perfil->getId());
        $session->save();

        $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
        return ['user' => $user, 'perfil' => $perfil];
    }

    protected function logInWithoutPerfil(): array
    {
        $container = $this->client->getContainer();
        $session = $container->get('session');
        $firewallName = 'main';


        $user = $container->get('doctrine')->getRepository(UserDummy::class)->find(1);
        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $container->get('security.token_storage')->setToken($token);
        $session->set('_security_' . $firewallName, serialize($token));
        $session->remove($container->getParameter('ad_perfil.session_name'));
        $session->save();

        $this->client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
        return ['user' => $user];
    }

    protected function logInWithRestrictedPermiso(): array
    {
        // Aquí podrías cargar un segundo perfil con permisos limitados
        // o ajustar los fixtures según necesidad. Por ahora retorna sin perfil válido.
        return $this->logInWithoutPerfil();
    }
}
