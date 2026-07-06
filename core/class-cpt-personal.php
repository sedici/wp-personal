<?php
namespace Personal\Core;

class CPT_personal
{
    public function __construct(){}

/**
     * Registra el Post Type Personal
     */

    public function cptui_register_my_cpts_personal()
    {
        /**
         * Post Type: Personal.
         */


        $labels = array(
            "name" => __("Personal", ""),
            "singular_name" => __("Persona", ""),
            "menu_name" => __("Personal", ""),
            "all_items" => __("Todo el Personal", ""),
            "add_new" => __("Agregar Personal", ""),
            "add_new_item" => __("Agregar nuevo Personal", ""),
            "edit_item" => __("Editar Personal", ""),
            "new_item" => __("Nuevo Personal", ""),
            "view_item" => __("Ver Personal", ""),
            "view_items" => __("Ver Personal", ""),
            "search_items" => __("Buscar Personal", ""),
            "not_found" => __("No se encontro el Personal", ""),
            "not_found_in_trash" => __("No se encontro el Personal en la papelera", ""),
            "parent_item_colon" => __("Personal Padre", ""),
            "featured_image" => __("Foto del Personal", ""),
            "set_featured_image" => __("Seleccionar la imagen", ""),
            "remove_featured_image" => __("Remover la imagen", ""),
            "use_featured_image" => __("Utilizar la imagen", ""),
            "archives" => __("Archivar al personal", ""),
            "insert_into_item" => __("Insert en Personal", ""),
            "uploaded_to_this_item" => __("Subir al personal", ""),
            "filter_items_list" => __("Filtrar lista de personal", ""),
            "items_list_navigation" => __("Navegación de la lista de personal", ""),
            "items_list" => __("Lista de Personal", ""),
            "attributes" => __("Atributos del Personal", ""),
            "parent_item_colon" => __("Personal Padre", ""),
        );

        $args = array(
            "label" => __("Personal", ""),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => false,
            "rest_base" => "",
            "has_archive" => "personal",
            "show_in_menu" => true,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "capabilities" => array(
                'create_posts' => 'create_personal',
                'delete_others_posts' => 'delete_others_personales',
                'delete_private_posts' => 'delete_private_personales',
                'delete_published_posts' => 'delete_published_personales',
                'edit_private_posts' => 'edit_private_personales',
                'edit_published_posts' => 'edit_published_personales',
                'edit_post' => 'edit_personal',
                'edit_posts' => 'edit_personales',
                'edit_others_posts' => 'edit_other_personales',
                'publish_posts' => 'publish_personales',
                'read_post' => 'read_personal',
                'read_private_posts' => 'read_private_personales',
                'delete_post' => 'delete_personal'
            ),
            "map_meta_cap" => true,
            "hierarchical" => false,
            "rewrite" => array("slug" => "personal", "with_front" => true),
            "query_var" => true,
            "supports" => array("title", "thumbnail", "page-attributes"),
            "taxonomies" => array("categorias"),
        );

        register_post_type("personal", $args);
    }



    public function cptui_register_my_taxes_lineas_de_investigacion()
    {
        /**
         * Taxonomy: Lineas de investigacion.
         */

        $labels = array(
            "name" => __("Lineas de investigacion", ""),
            "singular_name" => __("Linea de investigacion", ""),
        );

        $args = array(
            "label" => __("Lineas de investigacion", ""),
            "labels" => $labels,
            "public" => true,
            "hierarchical" => true,
            "label" => "Lineas de investigacion",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array('slug' => 'lineas_de_investigacion', 'with_front' => true, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "show_in_quick_edit" => true,
        );
        register_taxonomy("lineas_de_investigacion", array("personal"), $args);
    }

    /**
     * Registra las taxonomias para el Post Type personal
     */
    public function cptui_register_my_taxes_categorias()
    {
        /**
         * Taxonomy: Categorias.
         */

        $labels = array(
            "name" => __("Categorias", ""),
            "singular_name" => __("Categoria", ""),
        );

        $args = array(
            "label" => __("Categorias", ""),
            "labels" => $labels,
            "public" => true,
            "hierarchical" => true,
            "label" => "Categorias",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array('slug' => 'categorias', 'with_front' => true, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "show_in_quick_edit" => true,
        );
        register_taxonomy("categorias", array("personal"), $args);
    }

    /**
     * Agrega las capabilites para editar el custom post.
     */
    public function add_personal_caps()
    {
        // gets the administrator role
        // FIXME Evaluar si agregar un rol personal.
        $admins = get_role('administrator');
        $admins->add_cap('create_personal');
        $admins->add_cap('delete_private_personales');
        $admins->add_cap('delete_others_personales');
        $admins->add_cap('delete_published_personales');
        $admins->add_cap('edit_published_personales');
        $admins->add_cap('edit_personal');
        $admins->add_cap('edit_personales');
        $admins->add_cap('publish_personales');
        $admins->add_cap('read_personal');
        $admins->add_cap('delete_personal');
        $admins->add_cap('edit_private_personales');
        $admins->add_cap('edit_other_personales');
        $admins->add_cap('read_private_personales');

    }

}