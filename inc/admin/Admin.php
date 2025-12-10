<?php
namespace Personal\Inc\Admin;


/**
 * Define la funcionalidad del área admin
 *
 * @author  SEDICI - Ezequiel Manzur - Maria Marta Vila
 */
class Admin
{


    private $plugin_name;


    private $version;

    private $plugin_text_domain;

    /**
     * Define los campos personalizados del custom post personal
     * @var array() $inputs_personal
     */
    private $inputs_personal;

    /**
     * Son las configuraciones del repositorio obtenidas del plugin wp-dspace
     * @var array() $repositories
     */
    private $repositories;

    public function __construct($plugin_name, $version, $plugin_text_domain)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->plugin_text_domain = $plugin_text_domain;
//        $this->initializeInputsPersonal();
    }

    /**
     * Registra los estilos css para el admin
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/personal-admin.css', array(), $this->version, 'all');
    }

    /**
     * Registra los javascript para el admin
     */
    public function enqueue_scripts()
    {
        //wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/personal-admin.js', $this->version, false);
        wp_register_script('personal-admin-js', plugin_dir_url(__FILE__) . 'js/personal-admin.js', array('jquery'), '1', false );
        wp_enqueue_script('personal-admin-js');
        wp_localize_script('personal-admin-js','personal_ajax_object', array('url' => admin_url( 'admin-ajax.php' ) ));
    }

    /**
     * Administra el menu en el área admin
     */
    public function add_plugin_admin_menu()
    {
        
        //usar add_menu_page(), add_submenu_page(), etc.

        ## Agregar subpágina Generar shortcode

		$ajax_form_page_hook = add_submenu_page(
            'edit.php?post_type=personal',
            __('Generar shortcode', $this->plugin_text_domain), //page title
            __('Generar shortcode', $this->plugin_text_domain), //menu title
            'manage_options', //capability
            'get-personal-tag-id', //menu_slug
            array($this, 'show_terms')// página que va a manejar la sección
        );

    }


    public function get_personal_terms() {

        $post_type_name = 'personal';

        $args = array(
            'post_type' => $post_type_name,
            'posts_per_page' => -1, 
        );

        $query = new \WP_Query($args);

        // Si ningun post tenia una categoria asociada, $terms_name_array quedará vacio
        $terms_name_array = array();
        
        // Guardo en $terms_name_array los terminos de los posts de personal
        if ($query->have_posts()) {

            while ($query->have_posts()) {

                $query->the_post();

                // Obtiene los terminos del post asociado
                $terms = get_the_terms( get_the_ID() , 'categorias');

                if ($terms && !is_wp_error($terms)) {

                    foreach ($terms as $term) {
                        
                        //Evito guardar terminos repetidos
                        if (!in_array($term->name, $terms_name_array)) {  
                            array_push($terms_name_array,$term->name); 
                        }
                    }

                } 

            }

            // Si ningun post tenia una categoria asociada, $terms_array quedará vacio
            $terms_array = array();

            if(!empty($terms_name_array)) {

                foreach( $terms_name_array as $term_name) {
                    $terms_array[] = get_term_by('name', $term_name, 'categorias');
                }
            }
        
            wp_reset_postdata();
        }

        return $terms_array;
    }

    public function show_terms () {
        
        include_once dirname(__DIR__) . '/admin/views/personal-shortcode-generator-view.php';
        
    }

    

    public function generate_shortcode_personal() {

        $shortcode = "";
        
        $form_data = $_POST['formulario_data'];

        // Guardo los input del formulario en un array
        $form_data_array = explode("&",$form_data);


        //Usando expresiones regulares obtengo los valores numericos de los campos del formulario
        $diccionario_shortcode = array(
            "term_id_selected" => preg_replace('/[^0-9]/', '', $form_data_array[0]),
            "columns" => preg_replace('/[^0-9]/', '', $form_data_array[1]),
        );

        //Verifica que term_id y columns sea valido

        if( !empty($diccionario_shortcode['term_id_selected']) && 
            !empty($diccionario_shortcode['columns']) && 
            ($diccionario_shortcode['columns'] >=1 && $diccionario_shortcode['columns'] <= 4) ) {
            
            $shortcode = "[list-personal category_id=" . $diccionario_shortcode['term_id_selected'] . " columns=" . $diccionario_shortcode['columns'] . "]" ;
            
            echo $shortcode;
             
        }
        else {
            echo 'Ocurrio un error';
        }
        
        wp_die();
    }



    public function get_repositories_wpdspace($value){
        $this->repositories= $value;
        $this->initializeInputsPersonal();
        return $value;
    }



    /**
     * Redirect
     *
     * @since    1.0.0
     */
    public function custom_redirect( $admin_notice, $response, $path = "" ) {
        wp_redirect( esc_url_raw( add_query_arg( array(
            'personal_admin_add_notice' => $admin_notice,
            'personal_response' => $response,
        ),
            admin_url('admin.php?page='. $this->plugin_name . $path )  //Fixme agregar url para redirigir.
        ) ) );

    }

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
            "supports" => array("title", "thumbnail"),
            "taxonomies" => array("categorias"),
        );

        register_post_type("personal", $args);
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
            "rewrite" => array('slug' => 'categorias', 'with_front' => true,),
            "show_admin_column" => true,
            "show_in_rest" => false,
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
    /**
     * Agrega los campos personalizados para el custom post.
     */
    public function personal_custom_metabox()
    {
        add_meta_box('personal_meta', __('Información del personal', 'personal'), array($this, 'personal_display_callback'), 'personal');
    }
    /**
     * Formulario custom post
     */
    public function personal_display_callback($post)
    {
        include_once('views/personal-view.php');
    }
    /**
     * Guarda los campos personalizados del post
     */
    public function personal_save_metas($idpersonal)
    {
        $personal = get_post($idpersonal);

        if ($personal->post_type == 'personal') {
            $inputs= $this->getInputsPersonal();
            foreach ( $inputs as $input) {

                if ( isset($input['name'])  and isset($_POST[$input['name']]) )
                    update_post_meta($idpersonal, $input['name'], $_POST[$input['name']]);
                if(isset($input['repositories'])){
                    foreach ($input['repositories'] as $repository) {
                        if (isset($_POST[$repository['name']]))
                            update_post_meta($idpersonal, $repository['name'], $_POST[$repository['name']]);
                    }
                }
            }
            if(!empty($_FILES['curriculum_vitae']['name'])) {
                $supported_types = array('application/pdf');
                $arr_file_type = wp_check_filetype(basename($_FILES['curriculum_vitae']['name']));
                $uploaded_type = $arr_file_type['type'];

                if(in_array($uploaded_type, $supported_types)) {
                    $upload = wp_upload_bits($_FILES['curriculum_vitae']['name'], null, file_get_contents($_FILES['curriculum_vitae']['tmp_name']));
                    if(isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                    } else {
                        update_post_meta($idpersonal, 'curriculum_vitae', $upload);
                    }
                }
                else {
                    wp_die("The file type that you've uploaded is not a PDF.");
                }
            }
        }
    }


    public function getInputsPersonal()
    {
        return $this->inputs_personal;
    }


    /**
     * Permite manipular archivos en un formulario (Se usa para guardar el cv del personal)
     */
    public function update_edit_form() {
        echo 'enctype="multipart/form-data"';
    }

    /**
     * no muestra la imagen destacada del post personal.
     */
    function wordpress_hide_feature_image( $html, $post_id, $post_image_id ) {
        return (is_single() and get_post_type() == 'personal') ? '' : $html;
    }
    private function initializeInputsPersonal()
    {
//        add_filter('get_repositorios',$this,'get_repositories_wpdspace',1);
        $repositories = array(array(
            'key' => 'field_59dd247f52528',
            'label' => '<img src="' . plugins_url() . '/personal/assets/images/sedici.png"	height="32"> SEDICI',
            'name' => 'sedici',
            'type' => 'text',
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'formatting' => 'none',
            'maxlength' => '',
        ),
            array(
                'key' => 'field_59dd24ac52529',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/cic_digital.png" height="32"> CIC',
                'name' => 'cic',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'none',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd24b65252a',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/conicet-digital.png" height="40"> CONICET',
                'name' => 'conicet',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'none',
                'maxlength' => '',
            ));
        $repositories_custom = $this->getRepositories();
        foreach ( $repositories_custom as $r) {
            $repo = array(
                'key' => $r['id'],
                'label' => strtoupper($r['name']),
                'name' => $r['name'],
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'none',
                'maxlength' => '',
            );
            array_push($repositories,$repo);
        }
        $this->inputs_personal = array(
            array(
                'class' => '',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/email.png" height="32"> Email ',
                'name' => 'email',
                'type' => 'email',
                'instructions' => 'Correo electrónico',
                'default_value' => '',
                'placeholder' => 'Email',
            ),
            array(
                'class' => '',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/tel.png" height="32">	Teléfono',
                'name' => 'telefono',
                'type' => 'text',
                'instructions' => 'Teléfono',
                'default_value' => '',
                'placeholder' => '0221-11111111',
            ),
            array(
                'key' => 'field_59dd232d52523',
                'label' => 'Unidad de investigación',
                'name' => 'unidad_de_investigacion',
                'type' => 'text',
                'instructions' => 'Unidad de investigación a la que pertenece',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd232d52das',
                'label' => 'Rol',
                'name' => 'rol_unidad_de_investigacion',
                'type' => 'text',
                'instructions' => 'Rol que cumple en la unidad de investigación a la que pertenece',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd235252524',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/grado_alcanzado.png" height="32">	 Grado Alcanzado',
                'name' => 'grado_alcanzado',
                'type' => 'text',
                'instructions' => 'Grado alcanzado',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd238952525',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/google_scholar.png" width="32" height="32"> Google Scholar ',
                'name' => 'google_scholar',
                'type' => 'text',
                'instructions' => 'http://scholar.google.com/citations?user=xxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd241852526',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/orcid.gif" width="32" height="32"> ORCID',
                'name' => 'orcid',
                'type' => 'text',
                'instructions' => 'https://orcid.org/xxxx-xxxx-xxxx-xxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f52527',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/research-gate.png" width="32" height="32"> ResearchGate',
                'name' => 'researchgate',
                'type' => 'text',
                'instructions' => 'https://www.researchgate.net/profile/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f534434',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/linkedin.png" width="32" height="32"> Linkedin',
                'name' => 'linkedin',
                'type' => 'text',
                'instructions' => 'https://www.linkedin.com/in/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f5343',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/facebook.jpg" width="32" height="32"> Facebook',
                'name' => 'facebook',
                'type' => 'text',
                'instructions' => 'https://www.facebook.com/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_59dd244f5434334',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/twitter.png" width="32" height="32"> Twitter',
                'name' => 'twitter',
                'type' => 'text',
                'instructions' => 'https://twitter.com/xxxxxxx',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array('repositories' => $repositories),
            array(
                'key' => 'field_59dd25596cb02',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/biography.png" height="32">	Biografía',
                'name' => 'biografia',
                'type' => 'wp_editor',
                /*'size'=>'15',
                'maxlength' =>'30',*/
                'default_value' => '',
                'toolbar' => 'full',
                'media_upload' => 'yes',
            ),
            array(
                'key' => 'field_59dd25736cb03',
                'label' => '<img src="' . plugins_url() . '/personal/assets/images/cv.png" height="32">	Curriculum Vitae',
                'name' => 'curriculum_vitae',
                'type' => 'file',
                'save_format' => 'object',
                'library' => 'all',
            )
        );
    }

    private function getRepositories()
    {
       return array_filter(
            $this->repositories,
            function ($repo)  {
                return !(strtolower($repo['name']) == 'sedici' or strtolower($repo['name']) == 'conicet' or strtolower($repo['name']) == 'cic'  );
            }
        );

    }

    public function create_block_personal_block_block_init() {
        $build_dir = \Personal\PLUGIN_NAME_DIR . 'personal-block/build';
        $manifest = $build_dir . '/blocks-manifest.php';
        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
         * based on the registered block metadata.
         * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
         *
         * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
         */
        if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
            wp_register_block_types_from_metadata_collection( $build_dir, $manifest );
            return;
        }
    
        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` file.
         * Added to WordPress 6.7 to improve the performance of block type registration.
         *
         * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
         */
        if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
            wp_register_block_metadata_collection( $build_dir, $manifest );
        }
        /**
         * Registers the block type(s) in the `blocks-manifest.php` file.
         *
         * @see https://developer.wordpress.org/reference/functions/register_block_type/
         */
        $manifest_data = require $manifest;
        foreach ( array_keys( $manifest_data ) as $block_type ) {
            register_block_type( $build_dir . "/{$block_type}" );
        }
    }

}
