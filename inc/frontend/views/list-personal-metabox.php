<?php
/**
 * Vista para listar el personal - Formato Cajas
 * @var array $args Datos de personas inyectados desde la clase Frontend o Render_Personal.
 *                  'campos' indica qué datos del perfil mostrar además del nombre.
 */

use Personal\Inc\Render\Render_Personal;

$campos  = isset( $args['campos'] ) ? $args['campos'] : Render_Personal::CAMPOS_DEFAULT;
$mostrar = function ( $campo ) use ( $campos ) {
    return in_array( $campo, $campos, true );
};
?>

<div class="personal-list-container">

    <?php if ( isset( $args['title'] ) && ! empty( $args['title'] ) ) : ?>
        <h2 class="personal-list-main-title">
            <?php echo esc_html( $args['title'] ); ?>
        </h2>
    <?php endif; ?>

    <div class="personal-list-grid" style="--grid-columns: <?php echo esc_attr( $args['columns'] ); ?>;">

        <?php foreach ( $args['personas'] as $p ) : ?>
            <article class="personal-list-card">

                <?php if ( $mostrar( 'foto' ) ) : ?>
                    <a href="<?php echo esc_url( $p['permalink'] ); ?>" class="personal-list-card-link">
                        <div class="personal-list-avatar" style="background-image: url('<?php echo esc_url( $p['image'] ); ?>');"></div>
                    </a>
                <?php endif; ?>

                <!-- Cuerpo de Información -->
                <div class="personal-list-card-body">
                    <h3 class="personal-list-card-name">
                        <a class="personal-card-title" href="<?php echo esc_url( $p['permalink'] ); ?>">
                            <?php echo esc_html( $p['title'] ); ?>
                        </a>
                    </h3>

                    <?php if ( array_diff( $campos, array( 'foto' ) ) ) : ?>
                        <hr style="border: none; height: 1px; background-color: #666666; margin: 3px;">
                    <?php endif; ?>

                    <!-- Afiliaciones -->
                    <?php if ( $mostrar( 'afiliaciones' ) && ! empty( $p['afiliaciones'] ) ) : ?>
                        <div class="personal-list-card-afiliaciones">
                            <?php foreach ( $p['afiliaciones'] as $afiliacion ) : ?>
                                <?php if ( ! empty( $afiliacion['url'] ) ) : ?>
                                    <div class="personal-list-card-afiliacion-item">
                                        <a href="<?php echo esc_url( $afiliacion['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo esc_url( $afiliacion['img'] ); ?>" alt="<?php echo esc_attr( $afiliacion['alt'] ); ?>" width="40" height="40">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $mostrar( 'rol' ) && ! empty( $p['rol'] ) ) : ?>
                        <p class="personal-list-card-role">
                            <?php echo esc_html( $p['rol'] ); ?>
                        </p>
                    <?php endif; ?>

                    <div class="personal-list-card-details">
                        <?php if ( $mostrar( 'grado' ) && ! empty( $p['grado_alcanzado'] ) ) : ?>
                            <p class="personal-list-card-degree"><?php echo esc_html( $p['grado_alcanzado'] ); ?></p>
                        <?php endif; ?>

                        <?php if ( $mostrar( 'unidad' ) && ! empty( $p['unidad'] ) ) : ?>
                            <p class="personal-list-card-unit"><?php echo esc_html( $p['unidad'] ); ?></p>
                        <?php endif; ?>

                        <?php if ( $mostrar( 'email' ) && ! empty( $p['email'] ) ) : ?>
                            <p class="personal-list-card-email"><a href="mailto:<?php echo esc_attr( antispambot( $p['email'] ) ); ?>"><?php echo esc_html( antispambot( $p['email'] ) ); ?></a></p>
                        <?php endif; ?>

                        <?php if ( $mostrar( 'telefono' ) && ! empty( $p['telefono'] ) ) : ?>
                            <p class="personal-list-card-phone"><?php echo esc_html( $p['telefono'] ); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Fila de Redes Sociales con Colores Originales Nativos -->
                    <?php if ( $mostrar( 'redes' ) && ! empty( $p['social_media'] ) ) : ?>
                        <div class="personal-list-card-socials">
                            <?php foreach ( $p['social_media'] as $platform => $red ) : ?>
                                <?php if ( ! empty( $red['url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $red['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="<?php echo esc_url($red['img']); ?>" alt="<?php echo esc_attr( $red['alt'] ); ?>" width="16" height="16">
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </article>
        <?php endforeach; ?>

    </div>
</div>
