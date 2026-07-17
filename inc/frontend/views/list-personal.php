<?php
/**
 * Vista para listar el personal - Versión Optimizada Minimalista
 * @var array $args Datos inyectados de forma segura desde el controlador.
 */
?>

<div class="personal-directory-container">
    
    <?php if ( isset( $args['title'] ) && ! empty( $args['title'] ) ) : ?>
        <h2 class="personal-directory-main-title">
            <?php echo esc_html( $args['title'] ); ?>
        </h2>
    <?php endif; ?>

    <!-- Grilla adaptativa basada en el atributo dinámico de columnas -->
    <div class="personal-directory-grid" style="--grid-columns: <?php echo esc_attr( $args['columns'] ); ?>;">
        
        <?php foreach ( $args['personas'] as $p ) : ?>
            <article class="personal-directory-card">
                
                <!-- Contenedor de la Imagen Cuadrada (Proporción 1:1) -->
                <a href="<?php echo esc_url( $p['permalink'] ); ?>" class="personal-directory-card-link">
                    <div class="personal-directory-avatar" style="background-image: url('<?php echo esc_url( $p['image'] ); ?>');"></div>
                </a>
                
                <!-- Cuerpo de Información -->
                <div class="personal-directory-card-body">
                    <h3 class="personal-directory-card-name">
                        <a href="<?php echo esc_url( $p['permalink'] ); ?>">
                            <?php echo esc_html( $p['title'] ); ?>
                        </a>
                    </h3>
                    
                    <?php if ( ! empty( $p['rol'] ) ) : ?>
                        <p class="personal-directory-card-role">
                            <?php echo esc_html( $p['rol'] ); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="personal-directory-card-details">
                        <?php if ( ! empty( $p['grado_alcanzado'] ) ) : ?>
                            <p class="personal-directory-card-degree"><?php echo esc_html( $p['grado_alcanzado'] ); ?></p>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $p['unidad'] ) ) : ?>
                            <p class="personal-directory-card-unit"><?php echo esc_html( $p['unidad'] ); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Fila de Redes Sociales con Colores Originales Nativos -->
                    <?php if ( ! empty( $p['social_media'] ) ) : ?>
                        <div class="personal-directory-card-socials">
                            <?php foreach ( $p['social_media'] as $platform => $url ) : ?>
                                <?php if ( ! empty( $url ) ) : ?>
                                    <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="<?php echo plugins_url() . '/wp-personal/assets/images/' . esc_attr( $platform ) . '.png'; ?>" alt="<?php echo esc_attr( $platform ); ?>" width="16" height="16">
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