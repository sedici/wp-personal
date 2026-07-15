<?php
/**
 * Vista para la exportación de personal a un archivo CSV.
 */
?>

<div class="wrap">
    <h1>Exportar Personal a CSV</h1>

    <p>Haga clic en el botón para descargar un archivo CSV con toda la información del personal registrado.</p>

    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="export_personal_csv">
        <?php wp_nonce_field('export_personal_csv_action', 'export_personal_csv_nonce'); ?>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary"
                value="Exportar personal a CSV">
        </p>
    </form>
</div>