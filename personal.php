<?php	
/*	
* Plugin Name: Personal-Plugin	
* Plugin URI: https://github.com/sedici/wp-personal
* Description: 	
* Version: 1.0	
* Author: SEDICI - Ezequiel Manzur - Maria Marta Vila
* Author URI: http://sedici.unlp.edu.ar/	
* Text Domain:   
* Copyright (c) 2015 SEDICI UNLP, http://sedici.unlp.edu.ar
* Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.	
*/

namespace Personal;

// Aborta si ingresa directamente
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Constants
 */

define( __NAMESPACE__ . '\NP', __NAMESPACE__ . '\\' );

define( NP . 'PLUGIN_NAME', 'Personal plugin' );

define( NP . 'PLUGIN_VERSION', '1.0.0' );

define( NP . 'PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );

define( NP . 'PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );

define( NP . 'PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( NP . 'PLUGIN_TEXT_DOMAIN', 'personal' );

/**
 * Autoload de Clases
 */

require_once( PLUGIN_NAME_DIR . 'inc/libraries/autoloader.php' );

/**
 * Se registran los hook para cuando se activa o se desactiva el plugin
 */
register_activation_hook( __FILE__, array( NP . 'Inc\Core\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( NP . 'Inc\Core\Deactivator', 'deactivate' ) );


/**
 * Plugin Singleton Container
 */
class Personal {

	static $init;
	/**
	 * Inicia el plugin
	 *
	 */
	public static function init() {

		if ( null == self::$init ) {
			self::$init = new Inc\Core\Init();
			self::$init->run();
		}

		return self::$init;
	}

}
/*
 * Comienza la ejecución del plugin.
 */
function personal_init() {
    return Personal::init();
}


//Fixme Evaluar si se necesita una version de php minima
personal_init();
/*$min_php = '5.6.0';
if ( version_compare( PHP_VERSION, $min_php, '>=' ) ) {
		personal_init();
}*/
