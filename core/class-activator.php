<?php

namespace Personal\Core;

/**
 * Esta clase define todo el código que se ejecutará durante la activación del complemento.
 * @author  SEDICI - Ezequiel Manzur - Maria Marta Vila
 */
class Activator
{


    public static function activate()
    {
        // El plugin wp-dspace-v2 es una dependencia blanda: personal se puede
        // activar sin él, y la vista single avisa que hay que activarlo para
        // mostrar las publicaciones de los repositorios.
        $args = array('post_type' => 'personal');
        $loop = new \WP_Query($args);
        while ($loop->have_posts()) :
            $loop->the_post();
            $image = get_post_meta(get_the_ID(), 'foto', true);
            if (!empty($image)) {
                set_post_thumbnail(get_the_ID(), $image);
//                delete_post_meta(get_the_ID(), 'foto');
            }
        endwhile;
    }

}
