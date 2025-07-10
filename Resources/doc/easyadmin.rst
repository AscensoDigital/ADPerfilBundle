Integración con EasyAdmin
=========================

ADPerfilBundle puede integrarse con EasyAdminBundle para exponer formularios y
administrar entidades como `Perfil`, `Permiso`, `Menú`, `Reporte`, etc.

Configuración
-------------

El archivo `Resources/config/ad_perfil.easyadmin.yml` contiene una configuración
predeterminada que puedes importar desde `config.yml`:

.. code-block:: yaml

    imports:
        - { resource: "@ADPerfilBundle/Resources/config/ad_perfil.easyadmin.yml" }

Esto agregará automáticamente la definición de entidades al backend de EasyAdmin.

Consideraciones en entorno test
-------------------------------

En entornos de test funcional, puede causar errores si EasyAdminBundle no está registrado.
Para evitar eso:

1. Agrega una condición en `ADPerfilExtension.php`:

.. code-block:: php

    if ($container->getParameter('kernel.environment') !== 'test') {
        $loader->load('ad_perfil.easyadmin.yml');
    }

2. O bien, usa un archivo `config_test.yml` sin importar `easyadmin`.

Configuración personalizada
---------------------------

Puedes extender el archivo `ad_perfil.easyadmin.yml` para sobrescribir secciones:

.. code-block:: yaml

    easy_admin:
        entities:
            Perfil:
                class: AppBundle\Entity\Perfil
                list:
                    fields: ['id', 'nombre', 'slug']
                form:
                    fields: ['nombre']

            Permiso:
                class: AscensoDigital\PerfilBundle\Entity\Permiso
                list:
                    fields: ['id', 'nombre']
