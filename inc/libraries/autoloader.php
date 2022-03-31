<?php
/**
 *
 * Autoload de clases
 * @param string $class_name Clase a cargar
 *
 */



spl_autoload_register( function( $class_name ) {
	// Retorna si no esta incluido en el namespace
	$pos = strpos( $class_name, 'Personal' );
	if ( (false === $pos) or ($pos !== 0) ) {
		return;
	}
	$file_parts = explode( '\\', $class_name );
	$namespace='';
	for ($i=0; $i < count( $file_parts ) - 1 ; $i++) { 
		$namespace.= strtolower( $file_parts[ $i ] ).'/';
	}
	$namespace.= $file_parts[  count( $file_parts ) -1 ] .'.php';

	 require_once(WP_PLUGIN_DIR.'/'.$namespace);
}
);
