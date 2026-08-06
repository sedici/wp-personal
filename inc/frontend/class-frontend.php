<?php

namespace Personal\Inc\Frontend;
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
        // Versionamos con filemtime para que el navegador no sirva assets
        // cacheados después de un cambio.
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/personal-frontend.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/personal-frontend.css'), 'all');

        wp_enqueue_style($this->plugin_name . '-single', plugin_dir_url(__FILE__) . 'css/single-personal.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/single-personal.css'), 'all');

        wp_enqueue_style('list-' . $this->plugin_name, plugin_dir_url(__FILE__) . 'css/list-personal.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/list-personal.css'), 'all');

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
     * @param string $name nombre del campo meta a obtener
     * @return retorna el valor del campo meta
     */
    private function the_personal_meta($name)
    {
        return get_post_meta(get_the_ID(), $name, true);
    }

     /**
     * Filtra y formatea las redes sociales activas a partir de los metadatos.
    * 
    * @param  array $social_media Array asociativo con las redes de get_personal_social_media.
    * @return array               Array de redes activas con claves: url, img y alt.
    */
    private function get_active_social_media($social_media) {
        $assets_url = \Personal\PLUGIN_NAME_URL . 'assets/images/';
    
        $redes_config = array(
            'google_scholar' => array( 'img' => 'google_scholar.png', 'alt' => 'Google Scholar' ),
            'research-gate'  => array( 'img' => 'research-gate.png',  'alt' => 'ResearchGate' ),
            'orcid'          => array( 'img' => 'orcid.png',          'alt' => 'ORCID' ),
            'linkedin'       => array( 'img' => 'linkedin.png',       'alt' => 'LinkedIn' ),
            'facebook'       => array( 'img' => 'facebook.png',       'alt' => 'Facebook' ),
            'twitter'        => array( 'img' => 'twitter.png',        'alt' => 'Twitter' ),
            'instagram'      => array( 'img' => 'instagram.png',      'alt' => 'Instagram' ),
        );

        $redes_activas = array();
        foreach ( $social_media as $key => $url_perfil ) {
            if ( ! empty( $url_perfil ) && isset( $redes_config[$key] ) ) {
                $redes_activas[] = array(
                    'url' => $url_perfil,
                    'img' => $assets_url . $redes_config[$key]['img'],
                    'alt' => $redes_config[$key]['alt']
                );
            }
        }

        return $redes_activas;

    }

    /**
     * Obtiene los shortcodes para las publicaciones de los repositorios configurados.
    * 
    * @param  int $post_id ID del post personal.
    * @return array        Listado de shortcodes formateados con label y shortcode.
    */
    private function get_publications_shortcodes( $post_id ) {
        $repos_fijos = array(
            'sedici'  => array( 'label' => 'SEDICI',  'domain' => 'sedici' ),
            'cic'     => array( 'label' => 'CIC',     'domain' => 'cic-digital' ),
            'conicet' => array( 'label' => 'CONICET', 'domain' => 'conicet' ),
        );

        $publicaciones_shortcodes = array();
        foreach ( $repos_fijos as $meta_key => $repo ) {
            $author_id = get_post_meta( $post_id, $meta_key, true );
            if ( ! empty( $author_id ) ) {
                $publicaciones_shortcodes[] = array(
                    'label' => sprintf( "Producción científica en %s", $repo['label'] ),
                    'shortcode' => sprintf(
                        '[dspace_search repo="%s" author="%s" showabstract="false" size="20"]',
                        esc_attr( $repo['domain'] ),
                        esc_attr( $author_id )
                    )
                );
            }
        }

        return $publicaciones_shortcodes;
    }

    /**
     * Genera la URL del reporte de HERA si está habilitado y posee un ORCID válido.
    * 
    * @param  int $post_id ID del post personal.
    * @return string       URL del reporte de HERA o string vacío si no aplica.
    */
    private function get_hera_url($post_id) {
        if ( get_post_meta($post_id, 'hera_enabled', true) === '1' ) {
            $orcid_link = get_post_meta($post_id, 'orcid', true);
            if ( ! empty($orcid_link) && preg_match('/(\d{4}-\d{4}-\d{4}-\d{3}[\dXx])/', $orcid_link, $matches) ) {
                return 'https://hera.sedici.unlp.edu.ar/?orcid=' . $matches[1];
            }
        }
        return '';
    }

    /**
    * Construye un array completo con la información personal para la vista individual.
    * 
    * @param  int $post_id ID del post personal.
    * @return array        Array asociativo con la información detallada del personal.
    */
    public function build_single_personal_data( $post_id ) : array {
        $assets_url = \Personal\PLUGIN_NAME_URL . 'assets/images/';
        return array(
            'nombre'                  => get_post_field( 'post_title', $post_id ),
            'imagen_perfil'           => get_the_post_thumbnail_url($post_id, 'medium') ?: $assets_url . 'blank-profile.png',
            'email'                   => get_post_meta($post_id, 'email', true),
            'telefono'                => get_post_meta($post_id, 'telefono', true),
            'unidad_de_investigacion' => get_post_meta($post_id, 'unidad_de_investigacion', true),
            'rol'                     => get_post_meta($post_id, 'rol_unidad_de_investigacion', true),
            'grado_alcanzado'         => get_post_meta($post_id, 'grado_alcanzado', true),
            'biografia'               => get_post_meta($post_id, 'biografia', true),
            'categorias'              => wp_get_post_terms($post_id, 'categorias', array("personal")),
            'lineas_investigacion'    => wp_get_post_terms($post_id, 'lineas_de_investigacion', array("personal")),
        );
    }


    /**
     * Muestra la vista single del post type personal
     */
    public function show_single_personal_template($content) {
        global $post;

        $assets_url = \Personal\PLUGIN_NAME_URL . 'assets/images/';
        $template_path = plugin_dir_path(__DIR__) . 'frontend/views/single-personal.php';
        
        // Compruebo que post no sea nulo
        if ( ! is_a($post, 'WP_Post') || $post->post_type !== 'personal' ) {
            return $content;
        }
        
        $social_media = $this->get_personal_social_media($post->ID);
        $redes_activas = $this->get_active_social_media($social_media);
        
        $cv_url = $this->get_personal_cv_url($post->ID);
        if ( ! empty($cv_url) ) {
            $redes_activas[] = array(
                'url' => $cv_url,
                'img' => $assets_url . 'cv.png',
                'alt' => 'Curriculum Vitae'
            );
        }

        ob_start();
        load_template( $template_path, false, [
            'personal'      => $this->build_single_personal_data($post->ID),
            'redes'         => $redes_activas,
            'hera_url'      => $this->get_hera_url($post->ID),
            'publicaciones' => $this->get_publications_shortcodes($post->ID),
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
    * Normaliza los atributos del shortcode y construye los argumentos para WP_Query.
    * @param  array $atts Atributos crudos pasados por el usuario en el shortcode.
    * @return array Argumentos listos para instanciar \WP_Query.
    */
    private function prepare_query_args( array $atts ) : array {
        
        $parsed_atts = shortcode_atts( array(
            'category_id' => '',
            'title'       => '',
            'columns'     => 3,
        ), $atts );

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
     * Obtiene los enlaces a redes sociales de un post del tipo personal.
     * @param  int $post_id ID del post personal.
     * @return array        Array asociativo con los enlaces a redes sociales.
     */
    public function get_personal_social_media( $post_id ) {
        $social_media = array(
            'google_scholar' => get_post_meta($post_id, "google_scholar", true),
            'research-gate' => get_post_meta($post_id, "researchgate", true),
            'orcid' => get_post_meta($post_id, "orcid", true),
            'linkedin' => get_post_meta($post_id, "linkedin", true),
            'facebook' => get_post_meta($post_id, "facebook", true),
            'twitter' => get_post_meta($post_id, "twitter", true),
            'instagram' => get_post_meta($post_id, "instagram", true),
        );

        return $social_media;
    }

    public function get_personal_cv_url( $post_id ) {
        $cv = get_post_meta($post_id, 'curriculum_vitae', true);
        if (!empty($cv) && isset($cv['url'])) {
            return $cv['url'];
        }
        return '';
    }

    /**
     * Retorno información de un post del tipo personal en un array asociativo.
     * @param  int $post_id ID del post personal.
     * @return array        Array asociativo con la información del post personal.
     */
    public function build_personal_data( $post_id ) {
        $image = get_the_post_thumbnail_url($post_id, 'medium');
        $terms = get_the_terms( $post_id, 'categorias' );
        $social_media = $this->get_personal_social_media($post_id);
        $cv = $this->get_personal_cv_url($post_id);
        $social_media['cv'] = $cv;

        return array(
            'id'              => $post_id,
            'permalink'       => get_permalink($post_id),
            'title'           => get_the_title(),
            'image'           => !empty($image) ? $image : plugins_url() . "/wp-personal/assets/images/blank-profile.png",
            'grado_alcanzado' => get_post_meta($post_id, 'grado_alcanzado', true),
            'rol'             => !empty($terms) ? $terms[0]->name : '',
            'unidad'          => get_post_meta($post_id, 'unidad_de_investigacion', true),
            'social_media'    => $social_media,
        );
    }

    /**
     * Muestra un listado de posts del post type personal
     */
    public function show_list_personal_template($atts = array()) {

        // Se preparan los argumentos para WP_Query a partir de los atributos del shortcode
        $args = $this->prepare_query_args( $atts );

        $loop = new \WP_Query($args);
        $personas = array();

        if ($loop->have_posts()) {
            while ( $loop->have_posts() ) {
                $loop->the_post();
                
                $post_id = get_the_ID();
                
                $personas[] = $this->build_personal_data($post_id);

            }
            wp_reset_postdata();
        }

        $template_path = plugin_dir_path(__FILE__) . 'views/list-personal.php';

        ob_start();

        load_template( $template_path, false, array(
            'personas'      => $personas,
            'columns'    => $atts['columns'],
            'title'      => $atts['title'],
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
