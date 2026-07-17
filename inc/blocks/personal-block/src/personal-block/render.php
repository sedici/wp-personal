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

// Execute the query. get_post_meta(get_the_ID(), $name, true);
$loop = new WP_Query($args);

if ($loop->have_posts()) {
    while ($loop->have_posts()) {
        $loop->the_post();
        $post_id = get_the_ID();
        $image = get_the_post_thumbnail_url($post_id, 'medium');

        // Mapeo dinámico de redes sociales idéntico al de list-personal
        $social_media = array(
            'google_scholar' => get_post_meta($post_id, "google_scholar", true),
            'research-gate' => get_post_meta($post_id, "researchgate", true),
            'orcid' => get_post_meta($post_id, "orcid", true),
            'linkedin' => get_post_meta($post_id, "linkedin", true),
            'facebook' => get_post_meta($post_id, "facebook", true),
            'twitter' => get_post_meta($post_id, "twitter", true),
            'instagram' => get_post_meta($post_id, "instagram", true),
        );

        $terms = get_the_terms( $post_id, 'categorias' );

        $cv = get_post_meta($post_id, 'curriculum_vitae', true);
        if (!empty($cv) && isset($cv['url'])) {
            $social_media['cv'] = $cv['url'];
        }
        $personas[] = array(
            'id'              => $post_id,
            'permalink'       => get_permalink($post_id),
            'title'           => get_the_title(),
            'image'           => !empty($image) ? $image : plugins_url() . "/wp-personal/assets/images/blank-profile.png",
            'grado_alcanzado' => get_post_meta($post_id, 'grado_alcanzado', true),
            'rol'             => !empty($terms) ? $terms[0]->name : '',
            'unidad'          => get_post_meta($post_id, 'unidad_de_investigacion', true),
            'social_media'    => $social_media,
        );
    }
    wp_reset_postdata();
}

// Check if there are posts to display.
if (!empty($personas)) {
    echo '<div ' . get_block_wrapper_attributes() . '>';
    $template_path = \Personal\PLUGIN_NAME_DIR . 'inc/frontend/views/list-personal.php';
    
    // Inyectamos de forma segura usando load_template
    load_template($template_path, false, array(
        'personas' => $personas,
        'columns'  => isset($attributes['columns']) ? $attributes['columns'] : 3,
    ));
    echo '</div>';
} else {
    // No posts found, display a fallback message.
    echo '<p ' . get_block_wrapper_attributes() . '>' . __('No hay personal para mostrar', 'personal-block') . '</p>';
}