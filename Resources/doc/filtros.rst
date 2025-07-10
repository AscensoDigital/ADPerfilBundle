Filtros
=======

El sistema de filtros permite aplicar restricciones dinámicas sobre colecciones de datos
asociadas a perfiles o usuarios. Los filtros se definen en la configuración del bundle
bajo el nodo `ad_perfil.filtros`.

Ejemplo básico
--------------

.. code-block:: yaml

    ad_perfil:
        filtros:
            pais:
                type: Symfony\Bridge\Doctrine\Form\Type\EntityType
                table_alias: p
                field: id
                operator: in
                options:
                    class: AppBundle\Entity\Pais
                    choice_label: nombre

Atributos disponibles
---------------------

- **type**: Clase del tipo de form a usar (por defecto `EntityType`)
- **table_alias**: Alias de tabla o array de alias usados en el filtro
- **field**: Campo a filtrar (por defecto `id`)
- **operator**: Operador SQL/Doctrine (por defecto `in`)
- **function**: (opcional) Función SQL a aplicar, como `date`, `upper`, etc.
- **query_builder_method**: Método en el repositorio para armar el query builder
- **query_builder_perfil**: Booleano, indica si se pasa el objeto perfil al builder
- **query_builder_user**: Booleano, indica si se pasa el objeto user al builder
- **options**: Opciones adicionales que se pasarán al campo de formulario

Uso en plantillas
-----------------

Los filtros se aplican mediante variables inyectadas en las vistas. El helper `FiltroHelper`
construye las expresiones `where` según los filtros activos.

Ejemplo en una plantilla Twig:

.. code-block:: twig

    {% for filtro in filtros %}
        {{ form_row(filtro.form) }}
    {% endfor %}
