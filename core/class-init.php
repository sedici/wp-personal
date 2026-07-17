<?php

namespace Personal\Core;

use Personal as PP;
use Personal\Admin\Admin as Admin;
use Personal\Inc\Frontend as Frontend;
use Personal\Admin\Metaboxes as Metaboxes;
use Personal\Admin\CSV_Handler as CSV_Handler;
use Personal\Core\Internationalization_i18n as Internationalization_i18n;
use Personal\Core\Loader as Loader;
use Personal\Core\CPT_personal as CPT_personal;



/**
 * La clase que inicia el plugin
 * Define la internationalization, admin hooks y public  hooks del plugin
 *
 * @author  SEDICI - Ezequiel Manzur - Maria Marta Vila
 */
class Init
{
    private string $plugin_name;
    private string $version;
    private string $plugin_text_domain;
    private Loader $loader;

    public function __construct()
    {
        $this->plugin_name        = PP\PLUGIN_NAME;
        $this->version            = PP\PLUGIN_VERSION;
        $this->plugin_text_domain = PP\PLUGIN_TEXT_DOMAIN;

        $this->loader = new Loader();
        $this->load_dependencies();
        $this->register_cpt();
        //$this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->loader->add_action('init', $this, 'register_gutenberg_blocks');
    }



    private function register_cpt()
    {
        $cpt_personal = new CPT_personal();
        
        $this->loader->add_action('init', $cpt_personal, 'cptui_register_my_cpts_personal', 20);
        $this->loader->add_action('init', $cpt_personal, 'cptui_register_my_taxes_categorias', 20);
        $this->loader->add_action('init', $cpt_personal, 'cptui_register_my_taxes_lineas_de_investigacion', 20);
        $this->loader->add_action('admin_init', $cpt_personal, 'add_personal_caps', 20);


    }

    


    private function load_dependencies()
    {
        $this->loader = new Loader();

        /**
         * Register Elementor Widgets
         */
        require_once(PP\PLUGIN_NAME_DIR . 'inc/blocks/personal-block-elementor/init.php');

    }


    /**
     * Registra los bloques nativos de Gutenberg usando el manifest.
     * Nota: Debe ser public para que el Loader pueda ejecutarlo.
     */
    public function register_gutenberg_blocks()
    {
        // IMPORTANTE: Asegúrate de que 'personal-block/build' coincide con la ruta de tu carpeta
        $build_path = PP\PLUGIN_NAME_DIR . 'inc/blocks/personal-block/build'; 
        $manifest_path = $build_path . '/blocks-manifest.php';

        if (!file_exists($manifest_path)) {
            return; // Evita errores si la carpeta o el manifest no existen
        }

        // WordPress 6.8+
        if (function_exists('wp_register_block_types_from_metadata_collection')) {
            wp_register_block_types_from_metadata_collection($build_path, $manifest_path);
            return;
        }

        // WordPress 6.7+
        if (function_exists('wp_register_block_metadata_collection')) {
            wp_register_block_metadata_collection($build_path, $manifest_path);
        }

        // Fallback para versiones anteriores
        $manifest_data = require $manifest_path;
        foreach (array_keys($manifest_data) as $block_type) {
            register_block_type($build_path . "/{$block_type}");
        }
    }



     private function define_admin_hooks(): void
    {
        $admin = new Admin($this->plugin_name, $this->version, $this->plugin_text_domain);


        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu',            $admin, 'add_plugin_admin_menu');
        $this->loader->add_action('post_edit_form_tag',    $admin, 'update_edit_form');


        //Shortcode generator (AJAX para el admin, no el shortcode público)
        $this->loader->add_action('wp_ajax_generate_shortcode_personal', $admin, 'generate_shortcode_personal');

        //Metaboxes
        $metaboxes = new Metaboxes();
        $this->loader->add_action('add_meta_boxes', $metaboxes, 'register');
        $this->loader->add_action('save_post',      $metaboxes, 'save');

        //CSV
        $this->loader->add_action('wp_ajax_import_csv',$admin, 'import_csv');
        $this->loader->add_action('admin_post_export_personal_csv', $admin, 'export_personal_csv');
    }


    /**
     * Defina la configuración regional de este complemento para la internacionalización.
     */
    private function set_locale()
    {

        $plugin_i18n = new Internationalization_i18n($this->plugin_text_domain);

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }



    /**
     * Registras todos los hooks para la sección pública del plugin
     *
     */
    private function define_public_hooks()
    {

        $plugin_public = new Frontend\Frontend($this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain());
        // Registra el hook para la vista del template personal
        $this->loader->add_filter('the_content', $plugin_public, 'single_personal_template');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_filter('post_thumbnail_html', $plugin_public, 'wordpress_hide_feature_image', 10,3);
        $this->loader->add_filter('the_title', $plugin_public, 'remove_personal_title', 10, 2);

        $shortcode = new Frontend\Shortcode();
        $this->loader->add_action('init', $shortcode, 'register_shortcodes');


    }


    /**
     * Ejecuta todos los hooks e wordpress
     */
    public function run()
    {
        $this->loader->run();
    }

    public function get_plugin_name()
    {
        return $this->plugin_name;
    }


    public function get_loader()
    {
        return $this->loader;
    }


    public function get_version()
    {
        return $this->version;
    }

    public function get_plugin_text_domain()
    {
        return $this->plugin_text_domain;
    }

}

