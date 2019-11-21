<?php

namespace Personal\Inc\Core;

/**
 * Esta clase define todo el código que se ejecutará durante la activación del complemento.
 * @author  SEDICI - Ezequiel Manzur - Maria Marta Vila
 */

class Activator {


	public static function activate() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
         // Si no esta activado el plugin wp-dspace no se puede activar este plugin
        //Fixme evaluar si tiene sentido usar el plugin de personal sin el de wp-dspace
        if ( ! is_plugin_active( 'wp-dspace/Dspace.php' ) ){
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( "Para usar este plugin se requiere el plugin <a href='https://github.com/sedici/wp-dspace/blob/master/wp-dspace.zip'> wp-dspace </a>"  );
        }

	}

}
