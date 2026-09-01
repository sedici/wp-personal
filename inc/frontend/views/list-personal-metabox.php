<?php
/**
 * Vista para listar el personal - Formato Cajas
 * @var array $args Datos de personas inyectados desde la clase Frontend.
 */
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
                
                <a href="<?php echo esc_url( $p['permalink'] ); ?>" class="personal-list-card-link">
                    <div class="personal-list-avatar" style="background-image: url('<?php echo esc_url( $p['image'] ); ?>');"></div>
                </a>
                
                <!-- Cuerpo de Información -->
                <div class="personal-list-card-body">
                    <h3 class="personal-list-card-name">
                        <a id="personal-card-title" href="<?php echo esc_url( $p['permalink'] ); ?>">
                            <?php echo esc_html( $p['title'] ); ?>
                        </a>
                    </h3>
                    
                    <?php if ( ! empty( $p['rol'] ) ) : ?>
                        <p class="personal-list-card-role">
                            <?php echo esc_html( $p['rol'] ); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="personal-list-card-details">
                        <?php if ( ! empty( $p['grado_alcanzado'] ) ) : ?>
                            <p class="personal-list-card-degree"><?php echo esc_html( $p['grado_alcanzado'] ); ?></p>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $p['unidad'] ) ) : ?>
                            <p class="personal-list-card-unit"><?php echo esc_html( $p['unidad'] ); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Fila de Redes Sociales con Colores Originales Nativos -->
                    <?php if ( ! empty( $p['social_media'] ) ) : ?>
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