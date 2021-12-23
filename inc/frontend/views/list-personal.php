<?php
$args = array('post_type' => 'personal','posts_per_page' => 50);
if (!empty($atts['category_id'])) {
    $args['tax_query'] =
        array(
            array(
                'terms' => $atts['category_id'],
                'taxonomy' => 'categorias',

            ));
}

$loop = new WP_Query($args);
?>

<h3> <?php echo $atts['title'] ?></h3>
<div class=" ">
    <div class="row row-cols-1 row-cols-md-<?php echo $atts['columns'] ?> g-4" >
        <?php while ($loop->have_posts()) :
            $loop->the_post(); ?>
            <?php
            $reserchgate = $this->the_personal_field('researchgate');
            $google_scholar = $this->the_personal_field('google_scholar');
            $scopus = $this->the_personal_field('scopus');
            $publons = $this->the_personal_field('publons');
            $orcid = $this->the_personal_field('orcid');
            $linkedin = $this->the_personal_field('linkedin');
            $email = $this->the_personal_field('email');
            $unidad_de_investigacion = $this->the_personal_field('unidad_de_investigacion');
            $rol = $this->the_personal_field('rol_unidad_de_investigacion');
            $grado_alcanzado = $this->the_personal_field('grado_alcanzado');
            $biografia = $this->the_personal_field('biografia');
            if(isset($post->ID)){
            $categorias = wp_get_post_terms($post->ID, 'categorias', array("personal"));
            }
            else{
                $categorias = wp_get_post_terms('categorias', array("personal"));
            }
            
            ?>
            <?php
            $image = get_the_post_thumbnail_url();
            $path_image_top = !empty($image) ? $image : plugins_url() . "/personal/assets/images/blank-profile.png";
            ?>
            <div class="col">
            <div class="card">
            <?php
                if(isset($post->ID)){ ?>
                  <a href="<?php echo get_permalink($post->ID) ?>">
               <?php }
                else{ ?>
                    <a>
               <?php } ?>
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
                        <?php if (!empty($scopus)): ?><a href="<?php echo $scopus; ?>" target="_blank"><img
                                    class=" wp-image-19"
                                    src="<?php echo plugins_url() . "/personal/assets/images/scopus.png" ?>"
                                    alt="scopus" width="20" height="20"></a>
                        <?php endif; ?>
                        <?php if (!empty($publons)): ?><a href="<?php echo $publons; ?>" target="_blank"><img
                                    class=" wp-image-19"
                                    src="<?php echo plugins_url() . "/personal/assets/images/publons.png" ?>"
                                    alt="publons" width="20" height="20"></a>
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
        <?php endwhile; 
        wp_reset_postdata();
        ?>
    </div>
</div>

