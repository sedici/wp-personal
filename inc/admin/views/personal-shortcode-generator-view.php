<?php 

use Personal\Inc\Admin as Admin;
use Personal as PP;

$plugin_admin = new Admin\Admin(PP\PLUGIN_NAME , PP\PLUGIN_VERSION , PP\PLUGIN_TEXT_DOMAIN);

$terms_array = $plugin_admin->get_personal_terms();

?> 

<h1> Tags ID de las categorías </h1>

<div class="lista-categorias-personal">

    <?php if(!empty($terms_array)) {

        foreach ($terms_array as $term): ?>

            <div class="elemento-categoria">
                <p> <?php echo $term->name . ' --> ' . $term->term_id ?> </p>
            </div>

    <?php endforeach; } 
        else { ?> <div class="elemento-categoria" > No hay ningun post con categoria asignada! </div> <?php  } ?>

</div>

<br>

<h1>Generar shortcode para una categoría</h1>

<div class="lista-categorias-personal"> 

<?php if(empty($terms_array)) { ?>

    <div class="elemento-categoria" > No hay ningun post con categoria asignada! </div>

<?php

}

else { ?>

    <br>

    <form id="form-personal-gen-shortcode" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" enctype="multipart/form-data" onsubmit="procesar_formulario_personal(this); return false;">

        <?php foreach ($terms_array as $term): ?>

            <?php 
                $term_name = $term->name;
                $term_id = $term->term_id;
            ?> 
        

            <input type="radio" id="category_<?php echo $term_id; ?>" name="term_id" value="<?php echo $term_id; ?>" required>
            <label for="category_<?php echo $term_id; ?>"><?php echo $term_name; ?></label>

            <br>

        <?php endforeach; ?>

        <br>

        <label for="number_columns">Selecciona el numero de columnas:</label>
        <input type="number" id="number_columns" name="number_columns" min="1" max="4" required>

        <input type="hidden" name="action" value="generate_shortcode_personal">

        <br>
        <br>

        <button id="button-gen-shortcode" type="submit">Generar shortcode</button>

    </form>

    <div id="shortcode-resultante"></div>

    </div>

<?php

} 





