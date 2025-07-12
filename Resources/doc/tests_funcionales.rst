Tests Funcionales
=================

1. No cargues `easy_admin` en entorno de test:

.. code-block:: php

    if ($container->getParameter('kernel.environment') !== 'test') {
        $loader->load('ad_perfil.easyadmin.yml');
    }

2. Configura `config_test.yml`:

.. code-block:: yaml

    framework:
        secret: test
        test: ~
        session:
            storage_id: session.storage.mock_file
        router:
            resource: "%kernel.root_dir%/config/routing.php"

    twig:
        debug: true
        strict_variables: true
        paths:
            "%kernel.root_dir%/../../Resources/views": ~

    doctrine:
        dbal:
            driver: pdo_sqlite
            path: "%kernel.cache_dir%/test.sqlite"
        orm:
            auto_generate_proxy_classes: true
            auto_mapping: true
            resolve_target_entities:
                AscensoDigital\PerfilBundle\Model\UserInterface: Tests\AscensoDigital\PerfilBundle\Entity\Dummy\UserDummy
                AscensoDigital\PerfilBundle\Model\PerfilInterface: Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy

    security:
        providers:
            in_memory:
                memory: ~

        firewalls:
            test:
                pattern: ^/
                anonymous: true
                provider: in_memory

    parameters:
        request_listener.http_port: 80
        request_listener.https_port: 443

    ad_perfil:
        perfil_class: Tests\AscensoDigital\PerfilBundle\Entity\Dummy\PerfilDummy
        perfil_table_alias: perfil
        icon_path: 'tests/Fixtures/icono_dummy.png'
        icon_alt: 'Ícono de prueba'
        proveedor_id: 1
        navegacion:
            homepage_title: 'Test'

3. Usa un test con `WebTestCase`:

.. code-block:: php

    $client = static::createClient();
    $client->request(
        'POST',
        '/ruta-dummy',
        [],
        ['form' => ['file' => $uploadedFile]]
    );

4. Buenas prácticas al usar SQLite con archivo físico (`path:`):

- No uses `memory: true` si tu app y tests se ejecutan en diferentes kernels (lo que es común en `WebTestCase`)
- Usa `path: "%kernel.cache_dir%/test.sqlite"` para garantizar que todo el entorno comparta la misma conexión SQLite
- Si necesitas limpiar el archivo, hazlo **antes** de llamar a `static::createClient()`

.. code-block:: php

    protected function setUp(): void
    {
        $path = __DIR__ . '/../cache/test.sqlite';
        if (file_exists($path)) {
            @chmod($path, 0666);
            unlink($path);
        }

        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

        // Inserciones dummy...
    }
