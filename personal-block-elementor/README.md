# Personal Block Elementor Widget

Este directorio contiene el código fuente para el Widget de Elementor "Listado de Personal" del plugin Personal.
Este widget permite integrar la funcionalidad de listado de personal dentro del constructor de páginas Elementor.

## Estructura del Directorio

- **`init.php`**: Archivo de inicialización que registra el widget en el gestor de Elementor.
- **`class-widget-personal.php`**: Clase principal `Widget_Personal` que extiende `\Elementor\Widget_Base`. Contiene la definición de controles y la lógica de renderizado.

## Configuración del Widget

El widget dispone de los siguientes controles en el panel de Elementor (pestaña Contenido):

| Control | Tipo | Por defecto | Descripción |
| :--- | :--- | :--- | :--- |
| `categories` | Select2 (Multiple) | `[]` | Permite seleccionar una o varias categorías de personal para filtrar. |
| `orderBy` | Select | `date-desc` | Criterio de ordenamiento (ej. `date-desc`, `title-asc`). |
| `columns` | Number | `3` | Número de columnas para mostrar el listado (1 a 4). |

## Desarrollo

Este widget depende de que el plugin "Elementor" esté activo. Hereda estilos y vistas compartidas del núcleo del plugin "Personal".

## Enlaces de Interés

- [Elementor Developers Handbook](https://developers.elementor.com/)
- [Creating a new Widget](https://developers.elementor.com/docs/widgets/)
