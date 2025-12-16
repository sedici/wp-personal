<?php

namespace Personal\Inc\Core;

use Personal as PP;
use Personal\Inc\Admin as Admin;
use Personal\Inc\Frontend as Frontend;

/**
 * La clase que inicia el plugin
 * Define la internationalization, admin hooks y public  hooks del plugin
 *
 * @author  SEDICI - Ezequiel Manzur - Maria Marta Vila
 */
class Init
{
    /**
     * @var     string $plugin_name nombre del plugin
     */
    private $plugin_name;

    /**
     * @var     Loader $loader registra y ejecuta los  hooks del plugin.
     */
    private $loader;

    /**
     * @var      string $plugin_base_name El nombre que identifica el plugin
     */
    private $plugin_basename;

    /**
     * @var      string $version Version del plugin
     */
    private $version;

    /**
     *
     *
     * @var      string $version Text domain del plugin
     */
    private $plugin_text_domain;


    public function __construct()
    {
        $this->plugin_name = PP\PLUGIN_NAME;
        $this->version = PP\PLUGIN_VERSION;
        $this->plugin_basename = PP\PLUGIN_BASENAME;
        $this->plugin_text_domain = PP\PLUGIN_TEXT_DOMAIN;
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies()
    {
        $this->loader = new Loader();

        /**
         * Register Elementor Widgets
         */
        require_once(PP\PLUGIN_NAME_DIR . 'personal-block-elementor/init.php');

    }

    /**
     * Defina la configuración regional de este complemento para la internacionalización.
     */
    private function set_locale()
    {

        $plugin_i18n = new Internationalization_i18n($this->plugin_text_domain);

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**.
     * Registras todos los hooks para la seccion  admin del plugin
     *
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Admin\Admin($this->get_plugin_name(), $this->get_version(), $this->get_plugin_text_domain());


        // Permite cargar un archivo desde un formulario (Carga el cv del personal)
        $this->loader->add_action('post_edit_form_tag', $plugin_admin, 'update_edit_form');
        $this->loader->add_filter('post_thumbnail_html', $plugin_admin, 'wordpress_hide_feature_image', 10, 3);
        // obtengo los repositorios del plugin wp-dspace
        $this->loader->add_filter('get_repositorios', $plugin_admin, 'get_repositories_wpdspace');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        // crea el menu para la administracion.
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        // Registra el custom post personal
        $this->loader->add_action('init', $plugin_admin, 'cptui_register_my_cpts_personal', 20);
        // Registra las taxonomias del custom post personal
        $this->loader->add_action('init', $plugin_admin, 'cptui_register_my_taxes_categorias', 20);
        // Registra roles y capabilities
        $this->loader->add_action('admin_init', $plugin_admin, 'add_personal_caps', 20);

        // Agrega los campos meta al custom post personal
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'personal_custom_metabox');
        // Guarda los campos meta
        $this->loader->add_action('save_post', $plugin_admin, 'personal_save_metas');


        $this->loader->add_action('wp_ajax_generate_shortcode_personal', $plugin_admin, 'generate_shortcode_personal');

        $this->loader->add_action('init', $plugin_admin, 'create_block_personal_block_block_init');
    }

    function create_block_personal_block_block_init()
    {
        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
         * based on the registered block metadata.
         * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
         *
         * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
         */
        if (function_exists('wp_register_block_types_from_metadata_collection')) {
            wp_register_block_types_from_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');
            return;
        }

        /**
         * Registers the block(s) metadata from the `blocks-manifest.php` file.
         * Added to WordPress 6.7 to improve the performance of block type registration.
         *
         * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
         */
        if (function_exists('wp_register_block_metadata_collection')) {
            wp_register_block_metadata_collection(__DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php');
        }
        /**
         * Registers the block type(s) in the `blocks-manifest.php` file.
         *
         * @see https://developer.wordpress.org/reference/functions/register_block_type/
         */
        $manifest_data = require __DIR__ . '/build/blocks-manifest.php';
        foreach (array_keys($manifest_data) as $block_type) {
            register_block_type(__DIR__ . "/build/{$block_type}");
        }
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
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        // obtengo los repositorios del plugin wp-dspace
        $this->loader->add_filter('get_repositorios', $plugin_public, 'get_repositories_wpdspace');
        $this->loader->add_filter('the_title', $plugin_public, 'remove_personal_title', 10, 2);


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
