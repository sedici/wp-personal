<?php

namespace Personal\Inc\Frontend;

/**
 *
 * Carga la vista pública del complemento
 *
 * @author   SEDICI - Ezequiel Manzur - Maria Marta Vila
 */

class Frontend {


	private $plugin_name;


	private $version;


	private $plugin_text_domain;	


	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

	}

	/**
	 * Registra las hojas de estilo la la parte pública del sitio
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/personal-frontend.css', array(), $this->version, 'all' );
        $style = 'bootstrap';
        if( ( ! wp_style_is( $style, 'queue' ) ) && ( ! wp_style_is( $style, 'done' ) ) ) {
            //queue up your bootstrap
            wp_enqueue_style( $style, plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        }
	}

	/**
	 * Registra las hojas de script la la parte pública del sitio
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/personal-frontend.js', array( 'jquery' ), $this->version, false );
        $script = 'bootstrap';
        if( ( ! wp_script_is( $script, 'queue' ) ) && ( ! wp_script_is( $script, 'done' ) ) ) {
            wp_enqueue_script( $script, plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array(), $this->version, 'all');
        }
	}

    /**
     * @param string $name  nombre del campo meta a obtener
     * @return retorna el valor del campo meta
     */

    private function the_personal_field($name){
        return get_post_custom()[$name][0];
    }
    /**
     * @param string $name  nombre del campo meta a obtener
     * @return retorna el valor del campo meta
     */
    private function the_personal_meta($name){
        return get_post_meta(  get_the_ID(), $name, true );
    }

    /**
     *  Remplaza el contenido del post para la vista publica.
     *
     */
    public function single_personal_template($content)
    {
        global $post;
        if ($post->post_type == 'personal') {
            if (file_exists(plugin_dir_path(__DIR__) . 'frontend/views/single-personal.php')) {
                ob_start();
                include(plugin_dir_path(__DIR__) . 'frontend/views/single-personal.php');
                $content =  ob_get_clean();
                return $content;
            }
        }

        return $content;

    }

    public function register_shortcodes(){

        add_shortcode('list-personal', array($this, 'list_personal'));

    }
    public function list_personal(){
        include_once('views/list-personal.php');
    }
}
