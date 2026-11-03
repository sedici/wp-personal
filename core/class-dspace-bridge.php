<?php
namespace Personal\Core;

/**
* Clase adaptadora para interactuar con las diferentes versiones del plugin wp-dspace.
*/
class Dspace_Bridge {

    /**
    * Verifica si alguna de las versiones compatibles de wp-dspace está activa.
    * 
    * @return bool
    */
    public static function is_active() : bool {
        return shortcode_exists( 'get_publications' ) || shortcode_exists( 'dspace_search' );
    }
   
    /**
     * Retorna el tag del shortcode correspondiente según la versión instalada y activa.
     * 
     * @return string
     */
    public static function get_active_wp_dspace_shortcode_tag() : string {
        if ( shortcode_exists( 'get_publications' ) ) {
            return 'get_publications';
        }
        return 'dspace_search'; 
    }
   
    /**
     * Construye y retorna el shortcode formateado para un repositorio y autor dados.
     * 
     * @param string $repo_domain  Dominio/slug del repositorio (sedici, cic, conicet).
     * @param string $author_id    Identificador de autor.
     * @return string              El string del shortcode listo para ejecutar.
     */
    public static function build_wp_dspace_shortcode( string $repo_domain, string $author_id ) : string {
        $tag = self::get_active_wp_dspace_shortcode_tag();
        
        if( self::is_active() === false ) {
            return '';
        }
        else if($tag == 'get_publications') {
            return sprintf(
                '[%s repo="%s" author="%s" group_date="true" show_author="true" date="true" showabstract="false" size="20"]',
                esc_attr( $tag ),
                esc_attr( $repo_domain ),
                esc_attr( $author_id )
            );
        }
        else if($tag == 'dspace_search') {
            return sprintf(
                '[%s repo="%s" author="%s" showabstract="false" size="20"]',
                esc_attr( $tag ),
                esc_attr( $repo_domain ),
                esc_attr( $author_id )
            );
        }
    }
 }
