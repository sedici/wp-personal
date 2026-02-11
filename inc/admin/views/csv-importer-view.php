<?php
/**
 * Vista para la importación de personal desde un archivo CSV.
 */
?>

<div class="wrap">
    <h1>Importar Personal desde CSV</h1>

    <p>Utilice este formulario para subir un archivo CSV que contenga la información del personal que desea importar.
        Asegúrese de que el archivo CSV esté correctamente formateado.</p>

    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="import_csv">
        <?php wp_nonce_field('personal_csv_import', 'personal_csv_import_nonce'); ?>
        <input type="file" name="personal_csv_file" accept=".csv" />
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Procesar CSV">
        </p>
    </form>
</div>