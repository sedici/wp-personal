<?php

namespace Personal\Inc\Render;

use Personal\Core\Personal_Model;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Renderiza el listado de personal en sus distintos layouts (carta, lista y tabla).
 * La usan el bloque de Gutenberg y el widget de Elementor para no duplicar la
 * consulta ni la elección de la vista en cada integración.
 */
class Render_Personal
{
    const LAYOUTS = array('carta', 'lista', 'tabla');

    /** Vista de cada layout, dentro de inc/frontend/views/. */
    const VISTAS = array(
        'carta' => 'list-personal-metabox.php',
        'lista' => 'list-personal-lista.php',
        'tabla' => 'list-personal-table.php',
    );

    /**
     * Datos del perfil que se muestran si no se configuró nada (lo que mostraba la carta
     * hasta ahora). El nombre se muestra siempre, así que con ningún campo elegido queda
     * solo el nombre.
     */
    const CAMPOS_DEFAULT = array('foto', 'rol', 'grado', 'unidad', 'afiliaciones', 'redes');

    const ORDEN_VALIDOS = array('menu_order-asc', 'title-asc', 'title-desc', 'date-desc', 'date-asc', 'modified-desc', 'modified-asc');

    /**
     * @return array<string,string> campo => etiqueta, en el orden en que se muestran.
     */
    public static function get_campos_labels()
    {
        return array(
            'foto'         => __('Foto', 'personal-block'),
            'rol'          => __('Rol en la unidad', 'personal-block'),
            'grado'        => __('Grado alcanzado', 'personal-block'),
            'unidad'       => __('Unidad de investigación', 'personal-block'),
            'email'        => __('Email', 'personal-block'),
            'telefono'     => __('Teléfono', 'personal-block'),
            'afiliaciones' => __('Afiliaciones', 'personal-block'),
            'redes'        => __('Redes sociales y CV', 'personal-block'),
        );
    }

    /**
     * @param array $args {
     *   @type string     $layout     carta|lista|tabla.
     *   @type int        $columns    Columnas de la carta (1-4).
     *   @type array|null $campos     Datos del perfil a mostrar. null = CAMPOS_DEFAULT; vacío = solo el nombre.
     *   @type int[]      $categories Ids de términos de "categorias". Vacío = todas.
     *   @type string     $orderBy    campo-dirección, ej: title-asc (ver ORDEN_VALIDOS).
     * }
     * @return string HTML
     */
    public function render(array $args = array())
    {
        $args     = $this->normalizar_args($args);
        $personas = $this->query_personas($args);

        if (empty($personas)) {
            return '<p>' . esc_html__('No hay personal para mostrar', 'personal-block') . '</p>';
        }

        ob_start();
        load_template(\Personal\PLUGIN_NAME_DIR . 'inc/frontend/views/' . self::VISTAS[$args['layout']], false, array(
            'personas' => $personas,
            'columns'  => $args['columns'],
            'campos'   => $args['campos'],
        ));

        return ob_get_clean();
    }

    private function normalizar_args(array $args)
    {
        $args = wp_parse_args($args, array(
            'layout'     => 'carta',
            'columns'    => 3,
            'campos'     => null,
            'categories' => array(),
            'orderBy'    => 'date-desc',
        ));

        if (!in_array($args['layout'], self::LAYOUTS, true)) {
            $args['layout'] = 'carta';
        }

        $args['columns'] = max(1, min(4, (int) $args['columns']));

        // Se filtra contra la lista de campos (y en su orden) para descartar valores
        // inventados. Elementor manda '' cuando se vacía el selector: queda solo el nombre.
        $args['campos'] = $args['campos'] === null
            ? self::CAMPOS_DEFAULT
            : array_values(array_intersect(array_keys(self::get_campos_labels()), (array) $args['campos']));

        $args['categories'] = array_filter(array_map('absint', (array) $args['categories']));

        if (!in_array($args['orderBy'], self::ORDEN_VALIDOS, true)) {
            $args['orderBy'] = 'date-desc';
        }

        return $args;
    }

    /**
     * @return array Datos de cada personal (Personal_Model::get_all_personal_data()).
     */
    private function query_personas(array $args)
    {
        list($orderby, $order) = explode('-', $args['orderBy']);

        $query_args = array(
            'post_type'      => 'personal',
            'posts_per_page' => -1,
            'orderby'        => $orderby,
            'order'          => strtoupper($order),
        );

        if (!empty($args['categories'])) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'categorias',
                    'field'    => 'term_id',
                    'terms'    => $args['categories'],
                ),
            );
        }

        $personas = array();
        foreach (get_posts($query_args) as $post) {
            $personas[] = (new Personal_Model($post->ID))->get_all_personal_data();
        }

        return $personas;
    }
}
