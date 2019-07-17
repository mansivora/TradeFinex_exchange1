(function($) {
    "use strict";
    var win = $(window);

    win.on("load", function() {

        /********************
         *  Preloader  *
         ********************/
        var $element = $("#loader");
        $element.fadeOut(1000);

        /****************************
         *  Custom Select dropdown  *
         ****************************/
        var $element = $('.currency_select');
        if ($element.length > 0) {
            $element.select2({
                templateResult: formatState,
                templateSelection: formatState,
                minimumResultsForSearch: Infinity
            });
        }
        var $elements = $(".custom_select");
        if ($element.length > 0) {
            $elements.select2({
                minimumResultsForSearch: Infinity
            });
        }

        /*******************************************
         *   Add/remove class on scroll to Header  *
         *******************************************/
        win.on('scroll',function() {
            var scroll = win.scrollTop();
            if (scroll >= 100) {
                $("header.opt5").addClass("fixed");
            } else {
                $("header.opt5").removeClass("fixed");
            }
        });

        /*************************************
         *  Dropdown Menu on hover  *
         *************************************/
        function dropdown() {
            var $viewportWidth = win
                .width();
            var $element = $('ul.navbar-nav li.dropdown');
            if ($viewportWidth > 767) {
                $element.on('mouseenter',function() {
                    $(this)
                        .find('.dropdown-menu')
                        .stop(true, true)
                        .delay(200)
                        .slideDown(300);
                });
                $element.on('mouseleave',function() {
                    $(this)
                        .find('.dropdown-menu')
                        .stop(true, true)
                        .delay(200)
                        .slideUp(300);
                });
            }
        }
        win.on('resize', dropdown);
        dropdown();


        /***************************************
         * footer menu accordian (@media 767)  *
         ***************************************/
        function footerAcc() {
            var $allFooterAcco = $(".mob-acco ul");
            var $allFooterAccoItems = $(".mob-acco h4");
            if (win.width() < 768) {
                $allFooterAcco.css('display', 'none');
                $allFooterAccoItems.on("click", function() {
                    if ($(this)
                        .hasClass('open')) {
                        $(this)
                            .removeClass('open');
                        $(this)
                            .next()
                            .stop(true, false)
                            .slideUp(300);
                    } else {
                        $allFooterAcco.slideUp(300);
                        $allFooterAccoItems.removeClass('open');
                        $(this)
                            .addClass('open');
                        $(this)
                            .next()
                            .stop(true, false)
                            .slideDown(300);
                        return false;
                    }
                });
            } else {
                $allFooterAcco.css('display', 'block');
                $allFooterAccoItems.off();
            }
        }
        win.on('resize', function() {
            footerAcc();
        });
        footerAcc();

        /***************************
         *  Features-carousel  *
         ***************************/
        var owl = $(".features-carousel-sec .owl-carousel");
        if (owl.length > 0) {
            owl.owlCarousel({
                items: 1,
                navText: ['<i class="fa fa-chevron-circle-right" ></i>', '<i class="fa fa-chevron-circle-right" ></i>'],
                navigation: true,
                controls: true,                
                autoplayTimeout: 4000,
				loop: true,
				autoplay: true
            });
        }

        /******************************
         *  Smooth Scrolling To Div  *
         ******************************/
        var $element = $(".scroll-link");
        if ($element.length > 0) {
            $element.on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function() {});
                }
            });
        }

        /***************************
         *  Scroll to top Action  *
         ***************************/
        var $element = $('.scroll-top');
        win.on("scroll", function() {
            if ($(this)
                .scrollTop() > 100) {
                $('.scroll-top').fadeIn();
            } else {
                $('.scroll-top').fadeOut();
            }
        });
        $element.on("click", function() {
            var $scrollElement = $("html, body");
            $scrollElement.animate({
                scrollTop: 0
            }, 600);
            return false;
        });

        

        /***************************
         *  Video popup  *
         ***************************/
        var $element = $('.video');
        if ($element.length > 0) {
            $element.magnificPopup({
                type: 'iframe',
                iframe: {
                    patterns: {
                        dailymotion: {
                            index: 'dailymotion.com',
                            id: function(url) {
                                var m = url.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
                                if (m !== null) {
                                    if (m[4] !== undefined) {
                                        return m[4];
                                    }
                                    return m[2];
                                }
                                return null;
                            },
                            src: 'https://www.dailymotion.com/embed/video/%id%'
                        },
                        youtube: {
                            index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
                            id: 'v=', // String that splits URL in a two parts, second part should be %id%
                            // Or null - full URL will be returned
                            // Or a function that should return %id%, for example:
                            // id: function(url) { return 'parsed id'; }
                            src: 'https://www.youtube.com/embed/%id%?autoplay=1' // URL that will be set as a source for iframe.
                        },
                        vimeo: {
                            index: 'vimeo.com/',
                            id: '/',
                            src: 'https://player.vimeo.com/video/%id%?autoplay=1'
                        },
                        gmaps: {
                            index: 'https://maps.google.',
                            src: '%id%&output=embed'
                        }
                    }
                }
            });
        }

        /***************************
         *  Image popup  *
         ***************************/
        var $element = $('.image-popup');
        if ($element.length > 0) {
            $element.magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                image: {
                    verticalFit: true
                },
            });
        }
    });

})(jQuery);