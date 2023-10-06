<?php
/*
Plugin Name: Product Category Slider Addon
Description: An Elementor addon to display a product category slider.
Version: 1.0
*/

class Product_Category_Slider_Addon {

    public function __construct() {
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']); // Corrected method name
        add_action('wp_ajax_nopriv_get_category_data', array($this, 'get_category_data'));
        add_action('wp_ajax_get_category_data', array($this, 'get_category_data'));
    }

    public function register_widgets() {
        require_once(plugin_dir_path(__FILE__) . 'widget.php');
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Category_Slider_Widget());
    }
    
    public function enqueue_styles() {
        wp_enqueue_style(
            'swiper-style',
            'https://unpkg.com/swiper/swiper-bundle.min.css',
            array(),
            '6.8.4'
        );
        wp_enqueue_script(
            'swiper-script',
            'https://unpkg.com/swiper/swiper-bundle.min.js',
            array('jquery'),
            '6.8.4',
            true
        );
        wp_enqueue_style(
            'product-category-slider-addon-style',
            plugin_dir_url(__FILE__) . 'css/product-category-slider-addon.css',
            array(), // You can add dependencies here if needed
            '1.0', // Replace with your version number
            'all' // Change to 'screen' if it's specific to screen styles
        );
        wp_enqueue_script(
            'product-category-slider-addon-script',
            plugin_dir_url(__FILE__) . 'js/product-category-slider-addon.js',
            array('swiper-script'),
            '1.0',
            true
        );
    }
    function get_category_data() {
        $category_id = $_POST['category_id'];
        $category = get_term($category_id, 'product_cat');
        
        if ($category instanceof WP_Term) {
            $response = array(
                'name' => $category->name,
                'count' => $category->count,
                'featured_image' => wp_get_attachment_url(get_term_meta($category_id, 'thumbnail_id', true)),
            );
            wp_send_json_success($response);
        } else {
            wp_send_json_error();
        }
    }
    
}

new Product_Category_Slider_Addon();
