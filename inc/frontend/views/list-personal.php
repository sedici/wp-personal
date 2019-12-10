<?php $loop = new WP_Query(array('post_type' => 'personal')); ?>
<div class=row">
    <div class="card-columns col-md-10 offset-2  " style="column-count: 3">
        <?php while ($loop->have_posts()) :
            $loop->the_post(); ?>
            <?php
            $reserchgate = $this->the_personal_field('reserchgate');
            $google_scholar = $this->the_personal_field('google_scholar');
            $orcid = $this->the_personal_field('orcid');
            $email = $this->the_personal_field('email');
            $curriculum_vitae = $this->the_personal_meta('curriculum_vitae');
            $email = $this->the_personal_field('email');
            $telefono = $this->the_personal_field('telefono');
            $unidad_de_investigacion = $this->the_personal_field('unidad_de_investigacion');
            $grado_alcanzado = $this->the_personal_field('grado_alcanzado');
            $biografia = $this->the_personal_field('biografia');
            $sedici = $this->the_personal_field('sedici');
            $cic = $this->the_personal_field('cic');
            $conicet = $this->the_personal_field('conicet');
            $categorias = wp_get_post_terms($post->ID, 'categorias', array("personal"));
            ?>
            <?php
            $image = get_the_post_thumbnail_url();
            $path_image_top = !empty($image) ? $image : plugins_url() . "/personal/assets/images/blank-profile.png";
            ?>
            <div class="card">
                <a href="<?php echo get_permalink($post->ID) ?>">
                    <div class="card-img-top" style="background-image: url('<?php echo $path_image_top ?>'); ">
                    </div>
                </a>

                <div class="card-body">
                    <h5 class="card-title">    <?php the_title('<a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a>'); ?></h5>
                    <div class="card-subtitle mb-2"></div>
                    <p class="card-text small font-weight-bold	"><?php echo $unidad_de_investigacion ?></p>
                    <!--                    <p class="card-text small font-weight-bold	">-->
                    <?php //echo $grado_alcanzado
                    ?><!--</p>-->
                    <!--<div class="card-button">
                        <a href="<?php /*get_post_permalink() */
                    ?>" class="btn btn-primary">Ver más</a>
                    </div>-->
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
        <?php endwhile; ?>
    </div>
</div>
