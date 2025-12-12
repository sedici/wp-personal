# WP-Personal

Este plugin permite crear perfiles de investigadores y mostrar estos perfiles en una página utilizando un shortcode

## Instalación

### Requisitos

- WordPress 5.x o superior.
- PHP 7.0 o superior.

### Instalación Manual

1. Descarga el archivo `.zip` del plugin.
2. Ve a tu panel de administración de WordPress.
3. Dirígete a **Plugins** > **Añadir nuevo**.
4. Haz clic en **Subir plugin**.
5. Selecciona el archivo `.zip` y haz clic en **Instalar ahora**.
6. Activa el plugin desde el panel de administración.

## Uso

Para crear perfiles de investigadores, accede al Escritorio de WordPress y dirígete al menú Personal. Allí, selecciona la opción Agregar nuevo personal.

Una vez creado el/los perfiles es posible listarlos en una página agregando el shortcode 

- `[list-personal]`

- Para listar los perfiles pertenecientes a una categoria agregar la opción `category_id` al shortcode : `[list-personal category_id=3]`
- Para organizar los perfiles por columnas, agregar la opción `columns`al shortcode : `[list-personal columns=2]`

### Bloque de Personal

También puedes listar los perfiles de personal utilizando el bloque de Gutenberg "Bloque de Personal".

1.  En el editor de WordPress, haz clic en el botón '+' para añadir un nuevo bloque.
2.  Busca "Bloque de Personal" y selecciónalo.
3.  Una vez añadido, puedes configurar las siguientes opciones en el panel lateral de ajustes del bloque:
    *   **Opciones de ordenamiento**: Permite ordenar la lista de personal de forma manual (el número más bajo aparece primero), por nombre (A-Z, Z-A), fecha de publicación (más nuevos/antiguos primero) o fecha de modificación (más nuevos/antiguos primero).
    *   **Seleccionar categoría**: Elige una o varias categorías para filtrar los perfiles de personal.
    *   **Cantidad de columnas**: Define el número de columnas (entre 1 y 4) en las que se mostrarán los perfiles.

## Licencia
