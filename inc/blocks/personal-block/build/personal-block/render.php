<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$orderBy = $attributes['orderBy'];
list($orderby_key, $order_direction) = explode('-', $orderBy);

$args = array(
    'post_type' => 'personal',
    'posts_per_page' => -1, // Show all
    'orderby' => $orderby_key,
    'order' => strtoupper($order_direction),
);

if (!empty($attributes['categories'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'categorias',
            'field'    => 'term_id',
            'terms'    => $attributes['categories'],
        ),
    );
}

$loop = new WP_Query($args);

if ($loop->have_posts()) {
    echo '<div ' . get_block_wrapper_attributes() . '>';
    // The path to the new view is relative to the plugin root.
    // \Personal\PLUGIN_NAME_DIR is defined in personal.php
    include \Personal\PLUGIN_NAME_DIR . 'inc/frontend/views/list-personal-in-order.php';
    echo '</div>';
} else {
    echo '<p ' . get_block_wrapper_attributes() . '>' . __('No hay personal para mostrar', 'personal-block') . '</p>';
}

wp_reset_postdata();