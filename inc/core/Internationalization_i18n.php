<?php

namespace Personal\Inc\Core;

/**
 * Carga y define los archivos de internacionalización para que el plugin esté listo para la traducción.
 *
 * @author  SEDICI - Ezequiel Manzur - Maria Marta Vila
 */
class Internationalization_i18n {

	private $text_domain;

	/**
	 * @param      string $plugin_name       nombre del plugin
	 */
	public function __construct( $plugin_text_domain ) {

		$this->text_domain = $plugin_text_domain;

	}


	/**
	 * Carga el textdomain para las traducciones
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->text_domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
