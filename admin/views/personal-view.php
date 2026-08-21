<?php
/**
 * Vista para el metabox de Personal.
 * 
 * @var WP_Post $post Objeto del post actual de WordPress.
 * @var Personal\Admin\Metaboxes $this Instancia de la clase Metaboxes.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Se fusiona la configuración por defecto de los campos con los valores
 * reales guardados en la base de datos para este post específico.
 */
$fields = [];
foreach ($this->getInputsPersonal() as $item) {
    if (isset($item['repositories'])) {
        $repositories = [];
        foreach ($item['repositories'] as $repository) {
            $repository['default_value'] = get_post_meta($post->ID, $repository['name'], true);
            $repositories[] = $repository;
        }
        $fields[] = ['repositories' => $repositories];
    } else {
        $meta_val = get_post_meta($post->ID, $item['name'], true);
        if (!empty($meta_val)) {
            $item['default_value'] = $meta_val;
        }
        $fields[] = $item;
    }
}
?>

<div class="inptuts-personal">
    <?php wp_nonce_field('mi_meta_box_nonce', 'meta_box_nonce'); ?>

    <?php
    /**
     * Se recorre cada campo y se delega la impresión a la plantilla 'field.php'.
     */
    foreach ($fields as $field) :
        if (isset($field['repositories'])) :
            foreach ($field['repositories'] as $repository) :
                $args = $repository;
                include __DIR__ . '/partials/field.php';
            endforeach;
        else :
            $args = $field;
            include __DIR__ . '/partials/field.php';
        endif;
    endforeach;
    ?>
</div>
