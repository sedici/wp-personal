

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

<form method="post" action="procesar_formulario.php">

    <?php foreach ($terms_array as $term): ?>
        <?php 
            $term_name = $term->name;
            $term_id = $term->term_id;
        ?> 
        <input type="checkbox" id="category_<?php echo $term_id; ?>" name="selected_categories[]" value="<?php echo $term_id; ?>">
        <label for="category_<?php echo $term_id; ?>"><?php echo $term_name; ?></label>
        <br>
    <?php endforeach; ?>

    <br>
    <br>
    <button type="submit">Generar shortcode</button>

</form>





