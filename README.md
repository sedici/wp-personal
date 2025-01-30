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

## Licencia
