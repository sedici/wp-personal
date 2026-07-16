<?php
/**
 * Vista para listar el personal.
 * @var array $args Datos inyectados de forma segura desde el controlador.
 */
?>
<h3><?php 
if (isset($args['title']) && !empty($args['title'])) {
    echo esc_html( $args['title'] );
}
?></h3>

<div class="">
    <div class="row row-cols-1 row-cols-md-<?php echo esc_attr( $args['columns'] ); ?> g-4">
        <?php foreach ( $args['personas'] as $p ) : ?>
            <div class="col">
                <div class="card">
                    
                    <a href="<?php echo esc_url( $p['permalink'] ); ?>">
                        <div class="card-img-top" style="background-image: url('<?php echo esc_url( $p['image'] ); ?>');"></div>
                    </a>
                    
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="<?php echo esc_url( $p['permalink'] ); ?>" title="<?php echo esc_attr( $p['title'] ); ?>" rel="bookmark">
                                <?php echo esc_html( $p['title'] ); ?>
                            </a>
                        </h5>
                        <div class="card-text small mb-2"><?php echo esc_html( $p['grado_alcanzado'] ); ?></div>
                        <div class="card-text small mb-2"><?php echo esc_html( $p['rol'] ); ?></div>
                        <p class="card-text small"><?php echo esc_html( $p['unidad'] ); ?></p>
                    </div>      
                    
                    <div class="card-footer">
                        <div class="footer-redes">
                            <?php if ( ! empty( $p['social_media'] ) ) : ?>
                                <?php foreach ( $p['social_media'] as $platform => $url ) : ?>
                                    <?php if ( ! empty( $url ) ) : ?>
                                        <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                                            <img class="wp-image-16" src="<?php echo plugins_url() . '/wp-personal/assets/images/' . esc_attr( $platform ) . '.png'; ?>" alt="<?php echo esc_attr( $platform ); ?>" width="20" height="20">
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>