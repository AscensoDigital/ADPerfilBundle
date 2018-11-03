Getting Started With ADPerfilBundle
===================================

Prerequisites
-------------

This version of the bundle requires Symfony >=4.1.

Installation
------------

Installation is 10 step process:

1. Download ADPerfilBundle using composer
2. Enable the Bundle
3. Create your Perfil class
4. Create your User class
5. Configure the ADPerfilBundle
6. Import FOSUserBundle routing
7. Update your database schema
8. Load Fixtures to your database
9. Create admin perfil
10. Set up as ``super-admin`` the admin perfil

Step 1: Download ADPerfilBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Require the bundle with composer:

.. code-block:: bash

    $ composer require ascensodigital/perfil-bundle

Composer will install the bundle to your project's ``vendor/ascensodigital/perfil-bundle`` directory.

Step 2: Enable the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Enable the bundle in the bundles.php::

    <?php
    // config/bundles.php

    return [
            // ...
            AscensoDigital\PerfilBundle\ADPerfilBundle::class => ['all' => true],
            AscensoDigital\ComponentBundle\ADComponentBundle::class => ['all' => true],
            // ...
        ];

Step 3: Create your Perfil class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

a) Doctrine ORM Perfil class
..........................

If you're persisting your profiles via the Doctrine ORM, then your ``Perfil`` class
should live in the ``Entity`` namespace of your app and look like this to
start:

    <?php
    // src/Entity/Perfil.php

    namespace App\Entity;

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

Step 4: Create your User class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

a) Doctrine ORM User class
..........................

If you're persisting your users via the Doctrine ORM, then your ``User`` class
should live in the ``Entity`` namespace of your app and look like this to
start:

    <?php
    // src/Entity/Usuario.php

    namespace App\Entity;

    use AscensoDigital\PerfilBundle\Model\User as BaseUser;
    use AscensoDigital\PerfilBundle\Model\UserInterface;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     */
    class Usuario extends BaseUser implements UserInterface
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @var ArrayCollection
         * @ORM\OneToMany(targetEntity="UsuarioXPerfil", mappedBy="usuario")
         */
        private $usuarioXPerfils;

        public function __construct()
        {
            parent::__construct();
            $this->usuarioXPerfils = new ArrayCollection();
            // your own logic
        }

        public function getPerfils()
        {
            if(null==$this->perfils || 0==count($this->perfils)) {
                if($this->getUsuarioXPerfils()->count()) {
                    /** @var UsuarioXPerfil $uxp */
                    foreach ($this->getUsuarioXPerfils()->getValues() as $uxp) {
                        if($uxp->getActive()) {
                            $this->perfils[$uxp->getPerfil()->getId()] = $uxp->getPerfil();
                        }
                    }
                }
            }
            return $this->perfils;
        }
    }

Step 5: Create your UserXPerfil class
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

a) Doctrine ORM User class
..........................

If you're persisting your users via the Doctrine ORM, then your ``UserXPerfil`` class
should live in the ``Entity`` namespace of your app and look like this to
start:

    <?php
    // src/Entity/UsuarioXPerfil.php

    namespace App\Entity;

    use AscensoDigital\PerfilBundle\Model\UsuarioXPerfil as BaseUsuarioXPerfil;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     */
    class UsuarioXPerfil extends BaseUsuarioXPerfil
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

Step 6: Configure the ADPerfilBundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Create config file ``ad_perfil.yaml`` in ``config/packages/`` and Add the following configuration according to which type
of datastore you are using.

.. configuration-block::

    .. code-block:: yaml

        # config/packages/ad_perfil.yaml
        ad_perfil:
            perfil_class: AppBundle\Entity\Perfil
            perfil_table_alias: pr
            icon_path: path-to-logo/logo.extension
            icon_alt: alt del logo del sitio
            navegacion:
                homepage_title: Titulo Sitio
                homepage_subtitle: Subtitulo Sitio

        #config/packages/doctrine.yaml
        doctrine:
            orm:
                # ...
                resolve_target_entities:
                    AscensoDigital\PerfilBundle\Model\PerfilInterface: App\Entity\Perfil
                    AscensoDigital\PerfilBundle\Model\UserInterface: App\Entity\Usuario


Step 7: Import ADPerfilBundle routing files
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that you have activated and configured the bundle, all that is left to do is
import the ADPerfilBundle routing files.

By importing the routing files you will have ready made pages for things such as
logging in, creating users, etc.

.. configuration-block::

    .. code-block:: yaml

        # config/routes.yml
        ad_perfil:
            resource: "@ADPerfilBundle/Controller/"
            type: annotation
            prefix: /ad-perfil

Step 8: Update your database schema
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that the bundle is configured, you need to do is update your
database schema because you have added a new entity, the ``User`` class which you
created in Step 4.

For ORM run the following command.

.. code-block:: bash

    $ php bin/console doctrine:schema:update --force


Step 9: Load Fixtures to your database
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Now that the bundle is configured, the last thing you need to do is load your
database.

For ORM run the following command.

.. code-block:: bash

    $ php bin/console doctrine:fixtures:load --append

Step 10: Create admin perfil
~~~~~~~~~~~~~~~~~~~~~~~~~~~

