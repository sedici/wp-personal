<?php
/**
 *
 * Autoload de clases
 * @param string $class_name Clase a cargar
 *
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Personal plugin Autoloader (PSR-4).
 * Mapea el namespace Personal a la carpeta /src/
 */

spl_autoload_register(function ($class) {
	
	// Prefijo del namespace del plugin
	$prefix = 'Personal\\';
	// Carpeta base donde están las clases
	$base_dir = Personal\PLUGIN_NAME_DIR;

	$len = strlen($prefix);

	// ¿La clase utiliza nuestro prefijo?
	if (strncmp($prefix, $class, $len) !== 0) {
		return;
	}

	// Obtener el nombre de la clase relativo al prefijo
	$relative_class = substr($class, $len);

	// Separa el namespace en partes usando \ como separador
	$parts = explode('\\', $relative_class);

	// Toma el nombre de la clase (última parte)
	$class_name = array_pop($parts); 
	// Convierte el resto del namespace en una ruta de carpetas (en minúsculas)
	$sub_path = strtolower(implode(DIRECTORY_SEPARATOR, $parts));

	// 1. Limpieza de nombre: cambiamos guiones bajos por medios y pasamos a minúsculas
	$clean_class_name = strtolower(str_replace('_', '-', $class_name));

	// 2. Construimos el nombre del archivo final
	$file_name = 'class-' . $clean_class_name . '.php';

	$full_path = $base_dir . ( !empty($sub_path) ? $sub_path . DIRECTORY_SEPARATOR : '' ) . $file_name;

	// Si el archivo existe, cargarlo
	if (file_exists($full_path)) {
		require_once $full_path;
	}
});

?>