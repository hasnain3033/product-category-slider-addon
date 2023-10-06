jQuery(window).on('elementor/frontend/init', function () {
    // This code will be executed after Elementor frontend initialization
    console.log('elementor added');

    elementorFrontend.hooks.addAction('frontend/element_ready/product-category-slider-widget.default', function ($scope) {
        console.log($scope);
        var $scopeElement = $scope.find('.elementor-swiper')[0];
        console.log($scopeElement);

        // Access data attributes using getAttribute
        var slidesToShow = $scopeElement.getAttribute('data-slides_to_show');
        var slidesToScroll = $scopeElement.getAttribute('data-slides_to_scroll');
        var slidesToShowMobile = $scopeElement.getAttribute('data-slides_to_show_mobile');
        var slidesToScrollMobile = $scopeElement.getAttribute('data-slides_to_scroll_mobile');
        var slidesToShowTablet = $scopeElement.getAttribute('data-slides_to_show_tablet');
        var slidesToScrollTablet = $scopeElement.getAttribute('data-slides_to_scroll_tablet');
        var loop = $scopeElement.getAttribute('data-loop') === 'yes';
        var autoplay = $scopeElement.getAttribute('data-autoplay') === 'yes';
        var autoplayDuration = parseInt($scopeElement.getAttribute('data-autoplay_duration'));
		
		console.log(slidesToScrollMobile)

        // Initialize Swiper slider inside the widget element
        var swiper = new Swiper($scope.find('.swiper-container')[0], {
            slidesPerView: slidesToShow,
            slidesPerGroup: slidesToScroll,
            loop: loop,
			spaceBetween: 30,
            autoplay: autoplay,
            autoplayTimeout: autoplayDuration,
            // Add other options here
            breakpoints: {
				1024: {
					slidesPerView: slidesToShow,
                    slidesPerGroup: slidesToScroll
				},
                768: {
                    slidesPerView: 2,
                    slidesPerGroup: 1
                },
                120: {
                    slidesPerView: 1,
                    slidesPerGroup: 1
                }
            }
        });
        console.log(swiper);
    });

    // Your additional code here
});
