<?php

$post_type_name = 'personal';

$args = array(
    'post_type' => $post_type_name,
    'posts_per_page' => -1, 
);

$query = new WP_Query($args);

if ($query->have_posts()) {

    $terms_array = array();

    while ($query->have_posts()) {
        
        $query->the_post();

        $terms = get_the_terms( get_the_ID() , 'categorias');

        if ($terms && !is_wp_error($terms)) {

            foreach ($terms as $term) {
                
                if (!in_array($term->name, $terms_array)) {   
                    $terms_array[] = $term->name;
                }
            }        
        }
    }

    if($terms_array) {
       
    }
    else {
        echo 'No posee categorias creadas para personas!';
    }

    wp_reset_postdata();

}












   

   




?>