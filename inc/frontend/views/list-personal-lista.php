<?php
/**
 * Vista para listar el personal - Formato Lista (una fila por persona)
 * @var array $args Datos de personas inyectados desde Render_Personal.
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
        <h2 class="personal-list-main-title"><?php echo esc_html( $args['title'] ); ?></h2>
    <?php endif; ?>

    <ul class="personal-lista">
        <?php foreach ( $args['personas'] as $p ) : ?>
            <?php
            // Datos de una línea, separados por un punto medio; se omiten los vacíos.
            $meta = array();
            if ( $mostrar( 'rol' ) && ! empty( $p['rol'] ) ) {
                $meta[] = esc_html( $p['rol'] );
            }
            if ( $mostrar( 'grado' ) && ! empty( $p['grado_alcanzado'] ) ) {
                $meta[] = esc_html( $p['grado_alcanzado'] );
            }
            if ( $mostrar( 'unidad' ) && ! empty( $p['unidad'] ) ) {
                $meta[] = esc_html( $p['unidad'] );
            }

            $contacto = array();
            if ( $mostrar( 'email' ) && ! empty( $p['email'] ) ) {
                $contacto[] = '<a href="mailto:' . esc_attr( antispambot( $p['email'] ) ) . '">' . esc_html( antispambot( $p['email'] ) ) . '</a>';
            }
            if ( $mostrar( 'telefono' ) && ! empty( $p['telefono'] ) ) {
                $contacto[] = esc_html( $p['telefono'] );
            }

            $afiliaciones = $mostrar( 'afiliaciones' ) ? $p['afiliaciones'] : array();
            $redes        = $mostrar( 'redes' ) ? $p['social_media'] : array();
            ?>
            <li class="personal-lista-item">

                <?php if ( $mostrar( 'foto' ) ) : ?>
                    <a href="<?php echo esc_url( $p['permalink'] ); ?>" class="personal-lista-avatar" style="background-image: url('<?php echo esc_url( $p['image'] ); ?>');" aria-hidden="true" tabindex="-1"></a>
                <?php endif; ?>

                <div class="personal-lista-body">
                    <h3 class="personal-lista-name">
                        <a href="<?php echo esc_url( $p['permalink'] ); ?>"><?php echo esc_html( $p['title'] ); ?></a>
                    </h3>

                    <?php if ( $meta ) : ?>
                        <p class="personal-lista-meta"><?php echo implode( ' &middot; ', $meta ); ?></p>
                    <?php endif; ?>

                    <?php if ( $contacto ) : ?>
                        <p class="personal-lista-contacto"><?php echo implode( ' &middot; ', $contacto ); ?></p>
                    <?php endif; ?>
                </div>

                <?php if ( $afiliaciones || $redes ) : ?>
                    <div class="personal-lista-icons">
                        <?php foreach ( $afiliaciones as $afiliacion ) : ?>
                            <a href="<?php echo esc_url( $afiliacion['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo esc_url( $afiliacion['img'] ); ?>" alt="<?php echo esc_attr( $afiliacion['alt'] ); ?>" width="28" height="28">
                            </a>
                        <?php endforeach; ?>
                        <?php foreach ( $redes as $red ) : ?>
                            <a href="<?php echo esc_url( $red['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo esc_url( $red['img'] ); ?>" alt="<?php echo esc_attr( $red['alt'] ); ?>" width="16" height="16">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </li>
        <?php endforeach; ?>
    </ul>
</div>
