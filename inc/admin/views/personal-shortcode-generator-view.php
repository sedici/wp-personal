

<h1> Obtener Tag ID de una categoría </h1>

<ul class="lista-categorias">

    <?php foreach ($terms_array as $term): ?>
        <li class="elemento-categoria">
            <p> <?= $term->name . ' ' . $term->term_id ?></p>
        </li>
    <?php endforeach; ?>

</ul>

<br>
<br>

<h1>Generar shortcode para una categoría</h1>

<form method="post" action="<?php echo admin_url("admin-post.php"); ?>" enctype=multipart/form-data target="_self">

    <?php foreach ($terms_array as $term): ?>

        <?php 
            $term_name = $term->name;
            $term_id = $term->term_id;
        ?> 
        
        <input type="hidden" name="action" value="generate_shortcode_hook">

        <input type="radio" id="category_<?php echo $term_id; ?>" name="term_id" value="<?php echo $term_id; ?>" required>
        <label for="category_<?php echo $term_id; ?>"><?php echo $term_name; ?></label>

        <br>

    <?php endforeach; ?>

    <br>

    <label for="number_columns">Selecciona el numero de columnas:</label>
    <input type="number" id="number_columns" name="number_columns" min="1" max="4" required>

    <br>
    <br>

    <button type="submit">Generar shortcode</button>

</form>





