# Personal Block

Este directorio contiene el código fuente para el bloque Gutenberg "Personal Block" del plugin Personal.
Este bloque permite listar el personal filtrado por categorías y ordenado según diversos criterios.

## Estructura del Directorio

- **`src/`**: Contiene los archivos fuente del bloque (JavaScript, SCSS, y la lógica de renderizado en PHP).
    - **`personal-block/block.json`**: Archivo de configuración principal del bloque. Define metadatos, atributos y scripts.
    - **`personal-block/render.php`**: Lógica PHP para renderizar el bloque en el frontend.
- **`build/`**: (Generado) Contiene los archivos compilados listos para producción.
- **`personal-block.php`**: Archivo principal que registra el bloque en WordPress.

## Configuración del Bloque

El bloque acepta los siguientes atributos configurables desde el editor:

| Atributo | Tipo | Por defecto | Descripción |
| :--- | :--- | :--- | :--- |
| `orderBy` | String | `date-desc` | Criterio de ordenamiento (ej. `date-desc`, `title-asc`). |
| `categories` | Array | `[]` | Lista de IDs de términos de la taxonomía `categorias` para filtrar. |
| `columns` | Number | `3` | Número de columnas para mostrar el listado (máximo 4). |

## Desarrollo y Comandos

Para instalar dependencias y compilar el bloque durante el desarrollo:

```bash
npm install
npm start
npm run build
```

## Enlaces de Interés

- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Block Configuration (block.json)](https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/)
- [Dynamic Blocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/)
