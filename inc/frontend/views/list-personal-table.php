<?php
/**
 * Vista para listar el personal - Formato Tabla
 * @var array $args Datos de personas inyectados desde Render_Personal.
 *                  'campos' indica qué datos del perfil mostrar además del nombre:
 *                  la foto va junto al nombre y afiliaciones/redes comparten la columna "Perfiles".
 */

use Personal\Inc\Render\Render_Personal;

$campos  = isset( $args['campos'] ) ? $args['campos'] : Render_Personal::CAMPOS_DEFAULT;
$mostrar = function ( $campo ) use ( $campos ) {
    return in_array( $campo, $campos, true );
};

// Columnas de texto: campo => [encabezado, clave en los datos de la persona]
$columnas = array(
    'rol'      => array( __( 'Rol', 'personal-block' ), 'rol' ),
    'grado'    => array( __( 'Grado', 'personal-block' ), 'grado_alcanzado' ),
    'unidad'   => array( __( 'Unidad', 'personal-block' ), 'unidad' ),
    'email'    => array( __( 'Email', 'personal-block' ), 'email' ),
    'telefono' => array( __( 'Teléfono', 'personal-block' ), 'telefono' ),
);
$columnas = array_filter( $columnas, $mostrar, ARRAY_FILTER_USE_KEY );

$con_perfiles = $mostrar( 'afiliaciones' ) || $mostrar( 'redes' );
?>
<div class="personal-list-table-container">
    <?php if ( isset( $args['title'] ) && ! empty( $args['title'] ) ) : ?>
        <h2 class="personal-list-main-title"><?php echo esc_html( $args['title'] ); ?></h2>
    <?php endif; ?>

    <div class="personal-list-table-scroll">
        <table class="personal-list-table">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Nombre', 'personal-block' ); ?></th>
                    <?php foreach ( $columnas as $columna ) : ?>
                        <th><?php echo esc_html( $columna[0] ); ?></th>
                    <?php endforeach; ?>
                    <?php if ( $con_perfiles ) : ?>
                        <th><?php esc_html_e( 'Perfiles', 'personal-block' ); ?></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $args['personas'] as $p ) : ?>
                    <tr>
                        <td>
                            <a class="personal-list-table-name" href="<?php echo esc_url( $p['permalink'] ); ?>">
                                <?php if ( $mostrar( 'foto' ) ) : ?>
                                    <span class="personal-list-table-avatar" style="background-image: url('<?php echo esc_url( $p['image'] ); ?>');"></span>
                                <?php endif; ?>
                                <?php echo esc_html( $p['title'] ); ?>
                            </a>
                        </td>
                        <?php foreach ( $columnas as $campo => $columna ) : ?>
                            <td>
                                <?php if ( $campo === 'email' && ! empty( $p['email'] ) ) : ?>
                                    <a href="mailto:<?php echo esc_attr( antispambot( $p['email'] ) ); ?>"><?php echo esc_html( antispambot( $p['email'] ) ); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html( $p[ $columna[1] ] ?? '' ); ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        <?php if ( $con_perfiles ) : ?>
                            <td class="personal-list-table-icons">
                                <?php if ( $mostrar( 'afiliaciones' ) ) : ?>
                                    <?php foreach ( $p['afiliaciones'] as $afiliacion ) : ?>
                                        <a href="<?php echo esc_url( $afiliacion['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo esc_url( $afiliacion['img'] ); ?>" alt="<?php echo esc_attr( $afiliacion['alt'] ); ?>" width="24" height="24">
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if ( $mostrar( 'redes' ) ) : ?>
                                    <?php foreach ( $p['social_media'] as $red ) : ?>
                                        <a href="<?php echo esc_url( $red['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo esc_url( $red['img'] ); ?>" alt="<?php echo esc_attr( $red['alt'] ); ?>" width="16" height="16">
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
