<?php
/**
 * Vista para listar el personal - Formato Texto (Nombre; Nombre)
 * @var array $args Datos inyectados de forma segura desde el controlador.
 */

// Registrar CSS específico de esta vista si fuera necesario (opcional)
// wp_enqueue_style('list-personal-text', plugin_dir_url(__FILE__) . '../css/list-personal-text.css', array(), '1.0.0');

?>
<div class="personal-list-text">
    <?php if ( isset( $args['title'] ) && ! empty( $args['title'] ) ) : ?>
        <h2 class="personal-list-main-title"><?php echo esc_html( $args['title'] ); ?></h2>
    <?php endif; ?>

    <p>
        <?php
        $links = [];
        foreach ( $args['personas'] as $p ) {
            $links[] = '<a href="' . esc_url( $p['permalink'] ) . '">' . esc_html( $p['title'] ) . '</a>';
        }
        echo implode( '; ', $links );
        ?>
    </p>
</div>
