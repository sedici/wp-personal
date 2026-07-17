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

        // 1. Preparación de Redes Sociales (Mapeo dinámico)

        $redes_mapeo = [
        'google_scholar' => ['img' => 'google_scholar.png', 'alt' => 'Google Scholar'],
        'researchgate' => ['img' => 'research-gate.png', 'alt' => 'ResearchGate'],
        'orcid' => ['img' => 'orcid.gif', 'alt' => 'ORCID'],
        'linkedin' => ['img' => 'linkedin.png', 'alt' => 'LinkedIn'],
        'facebook' => ['img' => 'facebook.jpg', 'alt' => 'Facebook'],
        'twitter' => ['img' => 'twitter.png', 'alt' => 'Twitter'],
        'instagram' => ['img' => 'instagram.png', 'alt' => 'Instagram'],
        ];

        $redes_activas = [];
        foreach ( $redes_mapeo as $meta_key => $info ) {
            $url_perfil = $this->the_personal_meta($meta_key);
            
            if ( ! empty($url_perfil) ) {
                $redes_activas[] = [
                'url' => $url_perfil,
                'img' => $assets_url . $info['img'],
                'alt' => $info['alt']
                ];
            }
        }

        // Inyección del CV si existe

        $cv = $this->the_personal_meta('curriculum_vitae');
        if ( ! empty($cv) && isset($cv['url']) ) {
            $redes_activas[] = [
            'url' => $cv['url'],
            'img' => $assets_url . 'cv.png',
            'alt' => 'Curriculum Vitae'
            ];
        }

        // 2. Preparación de Publicaciones: cada campo meta guarda el nombre de
        // autor de la persona en ese repositorio, y las publicaciones las
        // resuelve el shortcode [dspace_search] del plugin wp-dspace-v2.
        // La clave es el campo meta histórico; 'domain' es el slug con el que
        // el repositorio está registrado en wp-dspace-v2.
        $repos_fijos = [
            'sedici'  => ['label' => 'SEDICI',  'domain' => 'sedici'],
            'cic'     => ['label' => 'CIC',     'domain' => 'cic-digital'],
            'conicet' => ['label' => 'CONICET', 'domain' => 'conicet'],
        ];

        $publicaciones_shortcodes = [];
        foreach ( $repos_fijos as $meta_key => $repo ) {
            $author_id = $this->the_personal_meta($meta_key);
            if ( ! empty($author_id) ) {
                $publicaciones_shortcodes[] = [
                    'label' => "Producción científica en {$repo['label']}",
                    'shortcode' => sprintf(
                    '[dspace_search repo="%s" author="%s" showabstract="false" size="20"]',
                    esc_attr( $repo['domain'] ),
                    esc_attr( $author_id )
                    )
                ];
            }
        }

        // 3. Enlace al reporte de HERA: se extrae el identificador ORCID de la
        // URL completa guardada en el campo meta (https://orcid.org/xxxx-...).
        $hera_url = '';
        if ( $this->the_personal_meta('hera_enabled') === '1' ) {
            $orcid_link = $this->the_personal_meta('orcid');
            if ( ! empty($orcid_link) && preg_match('/(\d{4}-\d{4}-\d{4}-\d{3}[\dXx])/', $orcid_link, $matches) ) {
                $hera_url = 'https://hera.sedici.unlp.edu.ar/?orcid=' . $matches[1];
            }
        }

        // 4. Imagen Destacada o Fallback
        $thumb = get_the_post_thumbnail_url($post->ID, 'medium');
        $imagen_perfil = !empty($thumb) ? $thumb : $assets_url . 'blank-profile.png';

        // 5. Renderizado limpio mediante buffer
        ob_start();
        load_template( $template_path, false, [
            'nombre' => $post->post_title,
            'imagen_perfil' => $imagen_perfil,
            'email' => $this->the_personal_meta('email'),
            'telefono' => $this->the_personal_meta('telefono'),
            'unidad_de_investigacion' => $this->the_personal_meta('unidad_de_investigacion'),
            'rol' => $this->the_personal_meta('rol_unidad_de_investigacion'),
            'grado_alcanzado' => $this->the_personal_meta('grado_alcanzado'),
            'biografia' => $this->the_personal_meta('biografia'),
            'categorias' => wp_get_post_terms($post->ID, 'categorias', ["personal"]),
            'lineas_investigación' => wp_get_post_terms($post->ID, 'lineas_de_investigacion', ["personal"]),
            'redes' => $redes_activas,
            'hera_url' => $hera_url,
            'publicaciones' => $publicaciones_shortcodes,
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
