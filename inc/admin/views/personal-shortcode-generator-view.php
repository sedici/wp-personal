

<h1> Obtener Tag ID de una categoría </h1>

<div class="lista-categorias-personal">

    <?php foreach ($terms_array as $term): ?>
        <div class="elemento-categoria">
            <p> <?= $term->name . ' ' . $term->term_id ?></p>
    </div>
    <?php endforeach; ?>

</div>

<br>
<br>

<h1>Generar shortcode para una categoría</h1>

<form id="form-personal-gen-shortcode" method="post" enctype="multipart/form-data" onsubmit="procesar_formulario_personal(this); return false;">

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

    <br>
    <br>

    <button type="submit">Generar shortcode</button>

</form>

<div id="shortcode-resultante"></div>



