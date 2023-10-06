<?php
class Product_Category_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'product-category-slider-widget';
    }

    public function get_title() {
        return __('Product Category Slider', 'product-category-slider-addon');
    }

    public function get_icon() {
        return 'eicon-slider';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function get_product_categories_options() {
        $categories = get_terms('product_cat', array('hide_empty' => false));
        $options = array();
    
        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }
    
        return $options;
    }
    

    protected function _register_controls() {
        $this->start_controls_section(
            'category_settings',
            [
                'label' => __('Category Settings', 'product-category-slider-addon'),
            ]
        );
    
        $this->add_control(
            'selected_categories',
            [
                'label' => __('Select Categories', 'product-category-slider-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_product_categories_options(),
            ]
        );
    
        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __('Slides to Show', 'product-category-slider-addon'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'devices' => ['desktop', 'tablet', 'mobile'], // Specify different values for different devices
                'desktop_default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
            ]
        );
    
        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => __('Slides to Scroll', 'product-category-slider-addon'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => 1,
                'tablet_default' => 1,
                'mobile_default' => 1,
            ]
        );
    
        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'product-category-slider-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes', // Autoplay enabled by default
            ]
        );
    
        $this->add_control(
            'loop',
            [
                'label' => __('Loop', 'product-category-slider-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes', // Loop enabled by default
            ]
        );
    
        $this->add_control(
            'autoplay_duration',
            [
                'label' => __('Autoplay Duration (ms)', 'product-category-slider-addon'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000, // Default autoplay duration in milliseconds
            ]
        );
    
        $this->end_controls_section();
    }
    

    protected function render() {
        $settings = $this->get_settings_for_display();
        $selected_categories = $settings['selected_categories'];
        

        // echo '<pre>';
        // echo var_dump($settings);
        // echo '</pre>';
        
        echo '<div class="product-cat-cust-slider elementor-swiper" 
              data-slides_to_show="' . $settings['slides_to_show'] . '" 
              data-slides_to_scroll="' . $settings['slides_to_scroll'] . '" 
              data-slides_to_show_mobile="' . $settings['slides_to_show_mobile'] . '"
              data-slides_to_scroll_mobile="' . $settings['slides_to_scroll_mobile'] . '"
              data-slides_to_show_tablet="' . $settings['slides_to_show_tablet'] . '"
              data-slides_to_scroll_tablet="' . $settings['slides_to_scroll_tablet'] . '"
              data-loop="' . $settings['loop'] . '" 
              data-autoplay="' . $settings['autoplay'] . '" 
              data-autoplay_duration="' . $settings['autoplay_duration'] . '">';
    
        echo '<div class="swiper-container">';
        echo '<div class="swiper-wrapper">';
        
        foreach ($selected_categories as $category_id) {
            $category = get_term($category_id, 'product_cat');
        
            if ($category instanceof WP_Term) {
                $image_url = get_term_meta($category_id, 'thumbnail_id', true);
                $title = $category->name;
                $product_count = $category->count;
				$category_archive_link = get_category_link($category_id);
				

        
                echo '<div class="swiper-slide slider-item">';
                echo '<div class="category-image" style="background-image: url(' . wp_get_attachment_url($image_url) . ');"></div>';
                echo '<div class="category-dtails-container">';
				echo '<a href="' . esc_url($category_archive_link) . '" class="ctgry-lnk-slider">';
                echo '<h3 class="category-title">' . $title . '</h3>';
				echo '</a>';
                echo '<p class="product-count">' . sprintf(_n('%d Tour', '%d Tours', $product_count, 'product-category-slider-addon'), $product_count) . '</p>';
                echo '</div>';
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
    }
    
    

    protected function _content_template() {
        ?>
    <script>
        jQuery(document).ready(function ($) {
            $('.slider-item').each(function () {
                var category_id = $(this).data('category_id');
                var $sliderItem = $(this); // Save the reference to the outer context

                if (category_id) {
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'get_category_data',
                            category_id: category_id
                        },
                        success: function (response) {
                            console.log(response);
                            if (response.success) {
                                // Use the existing category variable
                                var category = response.data;

                                $sliderItem.find('.category-title').text(category.name);
                                $sliderItem.find('.product-count').text(category.count + ' Products');
                                $sliderItem.find('.category-image').css('background-image', 'url(' + category.featured_image + ')');
                            }
                        }
                    });

                }
            });
        });
    </script>

        <div class="product-category-slider elementor-swiper" data-slides_to_show="{{ settings.slides_to_show }}" data-slides_to_scroll="{{ settings.slides_to_scroll }}" data-loop="{{ settings.loop }}" data-autoplay="{{ settings.autoplay }}" data-autoplay_duration="{{ settings.autoplay_duration }}">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <# _.each(settings.selected_categories, function(category_id) { #>
                        <div class="swiper-slide slider-item" data-category_id="{{ category_id }}">
                            <div class="category-image" style="background-image: url({{category.featured_image}});"></div>
                            <div class="category-details-container">
                                <h3 class="category-title">{{category.name}}</h3>
                                <p class="product-count">{{category.count}} Tours</p>
                            </div>
                        </div>
                    <# }); #>
                </div>
            </div>
        </div>

        <?php
    }
    
}
