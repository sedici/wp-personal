<?php
namespace Personal\Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Register Personal Widget.
 *
 * Include the widget class and register it with Elementor's widget manager.
 * This function is hooked into 'elementor/widgets/register'.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_personal_widget($widgets_manager)
{

    require_once(__DIR__ . '/class-widget-personal.php');

    $widgets_manager->register(new \Personal\Elementor\Widget_Personal());

}
add_action('elementor/widgets/register', __NAMESPACE__ . '\\register_personal_widget');
