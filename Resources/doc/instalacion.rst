Instalación
===========

1. Instala el bundle vía Composer:

.. code-block:: bash

    composer require ascensodigital/perfil-bundle "dev-master"

2. Registra el bundle en tu `AppKernel.php`:

.. code-block:: php

    // app/AppKernel.php
    public function registerBundles()
    {
        return [
            // ...
            new AscensoDigital\PerfilBundle\ADPerfilBundle(),
        ];
    }

3. Crea las clases `Usuario` y `Perfil`:

.. code-block:: php

    // src/AppBundle/Entity/Usuario.php
    class Usuario extends BaseUser implements UsuarioInterface {}

    // src/AppBundle/Entity/Perfil.php
    class Perfil extends BasePerfil implements PerfilInterface {}

4. Agrega la configuración en `app/config/config.yml`:

.. code-block:: yaml

    ad_perfil:
        perfil_class: AppBundle\Entity\Perfil
        perfil_table_alias: pr
        icon_path: 'bundles/adperfil/images/logo.png'
        icon_alt: 'Ícono'
        proveedor_id: 1
        navegacion:
            homepage_title: Título del Sitio

5. Importa las rutas del bundle:

.. code-block:: yaml

    ad_perfil:
        resource: "@ADPerfilBundle/Controller/"
        type: annotation
        prefix: /ad-perfil

6. Actualiza la base de datos:

.. code-block:: bash

    php bin/console doctrine:schema:update --force

7. (Opcional) Carga fixtures:

.. code-block:: bash

    php bin/console doctrine:fixtures:load --append
