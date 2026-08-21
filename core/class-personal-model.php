<?php
namespace Personal\Core;

/**
 * Clase que representa el modelo de datos de un post del tipo personal.
 * Proporciona métodos para acceder a los metadatos y campos personalizados asociados a un post del tipo personal.
 */
class Personal_Model {

    private $post_id;
    
    public function __construct($post_id) {
        $this->post_id = $post_id;
    }

    public function get_email() {
        return get_post_meta($this->post_id, 'email', true);
    }

    public function get_telefono() {
        return get_post_meta($this->post_id, 'telefono', true);
    }

    public function get_unidad_investigacion() { 
        return get_post_meta($this->post_id, 'unidad_de_investigacion', true); 
    }

    public function get_grado_alcanzado() { 
        return get_post_meta($this->post_id, 'grado_alcanzado', true); 
    }

    public function is_hera_enabled() {
        return get_post_meta($this->post_id, 'hera_enabled', true) === '1';
    }

    /**
     * Genera la URL del reporte de HERA si está habilitado y posee un ORCID válido.
    * 
    * @return string       URL del reporte de HERA o string vacío si no aplica.
    */
    public function get_hera_url() {
        if ( get_post_meta($this->post_id, 'hera_enabled', true) === '1' ) {
            $orcid_link = get_post_meta($this->post_id, 'orcid', true);
            if ( ! empty($orcid_link) && preg_match('/(\d{4}-\d{4}-\d{4}-\d{3}[\dXx])/', $orcid_link, $matches) ) {
                return 'https://hera.sedici.unlp.edu.ar/?orcid=' . $matches[1];
            }
        }
        return '';
    }

    /* Getters social media */

    public function get_google_scholar() { 
        return get_post_meta($this->post_id, 'google_scholar', true); 
    }

    public function get_orcid() { 
        return get_post_meta($this->post_id, 'orcid', true); 
    }

    public function get_research_gate() { 
        return get_post_meta($this->post_id, 'researchgate', true); 
    }

    public function get_linkedin() { 
        return get_post_meta($this->post_id, 'linkedin', true); 
    }

    public function get_facebook() { 
        return get_post_meta($this->post_id, 'facebook', true); 
    }

    public function get_instagram() { 
        return get_post_meta($this->post_id, 'instagram', true); 
    }

    public function get_twitter() { 
        return get_post_meta($this->post_id, 'X', true); 
    }

    public function get_biografia() { 
        return get_post_meta($this->post_id, 'biografia', true); 
    }

    public function get_personal_cv_url( ) {
        $cv = get_post_meta($this->post_id, 'curriculum_vitae', true);
        if (!empty($cv) && isset($cv['url'])) {
            return $cv['url'];
        }
        return '';
    }

    public function get_categorias() { 
        return get_the_terms($this->post_id, 'categorias'); 
    }

    public function get_lineas_investigacion() { 
        return get_the_terms($this->post_id, 'lineas_de_investigacion'); 
    }

    public function get_orden() { 
        return get_post_field('menu_order', $this->post_id); 
    }

    public function get_imagen_destacada_url($size = 'medium') { 
        return get_the_post_thumbnail_url($this->post_id, $size); 
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
     * @return array        Array asociativo con los enlaces a redes sociales.
     */
    public function get_personal_social_media() {
        $social_media = array(
            'google_scholar' => get_post_meta($this->post_id, "google_scholar", true),
            'research-gate' => get_post_meta($this->post_id, "researchgate", true),
            'orcid' => get_post_meta($this->post_id, "orcid", true),
            'linkedin' => get_post_meta($this->post_id, "linkedin", true),
            'facebook' => get_post_meta($this->post_id, "facebook", true),
            'twitter' => get_post_meta($this->post_id, "twitter", true),
            'instagram' => get_post_meta($this->post_id, "instagram", true),
        );

        return $social_media;
    }

    /**
     * Obtiene los shortcodes para las publicaciones de los repositorios configurados.
    * 
    * @return array        Listado de shortcodes formateados con label y shortcode.
    */
    public function get_publications_shortcodes() {
        $repos_fijos = array(
            'sedici'  => array( 'label' => 'SEDICI',  'domain' => 'sedici' ),
            'cic'     => array( 'label' => 'CIC',     'domain' => 'cic-digital' ),
            'conicet' => array( 'label' => 'CONICET', 'domain' => 'conicet' ),
        );

        $publicaciones_shortcodes = array();
        foreach ( $repos_fijos as $meta_key => $repo ) {
            $author_id = get_post_meta( $this->post_id, $meta_key, true );
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
      * @return array        Array asociativo con la información completa del personal.
      */
     public function get_all_personal_data() : array {
        $assets_url = \Personal\PLUGIN_NAME_URL . 'assets/images/';
            
        // Obtener datos sociales crudos y procesarlos
        $social_media_raw = $this->get_personal_social_media();
        $redes_activas    = $this->get_active_social_media($social_media_raw);
        
        // Agregar el CV a las redes activas si existe
        $cv_url = $this->get_personal_cv_url();
        if ( ! empty($cv_url) ) {
            $redes_activas[] = array(
                'url' => $cv_url,
                'img' => $assets_url . 'cv.png',
                'alt' => 'Curriculum Vitae'
            );
        }

        return array(
            'id'              => $this->post_id,
            'title'          => get_the_title($this->post_id),
            'permalink'       => get_permalink($this->post_id),
            'image'   => $this->get_imagen_destacada_url('medium') ?: $assets_url . 'blank-profile.png',
            
            'email'           => $this->get_email(),
            'telefono'        => $this->get_telefono(),
            
            'unidad'          => $this->get_unidad_investigacion(),
            'grado_alcanzado' => $this->get_grado_alcanzado(),
            'biografia'       => $this->get_biografia(),
            
            'categorias'           => $this->get_categorias(),
            'lineas_investigacion' => $this->get_lineas_investigacion(),
            
            'social_media'    => $redes_activas,
            
            'hera_url'        => $this->get_hera_url(),
            'publicaciones'   => $this->get_publications_shortcodes(),
        );
    }
}

?>