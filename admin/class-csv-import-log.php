<?php

namespace Personal\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Historial de importaciones de CSV de personal: guarda, por cada corrida de
 * Csv_Importer::process_csv(), cuántos perfiles se crearon, cuántos se actualizaron
 * y qué filas tuvieron error, para poder consultarlo después sin depender del aviso
 * efímero que muestra la página justo después de subir el archivo.
 */
class Csv_Import_Log
{
    const OPTION_NAME = 'personal_csv_import_log';
    const MAX_ENTRIES = 20;

    /**
     * Registra una corrida de importación al principio del historial.
     *
     * @param string $file_name Nombre del archivo subido.
     * @param int $created Cantidad de personal creado.
     * @param int $updated Cantidad de personal actualizado.
     * @param array $errors Errores por fila (row, field, error).
     */
    public static function record($file_name, $created, $updated, array $errors)
    {
        $entries = self::get_all();

        array_unshift($entries, [
            'date'    => current_time('mysql'),
            'file'    => $file_name,
            'created' => $created,
            'updated' => $updated,
            'errors'  => $errors,
        ]);

        $entries = array_slice($entries, 0, self::MAX_ENTRIES);

        update_option(self::OPTION_NAME, $entries, false);
    }

    /**
     * @return array Historial de importaciones, más reciente primero.
     */
    public static function get_all()
    {
        $entries = get_option(self::OPTION_NAME, []);
        return is_array($entries) ? $entries : [];
    }
}
