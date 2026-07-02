<?php
/**
 * Plugin Name:       Widgets de Elementor Personalizados
 * Description:       Agrega widgets personalizados al constructor de Elementor.
 * Version:           1.0.0
 * Author:            Raúl Venegas
 * Text Domain:       elementor-custom-widgets
 */
if (!defined('ABSPATH')) {
    exit;
}

final class Elementor_Custom_Widgets_Plugin {

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_category']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function add_widget_category($elements_manager) {
        $elements_manager->add_category(
            'custom_widgets_category',
            [
                'title' => 'Widgets Personalizados',
                'icon' => 'fa fa-plug',
            ]
        );
    }

    public function register_widgets($widgets_manager) {
        if (!did_action('elementor/loaded')) {
            return;
        }

        require_once __DIR__ . '/widgets/class-larry-episodes.php';
        $widgets_manager->register(new \Elementor_Widget_Larry_Episodes());
    }

    public function enqueue_assets() {
        if (!class_exists('\Elementor\Plugin')) {
            return;
        }

        $is_elementor_page = false;

        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            $is_elementor_page = true;
        } else {
            $post_id = get_the_ID();
            if ($post_id) {
                $document = \Elementor\Plugin::$instance->documents->get($post_id);
                if ($document && $document->is_built_with_elementor()) {
                    $is_elementor_page = true;
                }
            }
        }

        if ($is_elementor_page) {
            $this->do_enqueue();
        }
    }

    private function do_enqueue() {
        wp_enqueue_style(
            'larry-episodes',
            plugin_dir_url(__FILE__) . 'assets/css/larry-episodes.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'larry-episodes',
            plugin_dir_url(__FILE__) . 'assets/js/larry-episodes.js',
            [],
            '1.0.0',
            true
        );
    }
}

add_action('plugins_loaded', function () {
    Elementor_Custom_Widgets_Plugin::instance();
});
