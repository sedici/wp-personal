<?php
namespace Personal\Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Personal Widget.
 *
 * Elementor widget that displays the personal list.
 *
 * @since 1.0.0
 */
class Widget_Personal extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve Personal widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'personal_block_elementor';
    }

    /**
     * Get widget title.
     *
     * Retrieve Personal widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Listado de Personal', 'personal-block');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Personal widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-person';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Personal widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['general'];
    }

    /**
     * Register Personal widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     * This method retrieves all 'categorias' terms and populates a Select2 control.
     * It also adds controls for sorting options and column count.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'personal-block'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Fetch categories to populate the select control.
        $categories = get_terms('categorias', ['hide_empty' => false]);
        $options = [];
        if (!is_wp_error($categories) && !empty($categories)) {
            foreach ($categories as $category) {
                $options[$category->term_id] = $category->name;
            }
        }

        $this->add_control(
            'categories',
            [
                'label' => esc_html__('Seleccionar categoría', 'personal-block'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $options,
                'default' => [],
            ]
        );

        $this->add_control(
            'orderBy',
            [
                'label' => esc_html__('Ordenar por', 'personal-block'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'date-desc',
                'options' => [
                    'menu_order-asc' => esc_html__('Orden Manual', 'personal-block'),
                    'title-asc' => esc_html__('Nombre (A-Z)', 'personal-block'),
                    'title-desc' => esc_html__('Nombre (Z-A)', 'personal-block'),
                    'date-desc' => esc_html__('Fecha (más nuevos primero)', 'personal-block'),
                    'date-asc' => esc_html__('Fecha (más antiguos primero)', 'personal-block'),
                    'modified-desc' => esc_html__('Modificado (más nuevos primero)', 'personal-block'),
                    'modified-asc' => esc_html__('Modificado (más antiguos primero)', 'personal-block'),
                ],
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__('Cantidad de columnas', 'personal-block'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 4,
                'step' => 1,
                'default' => 3,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render Personal widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     * This method retrieves the widget settings, prepares the WP_Query arguments
     * (including filtering by category if selected), executes the query,
     * and includes the view file to display the results.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Map settings to attributes expected by the view/block logic
        $attributes = [
            'orderBy' => $settings['orderBy'],
            'categories' => $settings['categories'],
            'columns' => $settings['columns'],
        ];

        // Logic copied from personal-block/src/personal-block/render.php
        $orderBy = $attributes['orderBy'];
        list($orderby_key, $order_direction) = explode('-', $orderBy);

        // Prepare query arguments
        $args = array(
            'post_type' => 'personal',
            'posts_per_page' => -1, // Show all
            'orderby' => $orderby_key,
            'order' => strtoupper($order_direction),
        );

        // Filter by categories if set
        if (!empty($attributes['categories'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'categorias',
                    'field' => 'term_id',
                    'terms' => $attributes['categories'],
                ),
            );
        }

        // Execute query
        $loop = new \WP_Query($args);

        if ($loop->have_posts()) {
            echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
            // \Personal\PLUGIN_NAME_DIR is defined in the main plugin file
            if (defined('\Personal\PLUGIN_NAME_DIR')) {
                include \Personal\PLUGIN_NAME_DIR . 'inc/frontend/views/list-personal-in-order.php';
            } else {
                // Fallback if constant not defined? Should shouldn't happen if loaded correctly.
                // Assuming relative path from this file: ../../inc/frontend/views/...
                // But this file is in personal-block-elementor/
                // So ../inc/...
                include plugin_dir_path(dirname(__FILE__)) . 'inc/frontend/views/list-personal-in-order.php';
            }
            echo '</div>';
        } else {
            echo '<p>' . esc_html__('No hay personal para mostrar', 'personal-block') . '</p>';
        }

        wp_reset_postdata();
    }
}
