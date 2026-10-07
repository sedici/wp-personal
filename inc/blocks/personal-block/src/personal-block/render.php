<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * La consulta y la elección del layout están en Render_Personal, compartida con el
 * widget de Elementor.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$render = new \Personal\Inc\Render\Render_Personal();

printf( '<div %s>%s</div>', get_block_wrapper_attributes(), $render->render( array(
    'layout'     => $attributes['layout'] ?? 'carta',
    'columns'    => $attributes['columns'] ?? 3,
    'campos'     => $attributes['campos'] ?? null,
    'categories' => $attributes['categories'] ?? array(),
    'orderBy'    => $attributes['orderBy'] ?? 'date-desc',
) ) );
