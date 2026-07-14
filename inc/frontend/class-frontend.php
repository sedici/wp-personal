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

    public  $repositories;
    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->repositories = array();
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
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/personal-frontend.css', array(), $this->version, 'all');
        
        wp_enqueue_style($this->plugin_name . '-single', plugin_dir_url(__FILE__) . 'css/single-personal.css', array(), $this->version, 'all');

    }

    /**
     * Registra las hojas de script la la parte pública del sitio
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/personal-frontend.js', array('jquery'), $this->version, false);
        $script = 'bootstrap';
        if ((!wp_script_is($script, 'queue')) && (!wp_script_is($script, 'done'))) {
            wp_enqueue_script($script, plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array(), $this->version, 'all');
        }
    }
    private function getRepositories()
    {
        return array_filter(
            $this->repositories,
            function ($repo)  {
                return !(strtolower($repo['name']) == 'sedici' or strtolower($repo['name']) == 'conicet' or strtolower($repo['name']) == 'cic'  );
            }
        );

    }

    /**
     * @param string $name nombre del campo meta a obtener
     * @return retorna el valor del campo meta
     */
    private function the_personal_meta($name)
    {
        return get_post_meta(get_the_ID(), $name, true);
    }

    // /**
    //  *  Remplaza el contenido del post para la vista publica.
    //  *
    //  */
    // public function single_personal_template($content)
    // {   

    //     global $post;

    //     // Compruebo que $post no sea nulo
    //     if ( ! is_a($post, 'WP_Post') ) {
    //         return $content;
    //     }

    //     if ($post->post_type == 'personal') {
    //         if (file_exists(plugin_dir_path(__DIR__) . 'frontend/views/single-personal.php')) {
    //             ob_start();
    //             include(plugin_dir_path(__DIR__) . 'frontend/views/single-personal.php');
    //             $content = ob_get_clean();
    //             return $content;
    //         }
    //     }

    //     return $content;

    // }

    public function single_personal_template($content) {
        global $post;

        $assets_url = plugins_url() . '/wp-personal/assets/images/';

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

        // 2. Preparación de Repositorios (Unificación de fijos y dinámicos)
        $repositorios_activos = [];
        $repos = ['sedici' => 'SEDICI', 'cic' => 'CIC', 'conicet' => 'CONICET'];

        foreach ( $repos as $slug => $label ) {
            $author_id = $this->the_personal_meta($slug);
            if ( ! empty($author_id) ) {
                $repositorios_activos[] = [
                'slug' => $slug,
                'label' => "Producción científica en {$label}",
                'author' => $author_id
                ];

            }
        }

        $publicaciones_shortcodes = [];
        $repos_fijos = ['sedici' => 'SEDICI', 'cic' => 'CIC', 'conicet' => 'CONICET'];
        foreach ( $repos_fijos as $slug => $label ) {
            $author_id = $this->the_personal_meta($slug);
            if ( ! empty($author_id) ) {
                $publicaciones_shortcodes[] = [
                    'label' => "Producción científica en {$label}",
                    'shortcode' => sprintf(
                    '[get_publications config="%s" author="%s" show_subtype=false show_author=true group_date=true date=true max_results="1000"]',
                    esc_attr( $slug ),
                    esc_attr( $author_id )
                    )
                ];
            }
        }

        // 3. Imagen Destacada o Fallback
        $thumb = get_the_post_thumbnail_url($post->ID, 'medium');
        $imagen_perfil = !empty($thumb) ? $thumb : $assets_url . 'blank-profile.png';

        // 4. Renderizado limpio mediante buffer
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
            'repositorios' => $repositorios_activos,
            'publicaciones' => $publicaciones_shortcodes
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


    public function get_repositories_wpdspace($value){
        $this->repositories= $value;
        return $value;
    }

    public function register_shortcodes()
    {
        add_shortcode('list-personal', array($this, 'list_personal'));

    }

    public function list_personal($atts = array())
    {
        $atts = shortcode_atts(array(
            'category_id' => '',
            'title' => '',
            'columns' => 3,
        ), $atts);

        ob_start();
        include('views/list-personal.php');
        $content = ob_get_clean();
        return $content;
    }

    public function remove_personal_title( $title, $id ) {
        global $post;
        if ( is_singular( 'personal' )  and $id == get_the_ID() ) return '';

        return $title;
    }
}
