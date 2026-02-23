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

### Bloque de Gutenberg

También puedes listar los perfiles de personal utilizando el bloque de Gutenberg "Bloque de Personal".

1.  En el editor de WordPress, haz clic en el botón '+' para añadir un nuevo bloque.
2.  Busca "Bloque de Personal" y selecciónalo.
3.  Una vez añadido, puedes configurar las siguientes opciones en el panel lateral de ajustes del bloque:
    *   **Opciones de ordenamiento**: Permite ordenar la lista de personal de forma manual (el número más bajo aparece primero), por nombre (A-Z, Z-A), fecha de publicación (más nuevos/antiguos primero) o fecha de modificación (más nuevos/antiguos primero).
    *   **Seleccionar categoría**: Elige una o varias categorías para filtrar los perfiles de personal.
    *   **Cantidad de columnas**: Define el número de columnas (entre 1 y 4) en las que se mostrarán los perfiles.

### Widget de Elementor

El plugin también incluye un widget compatible con el constructor de páginas **Elementor**.

1.  En el editor de Elementor, busca el widget **"Listado de Personal"**.
2.  Arrástralo a la sección deseada.
3.  Utiliza los controles del panel izquierdo (pestaña Contenido) para:
    *   Seleccionar categorías.
    *   Configurar el ordenamiento.
    *   Definir el número de columnas.

## Importador de CSV

El plugin cuenta con una función para importar el personal automaticamente mediante un CSV

### Requisitos del Archivo CSV

Para que el archivo sea procesado correctamente por el importador, el CSV generado debe cumplir con las siguientes características:

1. **Formato:** Debe ser un archivo de texto plano con extensión `.csv` (`text/csv`).
2. **Cantidad de Columnas:** El archivo debe contener **exactamente 16 columnas**.
3. **Nombres de Cabeceras:** La primera fila del CSV debe contener exactamente estos nombres de columnas:

   * **`email` (Obligatorio):** Identificador único para crear o actualizar. Debe ser un correo válido.
   * **`nombre_apellido` (Obligatorio):** Nombre completo. No puede estar vacío.
   * **`telefono` (Opcional):** Solo admite números, espacios, guiones (`-`), paréntesis y el signo `+`.
   * **`unidad_de_investigacion` (Opcional):** Nombre de la unidad. No puede contener enlaces (`http`/`www`).
   * **`rol_unidad_de_investigacion` (Opcional):** Cargo o rol. No puede contener enlaces.
   * **`grado_alcanzado` (Opcional):** Título académico. No puede contener enlaces.
   * **`sedici` (Opcional):** Nombre del personal tal cual figura en SEDICI.
   * **`cic` (Opcional):** Nombre del personal tal cual figura en CIC Digital.
   * **`conicet` (Opcional):** Nombre del personal tal cual figura en CONICET.
   * **`google_scholar` (Opcional):** Debe ser una URL absoluta y válida (ej. `https://...`).
   * **`orcid` (Opcional):** Debe ser una URL absoluta y válida.
   * **`linkedin` (Opcional):** Debe ser una URL absoluta y válida.
   * **`facebook` (Opcional):** Debe ser una URL absoluta y válida.
   * **`twitter` (Opcional):** Debe ser una URL absoluta y válida.
   * **`researchgate` (Opcional):** Debe ser una URL absoluta y válida.
   * **`biografia` (Opcional):** Resumen profesional. Permite texto enriquecido y formato HTML (`<p>`, `<strong>`, `<a>`).



## Licencia
