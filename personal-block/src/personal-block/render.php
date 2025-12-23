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

// Parse the orderBy attribute into key and direction.
// Expected format: 'field-direction', e.g., 'date-desc' or 'title-asc'.
$orderBy = $attributes['orderBy'];
list($orderby_key, $order_direction) = explode('-', $orderBy);

// Build the WP_Query arguments to fetch 'personal' post type.
$args = array(
    'post_type' => 'personal',
    'posts_per_page' => -1, // Show all posts
    'orderby' => $orderby_key,
    'order' => strtoupper($order_direction),
);

// If categories are selected, filter the query by those terms.
if (!empty($attributes['categories'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'categorias',
            'field' => 'term_id',
            'terms' => $attributes['categories'],
        ),
    );
}

// Execute the query.
$loop = new WP_Query($args);

// Check if there are posts to display.
if ($loop->have_posts()) {
    echo '<div ' . get_block_wrapper_attributes() . '>';
    // The path to the new view is relative to the plugin root.
    // \Personal\PLUGIN_NAME_DIR is defined in personal.php
    // Include the view file to render the list of personal items.
    include \Personal\PLUGIN_NAME_DIR . 'inc/frontend/views/list-personal-in-order.php';
    echo '</div>';
} else {
    // No posts found, display a fallback message.
    echo '<p ' . get_block_wrapper_attributes() . '>' . __('No hay personal para mostrar', 'personal-block') . '</p>';
}

// Restore original post data.
wp_reset_postdata();