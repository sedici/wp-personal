<?php
/**
 * Vista para la importación de personal desde un archivo CSV.
 */

use Personal\Admin\Csv_Import_Log;

$import_log = Csv_Import_Log::get_all();
?>

<div class="wrap">
    <h1>Importar Personal desde CSV</h1>

    <p>Utilice este formulario para subir un archivo CSV que contenga la información del personal que desea importar.
        Asegúrese de que el archivo CSV esté correctamente formateado.</p>

    <form method="post" enctype="multipart/form-data" onsubmit="process_csv_form(this); return false;">
        <input type="hidden" name="action" value="import_csv">
        <?php wp_nonce_field('personal_csv_import', 'personal_csv_import_nonce'); ?>
        <input type="file" name="personal_csv_file" accept=".csv" />
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Procesar CSV">
        </p>
    </form>

    <div id="csv-import-results" style="display:none; margin-top: 20px;"></div>

    <h2 style="margin-top: 40px;">Historial de importaciones</h2>

    <?php if (empty($import_log)) : ?>
        <p>Todavía no se importó ningún CSV.</p>
    <?php else : ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Archivo</th>
                    <th>Creados</th>
                    <th>Actualizados</th>
                    <th>Errores</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($import_log as $entry) : ?>
                    <tr>
                        <td><?php echo esc_html($entry['date']); ?></td>
                        <td><?php echo esc_html($entry['file']); ?></td>
                        <td><?php echo esc_html($entry['created']); ?></td>
                        <td><?php echo esc_html($entry['updated']); ?></td>
                        <td>
                            <?php if (empty($entry['errors'])) : ?>
                                &mdash;
                            <?php else : ?>
                                <details>
                                    <summary><?php echo count($entry['errors']); ?> fila(s) con error</summary>
                                    <ul>
                                        <?php foreach ($entry['errors'] as $error) : ?>
                                            <li>Fila <?php echo esc_html($error['row']); ?> (<?php echo esc_html($error['field']); ?>): <?php echo esc_html($error['error']); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </details>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>