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
            resource: "%kernel.root_dir%/config/routing.yml"

    twig:
        debug: true
        strict_variables: true
        paths:
            "%kernel.root_dir%/../../Resources/views": ~

    doctrine:
        dbal:
            driver: pdo_sqlite
            memory: true
        orm:
            auto_generate_proxy_classes: true
            auto_mapping: true

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
