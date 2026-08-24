<?php
/**
 * Vista para listar el personal - Formato Tabla
 * @var array $args Datos inyectados de forma segura desde el controlador.
 */
?>
<div class="personal-list-table-container">
    <?php if ( isset( $args['title'] ) && ! empty( $args['title'] ) ) : ?>
        <h2 class="personal-list-main-title"><?php echo esc_html( $args['title'] ); ?></h2>
    <?php endif; ?>

    <table class="personal-list-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Grado</th>
                <th>Unidad</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $args['personas'] as $p ) : ?>
                <tr>
                    <td>
                        <a href="<?php echo esc_url( $p['permalink'] ); ?>">
                            <?php echo esc_html( $p['title'] ); ?>
                        </a>
                    </td>
                    <td><?php echo esc_html( $p['rol'] ?? '' ); ?></td>
                    <td><?php echo esc_html( $p['grado_alcanzado'] ?? '' ); ?></td>
                    <td><?php echo esc_html( $p['unidad'] ?? '' ); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
