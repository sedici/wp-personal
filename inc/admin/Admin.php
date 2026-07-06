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
        wp_register_script('personal-admin-js', plugin_dir_url(__FILE__) . 'js/personal-admin.js', array('jquery'), '1', false);
        wp_enqueue_script('personal-admin-js');
        wp_localize_script('personal-admin-js', 'personal_ajax_object', array('url' => admin_url('admin-ajax.php')));
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


        add_submenu_page(
            'edit.php?post_type=personal',
            __('Importar personal', $this->plugin_text_domain), //page title
            __('Importar personal', $this->plugin_text_domain), //menu title
            'manage_options',
            'import-personal',
            array($this, 'show_csv_importer_view'),
        );

    }

    /**
     * Valida e importa un csv para crear o actualizar personal
     * 
     * @return void
     */
    public function import_csv()
    {

        // Validacion de nonce
        if (!isset($_POST['personal_csv_import_nonce']) || !wp_verify_nonce($_POST['personal_csv_import_nonce'], 'personal_csv_import')) {
            wp_send_json_error('Error de seguridad: el token de sesión ha expirado. Por favor, recarga la página.');
        }

        // Validacion de permisos
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Error: no tienes permisos suficientes para realizar esta acción.');
        }

        // Validacion del archivo subido
        if (!isset($_FILES['personal_csv_file']) || $_FILES['personal_csv_file']['error'] !== 0) {
            wp_send_json_error('Error : no se subio ningun archivo');
        }

        $file = $_FILES['personal_csv_file'];

        try {
            $csv_importer = new Csv_Importer($file);
            $results = $csv_importer->process_csv();
            wp_send_json_success($results);
        } catch (\Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }


    public function get_personal_terms()
    {

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
                $terms = get_the_terms(get_the_ID(), 'categorias');

                if ($terms && !is_wp_error($terms)) {

                    foreach ($terms as $term) {

                        //Evito guardar terminos repetidos
                        if (!in_array($term->name, $terms_name_array)) {
                            array_push($terms_name_array, $term->name);
                        }
                    }

                }

            }

            // Si ningun post tenia una categoria asociada, $terms_array quedará vacio
            $terms_array = array();

            if (!empty($terms_name_array)) {

                foreach ($terms_name_array as $term_name) {
                    $terms_array[] = get_term_by('name', $term_name, 'categorias');
                }
            }

            wp_reset_postdata();
        }

        return $terms_array;
    }

    public function show_terms()
    {

        include_once dirname(__DIR__) . '/admin/views/personal-shortcode-generator-view.php';

    }

    public function show_csv_importer_view()
    {
        include_once dirname(__DIR__) . '/admin/views/csv-importer-view.php';
    }




    public function generate_shortcode_personal()
    {

        $shortcode = "";

        $form_data = $_POST['formulario_data'];

        // Guardo los input del formulario en un array
        $form_data_array = explode("&", $form_data);


        //Usando expresiones regulares obtengo los valores numericos de los campos del formulario
        $diccionario_shortcode = array(
            "term_id_selected" => preg_replace('/[^0-9]/', '', $form_data_array[0]),
            "columns" => preg_replace('/[^0-9]/', '', $form_data_array[1]),
        );

        //Verifica que term_id y columns sea valido

        if (
            !empty($diccionario_shortcode['term_id_selected']) &&
            !empty($diccionario_shortcode['columns']) &&
            ($diccionario_shortcode['columns'] >= 1 && $diccionario_shortcode['columns'] <= 4)
        ) {

            $shortcode = "[list-personal category_id=" . $diccionario_shortcode['term_id_selected'] . " columns=" . $diccionario_shortcode['columns'] . "]";

            echo $shortcode;

        } else {
            echo 'Ocurrio un error';
        }

        wp_die();
    }



    public function get_repositories_wpdspace($value)
    {
        $this->repositories = $value;
        $this->initializeInputsPersonal();
        return $value;
    }



    /**
     * Redirect
     *
     * @since    1.0.0
     */
    public function custom_redirect($admin_notice, $response, $path = "")
    {
        wp_redirect(esc_url_raw(add_query_arg(
            array(
                'personal_admin_add_notice' => $admin_notice,
                'personal_response' => $response,
            ),
            admin_url('admin.php?page=' . $this->plugin_name . $path)  //Fixme agregar url para redirigir.
        )));

    }

    /**
     * Permite manipular archivos en un formulario (Se usa para guardar el cv del personal)
     */
    public function update_edit_form()
    {
        echo 'enctype="multipart/form-data"';
    }

    /**
     * no muestra la imagen destacada del post personal.
     */
    function wordpress_hide_feature_image($html, $post_id, $post_image_id)
    {
        return (is_single() and get_post_type() == 'personal') ? '' : $html;
    }
   
    private function getRepositories()
    {
        return array_filter(
            $this->repositories,
            function ($repo) {
                return !(strtolower($repo['name']) == 'sedici' or strtolower($repo['name']) == 'conicet' or strtolower($repo['name']) == 'cic');
            }
        );

    }

    public function create_block_personal_block_block_init()
    {
        $build_dir = \Personal\PLUGIN_NAME_DIR . 'personal-block/build';
        $manifest = $build_dir . '/blocks-manifest.php';
        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
         * based on the registered block metadata.
         * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
         *
         * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
         */
        if (function_exists('wp_register_block_types_from_metadata_collection')) {
            wp_register_block_types_from_metadata_collection($build_dir, $manifest);
            return;
        }

        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` file.
         * Added to WordPress 6.7 to improve the performance of block type registration.
         *
         * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
         */
        if (function_exists('wp_register_block_metadata_collection')) {
            wp_register_block_metadata_collection($build_dir, $manifest);
        }
        /**
         * Registers the block type(s) in the `blocks-manifest.php` file.
         *
         * @see https://developer.wordpress.org/reference/functions/register_block_type/
         */
        $manifest_data = require $manifest;
        foreach (array_keys($manifest_data) as $block_type) {
            register_block_type($build_dir . "/{$block_type}");
        }
    }

}
