<?php

/**
 *
 * Vista publica del custom post "Personal"
 * Author: SEDICI - Ezequiel Manzur - Maria Marta Vila
 *
 */
$reserchgate = $this->the_personal_field('researchgate');
$google_scholar = $this->the_personal_field('google_scholar');
$orcid = $this->the_personal_field('orcid');
$linkedin = $this->the_personal_field('linkedin');
$facebook = $this->the_personal_field('facebook');
$twitter = $this->the_personal_field('twitter');
$email = $this->the_personal_field('email');
$curriculum_vitae = $this->the_personal_meta('curriculum_vitae');
$telefono = $this->the_personal_field('telefono');
$unidad_de_investigacion = $this->the_personal_field('unidad_de_investigacion');
$grado_alcanzado = $this->the_personal_field('grado_alcanzado');
$biografia = $this->the_personal_field('biografia');
$sedici = $this->the_personal_field('sedici');
$cic = $this->the_personal_field('cic');
$conicet = $this->the_personal_field('conicet');
$categorias = wp_get_post_terms($post->ID, 'categorias', array("personal"));
$rol = $this->the_personal_field('rol_unidad_de_investigacion');


$other_repositories = $this->getRepositories();


?>
<div class="content-area">
    <div id="content" class="site-content" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content ">
                <div class="row">
                    <div class="col-md-4 sidebar_perfil">
                        <?php
                        $image = get_the_post_thumbnail_url();
                        $path_image_top = !empty($image) ? $image : plugins_url() . "/personal/assets/images/blank-profile.png";
                        ?>
                        <div class="col-md-12 sidebar_foto" style="display: flex;">
                            <div class="foto rounded-circle z-depth-2 "
                                 style="background-image: url('<?php echo $path_image_top ?>'); ">
                            </div>
                        </div>

                        <div class="col-md-8 offset-2">
                            <div class="barra-redes">
                                <?php if (!empty($google_scholar)): ?>
                                    <a href="<?php echo $google_scholar; ?>" target="_blank"><img
                                                class=" wp-image-16"
                                                src="<?php echo plugins_url() . "/personal/assets/images/google_scholar.png" ?>"
                                                alt="google_scholar" width="20" height="20" scale="0"></a>
                                <?php endif; ?>
                                <?php if (!empty($reserchgate)): ?><a href="<?php echo $reserchgate; ?>"
                                                                      target="_blank"><img
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
                                            alt="$linkedinid" width="20" height="20" scale="0"> </a>
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

                                <?php if (!empty($curriculum_vitae)): ?><a
                                    href="<?php echo $curriculum_vitae['url']; ?>"
                                    target="_blank"><img
                                            src="<?php echo plugins_url() . "/personal/assets/images/cv.png" ?>"
                                            alt="CV" width="16" scale="0"></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div>
                            <h2 id="nombre_personal">
                                <?php echo $post->post_title; ?>
                            </h2>
                            <hr class="hr-contacto">
                            <div class="col-md-12 contacto-personal">
                                <?php if (!empty($email)): ?>
                                    <a href="mailto:<?php echo $email; ?>" target="_blank"><img
                                                src="<?php echo plugins_url() . "/personal/assets/images/mail.svg" ?>"
                                                alt="Mail" width="25" scale="0"> <?php echo $email; ?></a>
                                <?php endif; ?>
                                <?php if (!empty($telefono)): ?>
                                <img
                                        width="25"
                                        src="<?php echo plugins_url() . "/personal/assets/images/telefono.svg" ?>">
                                <a href="tel:<?php echo $telefono; ?>" class="telefono"> <?php echo $telefono; ?></a>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($unidad_de_investigacion)): ?>
                                <div class="field-unidad_de_investigacion">
                                    <p><span>Unidad/es de investigación: </span><?php echo $unidad_de_investigacion; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($rol)): ?>
                                <div class="field-rol">
                                    <p>
                                        <span class="views-label views-label-rol">Rol dentro de la unidad de investigación: </span><?php echo $rol; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($grado_alcanzado)): ?>
                                <div class="field-grado_alcanzado">
                                    <p>
                                        <span class="views-label views-label-grado_alcanzado">Grado académico: </span><?php echo $grado_alcanzado; ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                        </div>
                        <?php if (!empty($biografia)): ?>
                            <div class="field-biografia">
                                <h2> Biografía </h2>
                                <p class="field-content-biografia"><?php echo $biografia; ?></p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                

                <div class="list-publicaciones col-md-12 ">


                    <div class="accordion" id="accordion_publicaciones">
                        <?php if (!empty($sedici)): ?>  
                            <div class="accordion-item card ">
                                <h2 class="accordion-header titulo-repo card-header" id="headingOne">
                                    <button class="accordion-button card-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Producción científica en SEDICI
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordion_publicaciones">
                                    <div class="accordion-body card-body">
                                        <?php echo do_shortcode('[get_publications  config="sedici"  author="' . $sedici . '" show_subtype=false show_author=true date=true max_results="1000" ]'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($cic)): ?>  
                            <div class="accordion-item card ">
                                <h2 class="accordion-header titulo-repo card-header" id="headingTwo">
                                    <button class="accordion-button card-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                        Producción científica en CIC
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordion_publicaciones">
                                    <div class="accordion-body card-body">
                                        <?php echo do_shortcode('[get_publications  config="cic"  author="' . $cic . '" show_subtype=false show_author=true date=true max_results="1000" ]'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($conicet)): ?>  
                            <div class="accordion-item card ">
                                <h2 class="accordion-header titulo-repo card-header" id="headingThree">
                                    <button class="accordion-button card-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        Producción científica en CONICET
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse " aria-labelledby="headingThree" data-bs-parent="#accordion_publicaciones">
                                    <div class="accordion-body card-body ">
                                        <?php echo do_shortcode('[get_publications  config="conicet"  author="' . $conicet . '" show_subtype=false show_author=true date=true max_results="1000" ]'); ?>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                        <?php
                            foreach ($other_repositories as $r) {
                                ?>
                                <div class="accordion-item card ">
                                <h2 class="accordion-header titulo-repo card-header" id="heading<?php echo $r['name']; ?>">
                                    <button class="accordion-button card-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $r['name']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $r['name']; ?>">
                                        Producción científica en <?php echo strtoupper($r['name']); ?>
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $r['name']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $r['name']; ?>" data-bs-parent="#accordion_publicaciones">
                                    <div class="accordion-body card-body">
                                    <?php echo do_shortcode('[get_publications  config="' . $r['name'] . '"  author="' . $this->the_personal_field($r['name']) . '"   show_subtype=false show_author=true date=true  max_results="1000""]'); ?>
                                    </div>
                                </div>
                            </div>
                                <?php

                            }
                        ?>
                    </div>
                </div>            
                <?php if (!empty($categorias)): ?>
                    <div class="list-categoria col-md-12">
                        <?php foreach ($categorias as $categoria): ?>
                            <span>
                                    <a href="<?php echo get_term_link($categoria); ?>"><?php echo get_term($categoria, "categorias")->name; ?></a> |
                            </span>
                        <?php endforeach; ?>
                        <?php
                        ?>
                    </div>

                <?php endif; ?>
        </article>




    </div><!-- #content .site-content -->
</div><!-- #primary .content-area -->