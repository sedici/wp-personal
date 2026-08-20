<?php
namespace Personal\Core;

class CPT_personal
{
    public function __construct(){}

    /**
     * Registra el Post Type Personal
     * @return void
     */
    public function cptui_register_my_cpts_personal()
    {
        /**
         * Post Type: Personal.
         */
        $labels = array(
            "name" => __("Personal", ""),
            "singular_name" => __("Persona", ""),
            "menu_name" => __("Personal", ""),
            "all_items" => __("Todo el Personal", ""),
            "add_new" => __("Agregar Personal", ""),
            "add_new_item" => __("Agregar nuevo Personal", ""),
            "edit_item" => __("Editar Personal", ""),
            "new_item" => __("Nuevo Personal", ""),
            "view_item" => __("Ver Personal", ""),
            "view_items" => __("Ver Personal", ""),
            "search_items" => __("Buscar Personal", ""),
            "not_found" => __("No se encontro el Personal", ""),
            "not_found_in_trash" => __("No se encontro el Personal en la papelera", ""),
            "parent_item_colon" => __("Personal Padre", ""),
            "featured_image" => __("Foto del Personal", ""),
            "set_featured_image" => __("Seleccionar la imagen", ""),
            "remove_featured_image" => __("Remover la imagen", ""),
            "use_featured_image" => __("Utilizar la imagen", ""),
            "archives" => __("Archivar al personal", ""),
            "insert_into_item" => __("Insert en Personal", ""),
            "uploaded_to_this_item" => __("Subir al personal", ""),
            "filter_items_list" => __("Filtrar lista de personal", ""),
            "items_list_navigation" => __("Navegación de la lista de personal", ""),
            "items_list" => __("Lista de Personal", ""),
            "attributes" => __("Atributos del Personal", ""),
            "parent_item_colon" => __("Personal Padre", ""),
        );

        $args = array(
            "label" => __("Personal", ""),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "has_archive" => "personal",
            "show_in_menu" => true,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "capabilities" => array(
                'create_posts' => 'create_personal',
                'delete_others_posts' => 'delete_others_personales',
                'delete_private_posts' => 'delete_private_personales',
                'delete_published_posts' => 'delete_published_personales',
                'edit_private_posts' => 'edit_private_personales',
                'edit_published_posts' => 'edit_published_personales',
                'edit_post' => 'edit_personal',
                'edit_posts' => 'edit_personales',
                'edit_others_posts' => 'edit_other_personales',
                'publish_posts' => 'publish_personales',
                'read_post' => 'read_personal',
                'read_private_posts' => 'read_private_personales',
                'delete_post' => 'delete_personal'
            ),
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array("slug" => "personal", "with_front" => true),
            "query_var" => true,
            "supports" => array("title", "thumbnail", "page-attributes"),
            "taxonomies" => array("categorias"),
        );

        register_post_type("personal", $args);
    }


    /**
     * Registra la taxonomia Lineas de investigacion para el Post Type Personal
     * @return void
     */
    public function cptui_register_my_taxes_lineas_de_investigacion()
    {
        /**
         * Taxonomy: Lineas de investigacion.
         */

        $labels = array(
            "name" => __("Lineas de investigacion", ""),
            "singular_name" => __("Linea de investigacion", ""),
        );

        $args = array(
            "label" => __("Lineas de investigacion", ""),
            "labels" => $labels,
            "public" => true,
            "hierarchical" => true,
            "label" => "Lineas de investigacion",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array('slug' => 'lineas_de_investigacion', 'with_front' => true, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "show_in_quick_edit" => true,
        );
        register_taxonomy("lineas_de_investigacion", array("personal"), $args);
    }

    /**
     * Registra las taxonomias para el Post Type personal
     */
    public function cptui_register_my_taxes_categorias()
    {
        /**
         * Taxonomy: Categorias.
         */

        $labels = array(
            "name" => __("Categorias", ""),
            "singular_name" => __("Categoria", ""),
        );

        $args = array(
            "label" => __("Categorias", ""),
            "labels" => $labels,
            "public" => true,
            "hierarchical" => true,
            "label" => "Categorias",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array('slug' => 'categorias', 'with_front' => true, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "show_in_quick_edit" => true,
        );
        register_taxonomy("categorias", array("personal"), $args);
    }

    /**
     * Agrega las capabilites para editar el custom post.
     */
    public function add_personal_caps()
    {
        // gets the administrator role
        // FIXME Evaluar si agregar un rol personal.
        $admins = get_role('administrator');
        $admins->add_cap('create_personal');
        $admins->add_cap('delete_private_personales');
        $admins->add_cap('delete_others_personales');
        $admins->add_cap('delete_published_personales');
        $admins->add_cap('edit_published_personales');
        $admins->add_cap('edit_personal');
        $admins->add_cap('edit_personales');
        $admins->add_cap('publish_personales');
        $admins->add_cap('read_personal');
        $admins->add_cap('delete_personal');
        $admins->add_cap('edit_private_personales');
        $admins->add_cap('edit_other_personales');
        $admins->add_cap('read_private_personales');

    }

    public function get_email($post_id) {
        return get_post_meta($post_id, 'email', true);
    }

    public function get_telefono($post_id) {
        return get_post_meta($post_id, 'telefono', true);
    }

    public function get_unidad_investigacion($post_id) { 
        return get_post_meta($post_id, 'unidad_de_investigacion', true); 
    }

    public function get_grado_alcanzado($post_id) { 
        return get_post_meta($post_id, 'grado_alcanzado', true); 
    }

    public function is_hera_enabled($post_id) {
        return get_post_meta($post_id, 'hera_enabled', true) === '1';
    }

    /**
     * Genera la URL del reporte de HERA si está habilitado y posee un ORCID válido.
    * 
    * @param  int $post_id ID del post personal.
    * @return string       URL del reporte de HERA o string vacío si no aplica.
    */
    public function get_hera_url($post_id) {
        if ( get_post_meta($post_id, 'hera_enabled', true) === '1' ) {
            $orcid_link = get_post_meta($post_id, 'orcid', true);
            if ( ! empty($orcid_link) && preg_match('/(\d{4}-\d{4}-\d{4}-\d{3}[\dXx])/', $orcid_link, $matches) ) {
                return 'https://hera.sedici.unlp.edu.ar/?orcid=' . $matches[1];
            }
        }
        return '';
    }

    /* Getter social media */

    public function get_google_scholar($post_id) { 
        return get_post_meta($post_id, 'google_scholar', true); 
    }

    public function get_orcid($post_id) { 
        return get_post_meta($post_id, 'orcid', true); 
    }

    public function get_research_gate($post_id) { 
        return get_post_meta($post_id, 'researchgate', true); 
    }

    public function get_linkedin($post_id) { 
        return get_post_meta($post_id, 'linkedin', true); 
    }

    public function get_facebook($post_id) { 
        return get_post_meta($post_id, 'facebook', true); 
    }

    public function get_instagram($post_id) { 
        return get_post_meta($post_id, 'instagram', true); 
    }

    public function get_twitter($post_id) { 
        return get_post_meta($post_id, 'X', true); 
    }

    public function get_biografia($post_id) { 
        return get_post_meta($post_id, 'biografia', true); 
    }

    public function get_personal_cv_url( $post_id ) {
        $cv = get_post_meta($post_id, 'curriculum_vitae', true);
        if (!empty($cv) && isset($cv['url'])) {
            return $cv['url'];
        }
        return '';
    }

    public function get_categorias($post_id) { 
        return get_the_terms($post_id, 'categorias'); 
    }

    public function get_lineas_investigacion($post_id) { 
        return get_the_terms($post_id, 'lineas_de_investigacion'); 
    }

    public function get_orden($post_id) { 
        return get_post_field('menu_order', $post_id); 
    }

    public function get_imagen_destacada_url($post_id, $size = 'medium') { 
        return get_the_post_thumbnail_url($post_id, $size); 
    }

    /**
     * Obtiene y retorna los terminos de la taxonomia categorias asociados al menos a un post del post type personal 
     */
    public function get_personal_terms()
    {

        $post_type_name = 'personal';

        $args = array(
            'post_type' => $post_type_name,
            'posts_per_page' => -1,
        );

        $query = new \WP_Query($args);

        // Si ningun post tenia una categoria asociada, $terms_name_array quedará vacio
        $terms_name_array = array();

        // Guardo en $terms_name_array los terminos de los posts de personal
        if ($query->have_posts()) {

            while ($query->have_posts()) {

                $query->the_post();

                // Obtiene los terminos del post asociado
                $terms = get_the_terms(get_the_ID(), 'categorias');

                if ($terms && !is_wp_error($terms)) {

                    foreach ($terms as $term) {

                        //Evito guardar terminos repetidos
                        if (!in_array($term->name, $terms_name_array)) {
                            array_push($terms_name_array, $term->name);
                        }
                    }

                }

            }

            // Si ningun post tenia una categoria asociada, $terms_array quedará vacio
            $terms_array = array();

            if (!empty($terms_name_array)) {

                foreach ($terms_name_array as $term_name) {
                    $terms_array[] = get_term_by('name', $term_name, 'categorias');
                }
            }

            wp_reset_postdata();
        }

        return $terms_array;
    }

    /**
     * Filtra y formatea las redes sociales activas a partir de los metadatos.
    * 
    * @param  array $social_media Array asociativo con las redes de get_personal_social_media.
    * @return array               Array de redes activas con claves: url, img y alt.
    */
    public function get_active_social_media($social_media) {
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

    /**
     * Obtiene los shortcodes para las publicaciones de los repositorios configurados.
    * 
    * @param  int $post_id ID del post personal.
    * @return array        Listado de shortcodes formateados con label y shortcode.
    */
    public function get_publications_shortcodes( $post_id ) {
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
      * Retorna un array consolidado con todos los datos del personal.
      * 
      * @param  int $post_id ID del post personal.
      * @return array        Array asociativo con la información completa del personal.
      */
     public function get_all_personal_data( $post_id ) : array {
        $assets_url = \Personal\PLUGIN_NAME_URL . 'assets/images/';
            
        // Obtener datos sociales crudos y procesarlos
        $social_media_raw = $this->get_personal_social_media($post_id);
        $redes_activas    = $this->get_active_social_media($social_media_raw);
        
        // Agregar el CV a las redes activas si existe
        $cv_url = $this->get_personal_cv_url($post_id);
        if ( ! empty($cv_url) ) {
            $redes_activas[] = array(
                'url' => $cv_url,
                'img' => $assets_url . 'cv.png',
                'alt' => 'Curriculum Vitae'
            );
        }

        return array(
            'id'              => $post_id,
            'title'          => get_the_title($post_id),
            'permalink'       => get_permalink($post_id),
            'image'   => $this->get_imagen_destacada_url($post_id, 'medium') ?: $assets_url . 'blank-profile.png',
            
            'email'           => $this->get_email($post_id),
            'telefono'        => $this->get_telefono($post_id),
            
            'unidad'          => $this->get_unidad_investigacion($post_id),
            'rol'             => $this->get_grado_alcanzado($post_id), // Ajustar si es necesario
            'grado_alcanzado' => $this->get_grado_alcanzado($post_id),
            'biografia'       => $this->get_biografia($post_id),
            
            'categorias'           => $this->get_categorias($post_id),
            'lineas_investigacion' => $this->get_lineas_investigacion($post_id),
            
            'social_media'    => $redes_activas,
            
            'hera_url'        => $this->get_hera_url($post_id),
            'publicaciones'   => $this->get_publications_shortcodes($post_id),
        );
    }
}