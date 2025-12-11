<?php
/**
 * This template is for rendering the list of personal for the block.
 * It expects a WP_Query object named $loop.
 * It also expects the $attributes array from the block.
 */

// Set default columns if not provided
$columns = isset($attributes['columns']) ? $attributes['columns'] : 3;

?>
<div class=" ">
    <div class="row row-cols-1 row-cols-md-<?php echo esc_attr($columns); ?> g-4" >
        <?php
        while ($loop->have_posts()) :
                $loop->the_post();
                $reserchgate = get_post_meta(get_the_ID(), 'researchgate', true);
                $google_scholar = get_post_meta(get_the_ID(), 'google_scholar', true);
                $orcid = get_post_meta(get_the_ID(), 'orcid', true);
                $linkedin = get_post_meta(get_the_ID(), 'linkedin', true);
                $facebook = get_post_meta(get_the_ID(), 'facebook', true);
                $twitter = get_post_meta(get_the_ID(), 'twitter', true);
                $email = get_post_meta(get_the_ID(), 'email', true);
                $unidad_de_investigacion = get_post_meta(get_the_ID(), 'unidad_de_investigacion', true);
                $rol = get_post_meta(get_the_ID(), 'rol_unidad_de_investigacion', true);
                $grado_alcanzado = get_post_meta(get_the_ID(), 'grado_alcanzado', true);
                $biografia = get_post_meta(get_the_ID(), 'biografia', true);
                $curriculum_vitae = get_post_meta(get_the_ID(), 'curriculum_vitae', true);
                $categorias = wp_get_post_terms(get_the_ID(), 'categorias', array("personal"));

            $image = get_the_post_thumbnail_url();
            $path_image_top = !empty($image) ? $image : plugins_url() . "/personal/assets/images/blank-profile.png";
            ?>
            <div class="col">
            <div class="card">
                <a href="<?php echo get_permalink(get_the_ID()) ?>">
                    <div class="card-img-top" style="background-image: url('<?php echo $path_image_top ?>'); ">
                    </div>

                </a>

                <div class="card-body">
                    <h5 class="card-title">    <?php the_title('<a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a>'); ?></h5>
                    <div class="card-text small mb-2"><?php echo $grado_alcanzado ?></div>
                    <div class="card-text small mb-2"><?php echo $rol ?></div>
                    <p class="card-text small"><?php echo $unidad_de_investigacion ?></p>
                </div>
                <div class="card-footer">
                    <div class="footer-redes">
                        <?php if (!empty($google_scholar)): ?>
                            <a href="<?php echo $google_scholar; ?>" target="_blank"><img
                                        class=" wp-image-16"
                                        src="<?php echo plugins_url() . "/personal/assets/images/google_scholar.png" ?>"
                                        alt="google_scholar" width="20" height="20" scale="0"></a>
                        <?php endif; ?>
                        <?php if (!empty($reserchgate)): ?><a href="<?php echo $reserchgate; ?>" target="_blank"><img
                                    class=" wp-image-17"
                                    src="<?php echo plugins_url() . "/personal/assets/images/research-gate.png" ?>"
                                    alt="research-gate" width="20" height="20"></a>
                        <?php endif; ?>
                        <?php if (!empty($orcid)): ?><a href="<?php echo $orcid; ?>" target="_blank"><img
                                    class=" wp-image-19"
                                    src="<?php echo plugins_url() . "/personal/assets/images/orcid.gif" ?>"
                                    alt="orcid" width="20" height="20" scale="0"> </a>
                        <?php endif; ?>
                        <?php if (!empty($linkedin)): ?><a href="<?php echo $linkedin; ?>" target="_blank"><img
                                    class=" wp-image-19"
                                    src="<?php echo plugins_url() . "/personal/assets/images/linkedin.png" ?>"
                                    alt="orcid" width="20" height="20" scale="0"> </a>
                        <?php endif; ?>
                        <?php if (!empty($facebook)): ?><a href="<?php echo $facebook; ?>" target="_blank"><img
                                    class=" wp-image-19"
                                    src="<?php echo plugins_url() . "/personal/assets/images/facebook.jpg" ?>"
                                    alt="facebook" width="20" height="20" scale="0"> </a>
                        <?php endif; ?>
                        <?php if (!empty($twitter)): ?><a href="<?php echo $twitter; ?>" target="_blank"><img
                                    class=" wp-image-19"
                                    src="<?php echo plugins_url() . "/personal/assets/images/twitter.png" ?>"
                                    alt="twitter" width="20" height="20" scale="0"> </a>
                        <?php endif; ?>
                        <?php if (!empty($email)): ?><a href="mailto:<?php echo $email; ?>" target="_blank"><img
                                    src="<?php echo plugins_url() . "/personal/assets/images/mailto.gif" ?>"
                                    alt="Mail" width="16" scale="0"></a>
                        <?php endif; ?>
                        <?php if (!empty($curriculum_vitae)): ?><a href="<?php echo $curriculum_vitae['url']; ?>"
                                                                   target="_blank"><img
                                    src="<?php echo plugins_url() . "/personal/assets/images/cv.png" ?>"
                                    alt="CV" width="16" scale="0"></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
