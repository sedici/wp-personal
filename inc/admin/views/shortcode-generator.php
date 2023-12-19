<?php

//Esto deberia ser una clase o una funcion en una clase

$post_type_name = 'personal';

$args = array(
    'post_type' => $post_type_name,
    'posts_per_page' => -1, 
);

$query = new WP_Query($args);

// Guardo en $terms_name_array los terminos/categorias de los posts de personal (no guardo categorias repetidas)
if ($query->have_posts()) {

    $terms_name_array = array();

    while ($query->have_posts()) {
        
        $query->the_post();

        $terms = get_the_terms( get_the_ID() , 'categorias');

        if ($terms && !is_wp_error($terms)) {

            foreach ($terms as $term) {
                
                if (!in_array($term->name, $terms_name_array)) {   
                    $terms_name_array[] = $term->name;
                }
            }        
        }
    }

    // Es posible obtener el WP_Term usando get_term_by()
    if($terms_name_array) {
        
        // Aca podria meter en variables o algo todo lo que quiero mostrar para enviarlo al template
        $terms_array = array();

        foreach( $terms_name_array as $term_name) {
            $terms_array[] = get_term_by('name', $term_name, 'categorias');
        }

        include_once dirname(__DIR__) . '/views/personal-shortcode-generator-view.php';
    }
    else {
        echo 'No posee categorias creadas para personas!';
    }

    wp_reset_postdata();

}





?>