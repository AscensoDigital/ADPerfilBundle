Getting Started With ADPerfilBundle
===================================

Prerequisites
-------------

This version of the bundle requires Symfony 2.7+.

Installation
------------

Installation is a quick (I promise!) 7 step process:

1. Download ADPerfilBundle using composer
2. Enable the Bundle
3. Create your User class
4. Create your Perfil class
5. Configure the ADPerfilBundle
6. Import FOSUserBundle routing
7. Update your database schema

Step 1: Download ADPerfilBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

.. code-block:: bash

    $ composer require ascensodigital/perfil-bundle "dev-master"

Composer will install the bundle to your project's ``vendor/ascensodigital/perfil-bundle`` directory.

Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the kernel::

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new AscensoDigital\PerfilBundle\ADPerfilBundle(),
            // ...
        );
    }

Step 3: Create your User class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

a) Doctrine ORM User class
..........................

If you're persisting your users via the Doctrine ORM, then your ``User`` class
should live in the ``Entity`` namespace of your bundle and look like this to
start:

.. configuration-block::

    .. code-block:: php-annotations

        <?php
        // src/AppBundle/Entity/Usuario.php

        namespace AppBundle\Entity;

        use AscensoDigital\PerfilBundle\Model\User as BaseUser;
        use AscensoDigital\PerfilBundle\Model\PerfilInterface;
        use Doctrine\ORM\Mapping as ORM;

        /**
         * @ORM\Entity
         * @ORM\Table(name="ad_user")
         */
        class Usuario extends BaseUser implements UsuarioInterface
        {
            /**
             * @ORM\Id
             * @ORM\Column(type="integer")
             * @ORM\GeneratedValue(strategy="AUTO")
             */
            protected $id;

            public function __construct()
            {
                parent::__construct();
                // your own logic
            }
        }

Step 4: Create your Perfil class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

a) Doctrine ORM User class
..........................

If you're persisting your users via the Doctrine ORM, then your ``Perfil`` class
should live in the ``Entity`` namespace of your bundle and look like this to
start:

.. configuration-block::

    .. code-block:: php-annotations

        <?php
        // src/AppBundle/Entity/Perfil.php

        namespace AppBundle\Entity;

        use AscensoDigital\PerfilBundle\Entity\Perfil as BasePerfil;
        use AscensoDigital\PerfilBundle\Model\PerfilInterface;
        use Doctrine\ORM\Mapping as ORM;

        /**
         * @ORM\Entity
         * @ORM\Table(name="ad_perfil")
         */
        class Perfil extends BasePerfil implements PerfilInterface
        {
            /**
             * @ORM\Id
             * @ORM\Column(type="integer")
             * @ORM\GeneratedValue(strategy="AUTO")
             */
            protected $id;

            public function __construct()
            {
                parent::__construct();
                // your own logic
            }
        }


Step 5: Configure the ADPerfilBundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Add the following configuration to your ``config.yml`` file according to which type
of datastore you are using.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        ad_perfil:
            perfil_class: AppBundle\Entity\Perfil
            perfil_table_alias: pr
            icon_path: path-to-logo/logo.extension
            icon_alt: alt del logo del sitio
            navegacion:
                homepage_title: Titulo Sitio
                homepage_subtitle: Subtitulo Sitio

        doctrine:
            orm:
                resolve_target_entities:
                    AscensoDigital\PerfilBundle\Model\PerfilInterface: AppBundle\Entity\Perfil
                    AscensoDigital\PerfilBundle\Model\UserInterface: AppBundle\Entity\Usuario


Step 6: Import ADPerfilBundle routing files
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that you have activated and configured the bundle, all that is left to do is
import the ADPerfilBundle routing files.

By importing the routing files you will have ready made pages for things such as
logging in, creating users, etc.

.. configuration-block::

    .. code-block:: yaml

        # app/config/routing.yml
        ad_perfil:
            resource: "@ADPerfilBundle/Controller/"
            type: annotation
            prefix: /ad-perfil

Step 7: Update your database schema
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that the bundle is configured, you need to do is update your
database schema because you have added a new entity, the ``User`` class which you
created in Step 4.

For ORM run the following command.

.. code-block:: bash

    $ php bin/console doctrine:schema:update --force


Step 8: Load Fixtures to your database
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that the bundle is configured, the last thing you need to do is load your
database.

For ORM run the following command.

.. code-block:: bash

    $ php bin/console doctrine:fixtures:load --append

Step 9: Create admin perfil
~~~~~~~~~~~~~~~~~~~~~~~~~~~

