Modelo de Entidades
===================

A continuación se describen las principales entidades utilizadas por ADPerfilBundle
según el modelo de datos definido en el sistema.

PERFIL
------

- id: INTEGER (PK)
- nombre: VARCHAR(100)
- slug: VARCHAR(100)

AD_PERFIL_PERMISO
-----------------

- id: INTEGER (PK)
- nombre: VARCHAR(100) (UNIQUE)
- descripcion: MEDIUMTEXT (nullable)

AD_PERFIL_PERFIL_x_PERMISO
---------------------------

- id: INTEGER (PK)
- perfil_id: INTEGER (FK -> PERFIL.id)
- permiso_id: INTEGER (FK -> AD_PERFIL_PERMISO.id)
- acceso: BIT

AD_PERFIL_MENU
--------------

- id: INTEGER (PK)
- menu_superior_id: INTEGER (FK -> AD_PERFIL_MENU.id)
- permiso_id: INTEGER (FK -> AD_PERFIL_PERMISO.id)
- color_id: INTEGER (FK -> AD_PERFIL_COLOR.id)
- nombre: VARCHAR(100)
- slug: VARCHAR(100) (UNIQUE)
- descripcion: MEDIUMTEXT
- orden: INTEGER
- icono: VARCHAR(50)
- route: VARCHAR(150)
- visible: BIT

AD_PERFIL_REPORTE
-----------------

- id: INTEGER (PK)
- permiso_id: INTEGER (FK -> AD_PERFIL_PERMISO.id)
- reporte_seccion_id: INTEGER (FK -> AD_PERFIL_REPORTE_SECCION.id)
- reporte_categoria_id: INTEGER (FK -> AD_PERFIL_REPORTE_CATEGORIA.id)
- reporte_criterio_id: INTEGER (FK -> AD_PERFIL_REPORTE_CRITERIO.id)
- nombre: VARCHAR(200)
- codigo: VARCHAR(20) (UNIQUE)
- descripcion: MEDIUMTEXT
- route: VARCHAR(100)
- orden: INTEGER
- manager: VARCHAR(50)
- repositorio: VARCHAR(100)
- metodo: VARCHAR(100)

AD_PERFIL_REPORTE_SECCION
--------------------------

- id: INTEGER (PK)
- nombre: VARCHAR(200)
- orden: INTEGER
- style: VARCHAR(10)

AD_PERFIL_REPORTE_CATEGORIA
----------------------------

- id: INTEGER (PK)
- nombre: VARCHAR(100)
- orden: INTEGER
- icono: VARCHAR(50)

AD_PERFIL_REPORTE_CRITERIO
---------------------------

- id: INTEGER (PK)
- nombre: VARCHAR(20) (UNIQUE)
- manager: VARCHAR(50)
- repositorio: VARCHAR(100)
- metodo: VARCHAR(100)
- titulo: VARCHAR(100)
- include_user: BIT
- include_perfil: BIT

AD_PERFIL_COLOR
---------------

- id: INTEGER (PK)
- nombre: VARCHAR(50)
- codigo: CHAR(6)

AD_PERFIL_ARCHIVO
------------------

- id: INTEGER (PK)
- titulo: VARCHAR(255)
- fecha_publicacion: DATETIME
- ruta: VARCHAR(255)
- visible: BIT
- mime_type: VARCHAR(200)
- creador_id: INTEGER
- created_at: DATETIME

AD_PERFIL_REPORTE_x_CRITERIO
-----------------------------

- id: INTEGER (PK)
- reporte_id: INTEGER (FK -> AD_PERFIL_REPORTE.id)
- criterio_id: INTEGER
- archivo_id: INTEGER (FK -> AD_PERFIL_ARCHIVO.id)
- updated_at: DATETIME
- modificador_id: INTEGER
