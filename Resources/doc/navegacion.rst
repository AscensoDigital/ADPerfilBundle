Uso en plantillas Twig de Navegacion
====================================

Agregar mensajes Flashes
------------------------
{{ include('ADPerfilBundle:Navegacion:alert-flashes.html.twig') }}


Agregar Breadcrumbs
-------------------
{{ render(controller('ADPerfilBundle:Navegacion:breadcrumbs')) }}


Agregar Titulo
--------------
{{ render(controller('ADPerfilBundle:Navegacion:pageTitle')) }}