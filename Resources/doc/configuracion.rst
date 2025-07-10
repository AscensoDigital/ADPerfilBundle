Configuración
=============

Parámetros requeridos:

.. code-block:: yaml

    ad_perfil:
        perfil_class: AppBundle\Entity\Perfil
        perfil_table_alias: perfil
        icon_path: 'bundles/adperfil/images/icono.png'
        icon_alt: 'Ícono'
        proveedor_id: 1
        navegacion:
            homepage_title: 'Inicio'

Parámetros opcionales:

.. code-block:: yaml

    route_redirect: 'homepage'
    session_name: 'ad_perfil.perfil_id'
    valor_true: '1'
    valor_false: '0'
    separador_encabezado: false
    csv_permisos_path: '%kernel.root_dir%/config/ad_perfil_permisos.csv'
    navegacion:
        homepage_subtitle: ''
        homepage_route: 'homepage'
        homepage_name: 'Inicio'
        homepage_icono: 'fa fa-home'
        homepage_color: 'blanco'
