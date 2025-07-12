PermisoVoter: Control de Acceso por Perfil
==========================================

El `PermisoVoter` es un componente de seguridad de ADPerfilBundle encargado de determinar si el perfil activo tiene acceso a ciertos menús o permisos lógicos. Evalúa dinámicamente la autorización en base a los permisos asociados al perfil en sesión.

Atributos soportados
---------------------

- ``MENU``: evalúa si un menú está permitido para el perfil.
- ``PERMISO``: evalúa si un permiso lógico (string) está habilitado para el perfil.

Flujo de autorización
----------------------

**Para `MENU`:**

1. Si el objeto es `null`, el acceso es permitido.
2. Si el slug del menú está en los permisos tipo ``Permiso::LIBRE`` → acceso permitido.
3. Si no está en ``LIBRE`` pero sí en ``Permiso::RESTRICT`` → acceso permitido.
4. En cualquier otro caso, se deniega el acceso.

**Para `PERMISO`:**

- Se permite solo si el código del permiso está en la lista retornada por `PerfilXPermisoRepository::findArrayIdByPerfil`.

Dependencias
------------

- Perfil activo se extrae desde la sesión Symfony (clave configurable, por defecto: ``perfil``).
- Repositorios utilizados:
  - ``MenuRepository::findArrayPermisoByPerfil($perfilId)``
  - ``PerfilXPermisoRepository::findArrayIdByPerfil($perfilId)``

Ejemplo de uso programático
----------------------------

.. code-block:: php

   if ($this->decisionManager->decide($token, ['MENU'], $menu)) {
       // acceso permitido
   }

   if ($this->decisionManager->decide($token, ['PERMISO'], 'PERM_EDIT')) {
       // permiso lógico permitido
   }

Uso mediante anotaciones
-------------------------

El voter puede usarse directamente en anotaciones como:

.. code-block:: php

   use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

   /**
    * @Security("is_granted('PERMISO', 'ad_perfil-mn-mapa-sitio')")
    */
   public function mapaSitioAction() {
       // Acción protegida por permiso
   }

Configuración avanzada
-----------------------

Puedes personalizar la clave usada para obtener el perfil desde la sesión:

.. code-block:: yaml

   # services.yml
   ad_perfil.permiso_voter:
       class: AscensoDigital\PerfilBundle\Security\PermisoVoter
       arguments: ['@session', '@doctrine.orm.entity_manager', 'perfil']
       tags:
           - { name: security.voter }

Tests
-----

La clase ``PermisoVoterTest`` valida:

- Accesos válidos e inválidos para `MENU` y `PERMISO`
- Comportamiento con `null`, usuarios anónimos, atributos desconocidos
- Cobertura completa del método `voteOnAttribute`
