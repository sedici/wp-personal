<?php
/**
 * Vista pública optimizada del Custom Post "Personal" - Versión PERFIL_2
 * @var array $args Datos inyectados desde el controlador.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
?>

<div class="personal-profile-wrapper">

    <div class="personal-top-section">
        
        <div class="personal-sidebar">
            <div class="personal-avatar">
                <img src="<?php echo esc_url( $args['personal']['imagen_perfil'] ); ?>" alt="Foto de <?php echo esc_attr( $args['personal']['nombre'] ); ?>">
            </div>

            <div class="personal-contact-box">
                <h3>Contacto</h3>
                
                <?php if ( ! empty( $args['personal']['telefono'] ) ) : ?>
                    <p class="contact-item tel">
                        <a href="tel:<?php echo esc_attr( $args['personal']['telefono'] ); ?>"><?php echo esc_html( $args['personal']['telefono'] ); ?></a>
                    </p>
                <?php endif; ?>

                <?php if ( ! empty( $args['personal']['email'] ) ) : ?>
                    <p class="contact-item email">
                        <a href="mailto:<?php echo esc_attr( $args['personal']['email'] ); ?>"><?php echo esc_html( $args['personal']['email'] ); ?></a>
                    </p>
                <?php endif; ?>

                <?php if ( ! empty( $args['redes'] ) ) : ?>
                    <div class="personal-social-row">
                        <?php foreach ( $args['redes'] as $red ) : 
                            if ( strpos( strtolower( $red['alt'] ), 'cv' ) !== false || strpos( strtolower( $red['alt'] ), 'curriculum' ) !== false ) {
                                $cv_btn = $red;
                                continue;
                            }
                        ?>
                            <a href="<?php echo esc_url( $red['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo esc_url( $red['img'] ); ?>" alt="<?php echo esc_attr( $red['alt'] ); ?>">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( isset( $cv_btn ) ) : ?>
                <a href="<?php echo esc_url( $cv_btn['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="personal-cv-download-btn">
                        Descargar CV completo
                </a>
            <?php endif; ?>

            <?php if ( ! empty( $args['hera_url'] ) ) : ?>
                <a href="<?php echo esc_url( $args['hera_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="personal-hera-btn">
                        Ver Reporte En HERA
                </a>
            <?php endif; ?>
        </div>

        <div class="personal-main-content">
            
            <div class="personal-header-info">
                <span class="personal-badge-label">Perfil Académico</span>
                <h1 class="personal-fullname"><?php echo esc_html( $args['personal']['nombre'] ); ?></h1>
                
                <?php if ( ! empty( $args['personal']['grado_alcanzado'] ) ) : ?>
                    <h3 class="personal-degree-label"> <?php echo esc_html( $args['personal']['grado_alcanzado'] ); ?></h3>
                <?php endif; ?>

                <div class="personal-metadata-block">
                    <?php if ( ! empty( $args['personal']['unidad_de_investigacion'] ) || ! empty( $args['personal']['unidad_de_investigacion'] ) ) : ?>
                        <p><strong>Unidades de investigación :</strong> <?php echo esc_html( $args['personal']['unidad_de_investigacion'] ); ?></p>
                    <?php endif; ?>

                    <?php if ( ! empty( $args['personal']['categorias'] ) ) : ?>
                        <p>
                            <strong>Rol dentro de la UID : </strong> 
                            <?php foreach ( $args['personal']['categorias'] as $index => $cat ) : ?>
                                <?php if ( $index > 0 ) echo ', '; ?>
                                <span class="personal-role-inline"><?php echo esc_html( $cat->name ); ?></span>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="personal-body-info">
                <?php if ( ! empty( $args['personal']['lineas_investigacion'] ) ) : ?>
                    <div class="personal-lines-section">
                        <h2>Líneas de investigación</h2>
                        <ul class="personal-bullets-list">
                            <?php foreach ( $args['personal']['lineas_investigacion'] as $linea ) : ?>
                                <li><?php echo esc_html( $linea->name ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $args['personal']['biografia'] ) ) : ?>
                    <div class="personal-biography-body">
                        <?php echo nl2br( $args['personal']['biografia'] ); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php if ( empty( $args['dspace_activo'] ) && is_user_logged_in()) : ?>
        <div class="personal-publications-area">
                <p class="personal-dspace-missing">
                    <?php esc_html_e('Para mostrar la producción científica de esta persona en repositorios es necesario activar el plugin', 'personal'); ?> <a href="https://github.com/sedici/wp-dspace-v2"> DSpace Connector v2 (wp-dspace-v2) </a>
                </p>
        </div>
    <?php elseif ( empty( $args['dspace_activo'] ) && ! is_user_logged_in()) : ?>
        <!-- Nothing to display -->
    <?php elseif ( ! empty( $args['publicaciones'] ) ) : ?>
        <div class="personal-publications-area">
            <?php foreach ( $args['publicaciones'] as $repositorio ) : ?>
                <div class="personal-accordion-item">
                    <div class="personal-accordion-header">
                        <span><?php echo esc_html( $repositorio['label'] ); ?></span>
                        <span class="personal-accordion-icon">❯</span>
                    </div>
                    <div class="personal-accordion-content">
                        <?php echo do_shortcode( $repositorio['shortcode'] ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>