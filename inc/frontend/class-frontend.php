<?php

namespace Personal\Inc\Frontend;
use Personal\Core\Personal_Model;
/**
 *
 * Carga la vista pública del complemento
 *
 * @author   SEDICI - Ezequiel Manzur - Maria Marta Vila
 */
class Frontend
{
    private $plugin_name;

    private $version;

    private $plugin_text_domain;

    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
    }

    /**
     * Registra las hojas de estilo la la parte pública del sitio
     */
    public function enqueue_styles()
    {

        $style = 'bootstrap';
        if ((!wp_style_is($style, 'queue')) && (!wp_style_is($style, 'done'))) {
            //queue up your bootstrap
            wp_enqueue_style($style, plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        }
        
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/personal-frontend.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/personal-frontend.css'), 'all');
        wp_enqueue_style($this->plugin_name . '-single', plugin_dir_url(__FILE__) . 'css/single-personal.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/single-personal.css'), 'all');
        
        wp_enqueue_style($this->plugin_name . '-list-metabox', plugin_dir_url(__FILE__) . 'css/list-personal-metabox.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/list-personal-metabox.css'), 'all');
        wp_enqueue_style($this->plugin_name . '-list-text', plugin_dir_url(__FILE__) . 'css/list-personal-text.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/list-personal-text.css'), 'all');
        wp_enqueue_style($this->plugin_name . '-list-table', plugin_dir_url(__FILE__) . 'css/list-personal-table.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/list-personal-table.css'), 'all');

    }

    /**
     * Registra las hojas de script la la parte pública del sitio
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/personal-frontend.js', array('jquery'), filemtime(plugin_dir_path(__FILE__) . 'js/personal-frontend.js'), false);
        $script = 'bootstrap';
        if ((!wp_script_is($script, 'queue')) && (!wp_script_is($script, 'done'))) {
            wp_enqueue_script($script, plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array(), $this->version, 'all');
        }
    }

    /**
     * Muestra la vista single del post type personal
    */
    public function show_single_personal_template($content) {
        global $post;
        $cpt_personal = new Personal_Model($post->ID);

        $template_path = plugin_dir_path(__DIR__) . 'frontend/views/single-personal.php';
        
        // Compruebo que post no sea nulo
        if ( ! is_a($post, 'WP_Post') || $post->post_type !== 'personal' ) {
            return $content;
        }
        
        // Obtener todos los datos centralizados
        $personal_data = $cpt_personal->get_all_personal_data();

        ob_start();
        load_template( $template_path, false, [
            'personal'      => $personal_data,
            'redes'         => $personal_data['social_media'],
            'hera_url'      => $personal_data['hera_url'],
            'publicaciones' => $personal_data['publicaciones'],
            'dspace_activo' => shortcode_exists('dspace_search')
        ] );

        return ob_get_clean();

    }


    /**
     * Evita que se muestre la imagen destacada del post personal.
     */
    function wordpress_hide_feature_image($html, $post_id, $post_image_id)
    {
        return (is_single() and get_post_type() == 'personal') ? '' : $html;
    }

    /**
     * Normaliza los atributos parseados y construye los argumentos para WP_Query.
     * 
     * @param  array $parsed_atts Atributos ya procesados con valores por defecto.
     * @return array Argumentos listos para instanciar \WP_Query.
     */
    private function prepare_query_args( array $parsed_atts ) : array {
        
        $args = array(
            'post_type'      => 'personal',
            'posts_per_page' => 50
        );

        if ( ! empty( $parsed_atts['category_id'] ) ) {
            $args['tax_query'] = array(
                array(
                    'terms'    => $parsed_atts['category_id'],
                    'taxonomy' => 'categorias',
                )
            );
        }

        return $args;
    }

    /**
     * Muestra un listado de posts del post type personal
     */
    public function show_list_personal_template($atts = array()) {

        $atts = (array) $atts;

        $parsed_atts = shortcode_atts( array(
            'category_id' => '',
            'title'       => '',
            'columns'     => 3,
            'layout'     => 'metabox',
        ), $atts );

        $args = $this->prepare_query_args( $parsed_atts );

        $loop = new \WP_Query($args);
        $personas = array();

        if ($loop->have_posts()) {
            while ( $loop->have_posts() ) {
                $loop->the_post();
                
                $cpt_personal = new Personal_Model(get_the_ID());
                
                $personas[] = $cpt_personal->get_all_personal_data();

            }
            wp_reset_postdata();
        }

        
        $template_path = plugin_dir_path(__FILE__) . 'views/list-personal-' . $parsed_atts['layout'] . '.php';
        $fallback_path = plugin_dir_path(__FILE__) . 'views/list-personal-metabox.php';
        $template = file_exists($template_path) ? $template_path : $fallback_path;

        ob_start();

        load_template( $template , false, array(
            'personas'      => $personas,
            'columns'    => $parsed_atts['columns'],
            'title'      => $parsed_atts['title'],
        ));

        return ob_get_clean();
                
    }

    /**
     * Elimina el título por defecto del post type personal en la vista single
     */
    public function remove_personal_title( $title, $id ) {
        global $post;
        if ( is_singular( 'personal' )  and $id == get_the_ID() ) return '';

        return $title;
    }
}
