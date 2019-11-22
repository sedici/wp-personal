<?php

/**
 *
 * Vista publica del custom post "Personal"
 * Author: SEDICI - Ezequiel Manzur - Maria Marta Vila
 *
 */
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
$categorias = wp_get_post_terms($post->ID, categorias, array("personal"));
?>





<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title">
                    <?php the_title(); ?></h1>


            </header>
            <div class="entry-content ">
                <div class="row">
                    <div class="col-md-3">


                        <div class="foto">
                            <?php
                            $image = get_the_post_thumbnail_url();
                            if (!empty($image)): ?><img src="<?php echo $image; ?>"
                                                        alt="Imagen de <?php echo the_title(); ?>" />
                            <?php else: ?><img
                                src="<?php echo plugins_url(); ?>/personal/assets/images/blank-profile.png" />
                            <?php endif; ?>

                        </div>
                        <div class="barra_redes">
                            <ul>

                                <?php if (!empty($google_scholar)): ?>
                                    <li><a href="<?php echo $google_scholar; ?>" target="_blank"><img
                                                    class="alignnone wp-image-16"
                                                    src="<?php echo plugins_url() . "/personal/assets/images/google_scholar.png" ?>"
                                                    alt="google_scholar" width="20" height="20" scale="0"></a></li>
                                <?php endif; ?>
                                <?php if (!empty($reserchgate)): ?>
                                    <li><a href="<?php echo $reserchgate; ?>" target="_blank"><img
                                                    class="alignnone wp-image-17"
                                                    src="<?php echo plugins_url() . "/personal/assets/images/research-gate.png" ?>"
                                                    alt="research-gate" width="20" height="20"></a></li>
                                <?php endif; ?>
                                <?php if (!empty($orcid)): ?>
                                    <li><a href="<?php echo $orcid; ?>" target="_blank"><img
                                                    class="alignnone wp-image-19"
                                                    src="<?php echo plugins_url() . "/personal/assets/images/orcid.gif" ?>"
                                                    alt="orcid" width="20" height="20" scale="0"> </a></li>
                                <?php endif; ?>
                                <?php if (!empty($email)): ?>
                                    <li><a href="mailto:<?php echo $email; ?>" target="_blank"><img
                                                    src="<?php echo plugins_url() . "/personal/assets/images/mailto.gif" ?>"
                                                    alt="Mail" width="16" scale="0"></a></li>
                                <?php endif; ?>
                                <?php if (!empty($curriculum_vitae)): ?>
                                    <li><a href="<?php echo $curriculum_vitae['url']; ?>" target="_blank"><img
                                                    src="<?php echo plugins_url() . "/personal/assets/images/cv.png" ?>"
                                                    alt="CV" width="16" scale="0"></a></li></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <?php if (!empty($email)): ?>
                            <div class="views-field views-field-email"><span class="views-label views-label-email">Email: </span><span
                                        class="field-content email"><a href="mailto:<?php echo $email; ?>"
                                                                       class="mailto"><?php echo $email; ?></a></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($telefono)): ?>
                            <div class="views-field views-field-tel"><span
                                        class="views-label views-label-tel">Télefono: </span><span
                                        class="field-content telefono"><a href="tel:<?php $telefono; ?>"
                                                                          class="telto"><?php echo $telefono; ?></a></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($unidad_de_investigacion)): ?>
                            <div class="views-field views-field-unidad_de_investigacion"><span
                                        class="views-label views-label-unidad_de_investigacion">Unidad/es de investigación: </span><span
                                        class="field-content unidad_de_investigacion"><?php echo $unidad_de_investigacion; ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($grado_alcanzado)): ?>
                            <div class="views-field views-field-grado_alcanzado"><span
                                        class="views-label views-label-grado_alcanzado">Grado académico: </span><span
                                        class="field-content grado_alcanzado"><?php echo $grado_alcanzado; ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($biografia)): ?>
                            <div class="views-field views-field-biografia"><span
                                        class="views-label views-label-biografia">Biografía: </span><span
                                        class="field-content biografia"><?php echo $biografia; ?></span></div>
                        <?php endif; ?>

                    </div>
                </div>

                <?php if (!empty($sedici)): ?>
                    <div id="sedici" class="col"><h2>Producción científica en
                            SEDICI</h2><?php echo do_shortcode('[get_publications  config="sedici"  author="' . $sedici . '"  share=true show_subtype=false show_author=true date=true max_results="1000" ]'); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($cic)): ?>
                    <div id="cic" class="col"><h2>Producción científica en
                            CIC</h2><?php echo do_shortcode('[get_publications  config="cic"  author="' . $cic . '"  share=true show_subtype=false show_author=true date=true  max_results="1000" "]'); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($conicet)): ?>
                    <div id="conicet" class="col"><h2>Producción científica en
                            CONICET</h2><?php echo do_shortcode('[get_publications  config="conicet"  author="' . $conicet . '"  share=true show_subtype=false show_author=true date=true  max_results="1000""]'); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($categorias)): ?>
                    <div class="list-categoria">
                            <?php foreach ($categorias as $categoria): ?>
                                <span>
                                    <a  href="<?php echo get_term_link($categoria); ?>"><?php echo get_term($categoria, "categorias")->name; ?></a> |
                                </span>
                            <?php endforeach; ?>
                            <?php
                            ?>
                    </div>

                <?php endif; ?>
        </article>


    </div><!-- #content .site-content -->
</div><!-- #primary .content-area -->


