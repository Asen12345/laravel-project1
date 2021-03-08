$(function () {
    $(document).ready(function () {

    		// adding 'header-reg' class
    			if($('.header-top__menu').hasClass('header-top__menu--reg')){
    				$('.header').addClass('header-reg');
    			}
    		// END adding 'header-reg' class

        // header-top__menu toggle
        $(".toggle-mnu").click(function () {
            var $th = $(this);
            $th.toggleClass("on");

            if ($th.closest('.header-top').length > 0) {
                $(".header-top__menu--text").stop(true, true).slideToggle(150);

            }

            if ($th.closest('.categories-menu-block--mob').length > 0) {
                $(".categories-menu-block--mob .categories-menu").stop(true, true).fadeToggle(150);

            }
            return false;
        });
        // end header-top__menu toggle

        // superfish
        sfOpt = {
            pathClass: 'current',
            speed: 1,
            speedOut: 1,
            // disableHI: true,
            onInit: function () {
                if (screen.width <= 768) {
                    $(this).children('li.active').superfish('hide');
                }
                else{
                    $(this).children('li.active').superfish('show');
                }
            },

            onShow: function () {

                // this.context.querySelector('.sf-with-ul').classList.add('opened');
                if ($(this).closest('li').find('ul').length === 0) {

                    $(this.context).closest('.sf-menu').find('li.active').superfish('hide');
                }
            },
            onBeforeHide: function () {

                $('.sf-with-ul').removeClass('opened');
            },

            onIdle: function () {

                if ($(this.context).hasClass('active') && screen.width > 768) {

                    $(this.context).closest('.sf-menu').find('li.active').superfish('show');
                }
                if ($(this).closest('li').find('ul').length == 0) {

                    $(this.context).closest('.sf-menu').find('li.active').superfish('show');
                }
            }
        };

        $('.header-menu--desk').superfish(sfOpt);

        $('.categories-menu').superfish({
            pathClass: 'current',
            onShow: function () {
                if (this.context.classList.contains('categories-menu__item-sub')) {
                    this.context.querySelector('.categories-menu__sub li .sf-with-ul').classList.add('opened');
                }

            },
            onBeforeHide: function () {

                $('.sf-with-ul').removeClass('opened');
            }
        });
        // end superfish

        // main-menu-mob

        var touchHover = function () {
            $('.header-menu--mob a[data-hover]').click(function (e) {
                e.preventDefault();
                var $this = $(this);
                var onHover = $this.attr('data-hover');
                var linkHref = $this.attr('href');
                var $parent = $this.closest('li');

                $parent.find('ul').first().slideDown(150);
                $parent.siblings().find('ul').slideUp(150);
                $parent.siblings().find('.header-menu__link')
                    .removeClass('opened');

                // if ($this.hasClass('opened')){
                //     $parent.find('ul').first().slideUp(150);
                // }
                $this.toggleClass('opened');

                if (linkHref && $this.hasClass(onHover)) {
                    location.href = linkHref;
                    return false;
                }
                $this.toggleClass(onHover);
                $this
                    .closest('li')
                    .siblings()
                    .find('[data-hover]')
                    .removeClass(onHover);

            });
        };


        touchHover();

        $('.header-menu--mob .icon-chev-wrapper').click(function (e) {
            e.stopPropagation();
            // alert(e.isPropagationStopped());
            var $parentLi = $(this).closest('li');
            console.log($parentLi.find('a').first().text());

            $parentLi.find('ul').first().slideToggle(150);
            $parentLi.siblings().find('ul').slideUp(150);
            $parentLi.find('.header-menu__link').toggleClass('opened');
            $parentLi.siblings().find('.header-menu__link').removeClass('opened');

            console.log('click on chev!');

            return false;
        });
        // end main-menu-mob

        // magnific-popup
        $('.to-popup').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#name',

            // When elemened is focused, some mobile browsers in some cases zoom in
            // It looks not nice, so we disable it:
            callbacks: {
                beforeOpen: function () {
                    if ($(window).width() < 700) {
                        this.st.focus = false;
                    } else {
                        this.st.focus = '#name';
                    }
                }
            }
        });

        // registered button toggle
        $('.header-reg .header-enter').click(function () {
            $(this).find('.header-enter__menu').fadeToggle();
        });
        // end registered button toggle

    });

    //slick
    $('.day-theme__slider').slick({
        slidesToScroll: 1,
        slidesToShow: 2,
        dots: true,
        responsive: [

            {
                breakpoint: 960,
                settings: {

                    slidesToShow: 1

                }
            }

        ]
    });

    $('.sidebar-analitcs__slider').slick({
        slidesToScroll: 1,
        // slidesToShow: 1,
        slidesPerRow: 1,
        rows: 3,
        dots: true,
        responsive: [

            {
                breakpoint: 992,
                settings: {

                    rows: 1,
                    slidesToShow: 3
                    // slidesPerRow: 3

                }
            },
            {
                breakpoint: 768,
                settings: {

                    rows: 1,
                    slidesToShow: 2

                }
            },
            {
                breakpoint: 576,
                settings: {

                    rows: 1,
                    rows: 3,
                    slidesToShow: 1

                }
            }
        ]
    });

    //end slick

    // staff variable slider
    $staffsSlider = $('.staffs-persons');

    var staffsSliderSettings = {
        slidesToShow: 1,
        dots: false,
        mobileFirst: true
    };


    if (screen.width < 768) {
        $staffsSlider.slick(staffsSliderSettings);
        // $variantsSlider.slick('unslick');
    }

    $(window).resize(function () {
        if ($(window).width() > 768) {
            if ($staffsSlider.hasClass('slick-initialized')) {
                $staffsSlider.slick('unslick');
            }
            return
        }

        if (!$staffsSlider.hasClass('slick-initialized')) {
            return $staffsSlider.slick(staffsSliderSettings);
        }
    });
    // end staff variable slider

    // subjects popup
    $(function() {
        let subject_slider = $('.subjects-slider');
        let range = document.getElementById('subject-range'),
            dragger,
            opts = {
                slidesToShow: 1,
                centerMode: true,
                focusOnSelect: true,
                centerPadding: '150px',
                responsive: [

                    {
                        breakpoint: 600,
                        settings: {

                            centerPadding: 0,

                        }
                    }
                ]
            };

        subject_slider.on('init reInit', function (event, slick) {
            let amount = slick.slideCount;

            dragger = noUiSlider.create(range, {

                range: {
                    'min': 1,
                    'max': amount
                },
                step: 1,
                start: 1,
                format: wNumb({
                    decimals: 0
                })
            });

            dragger.on('update', function () {
                let handle = document.querySelector('.subject-range .noUi-handle'),
                    handlePosition = handle.getAttribute('aria-valuenow'),
                    noUiOrigin = document.querySelector('.noUi-origin');

                if (handlePosition === '0.0') {
                    handle.setAttribute('style', 'left: 0');
                }

            });

            dragger.on('set', function () {

                $('.subjects-slider').slick('slickGoTo', this.get() - 1);

                let handle = document.querySelector('.subject-range .noUi-handle'),
                    handlePosition = handle.getAttribute('aria-valuenow'),
                    noUiOrigin = document.querySelector('.noUi-origin'),
                    handleWidth = handle.offsetWidth;

                if (handlePosition === '0.0') {
                    handle.setAttribute('style', 'left: 0');
                }
                if (handlePosition === '100.0') {
                    handle.setAttribute('style', 'right:' + handleWidth + 'px;' + 'left: auto;');
                }
                subjectsTabsHandling(this.get() - 1);
            });

            dragger.on('end', function () {

            });


        });

        subject_slider.on('afterChange', function (e, slick, currentSlide) {

            range.noUiSlider.set(currentSlide + 1);
            subjectsTabsHandling(currentSlide);
        });

        subject_slider.slick(opts);
        subject_slider.slick('unslick');
        range.noUiSlider.destroy();

        $('#subjects-open').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#name',
            mainClass: 'li-news-subjects',
            // When elemened is focused, some mobile browsers in some cases zoom in
            // It looks not nice, so we disable it:
            callbacks: {
                beforeOpen: function () {
                    if ($(window).width() < 700) {
                        this.st.focus = false;
                    } else {
                        this.st.focus = '#name';
                    }
                },
                open: function () {
                    $('.subjects-slider').slick(opts);
                },
                close: function () {
                    $('.subjects-slider').slick('unslick');
                    range.noUiSlider.destroy();
                }
            }
        });

        function subjectsTabsHandling(tabNumber) {
            $('.subject-content-item').eq(tabNumber)
                .removeClass('hidden')
                .siblings()
                .addClass('hidden');

        }
    });
    // end subjects popup

    // tabs
    var $tabs = $('.tabs__link:not(.url)');

    $tabs.on('click', function (e) {
        e.preventDefault();
        var $th = $(this),
            $tabsWrapper = $th.closest('.tabs-wrapper'),
            $data = $th.attr('data-tab'),
            $parent = $th.parent();

        $parent.addClass('tabs__item--active')
            .siblings()
            .removeClass('tabs__item--active');

        $tabsWrapper
            .find('.tabs__content .tabs__item[data-tab=' + $data + ']')
            .removeClass('hidden')
            .siblings()
            .addClass('hidden');
    });
    // end tabs

    // Accordeon-----------------------------------
    $('.acordeon-link').click(function (e) {
        e.preventDefault();
        var $currentItem = $(this).closest('.acordeon-item');
        if ($currentItem.hasClass('acordeon-item-with-sublist')) {

            $currentItem.find('.acordeon-sublist')
                .stop(true, true)
                .slideToggle();

            $currentItem.toggleClass('active');
            $currentItem
                .siblings()
                .removeClass('active');

            $currentItem.siblings()
                .find('.acordeon-sublist')
                .stop(true, true)
                .slideUp();

        } else {
            return;
        }
    });
    // end Accordeon-----------------------------------

    // .company-about toggler
    $('.company-about__link').click(function () {
        $(this).closest('.company-about').toggleClass('active');
        return false;
    });
    // end .company-about toggler

    // jsscrollpane
    
    var isMobile = {
    	Android:        function() { return navigator.userAgent.match(/Android/i) ? true : false; },
    	BlackBerry:     function() { return navigator.userAgent.match(/BlackBerry/i) ? true : false; },
    	iOS:            function() { return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false; },
    	Windows:        function() { return navigator.userAgent.match(/IEMobile/i) ? true : false; },
    	any:            function() { return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());  }
    };

    if (document.querySelector('.scroll-pane')) {
        let scrollPane = $('.scroll-pane').jScrollPane({
            verticalDragMaxHeight: 100,
            animateScroll: true,
            contentWidth: '0px'
        })
        .bind(
        	'jsp-will-scroll-y',
        	function(event, destY){
        		var dragHeight = +$(event.target).find('.jspDrag').height();
        		if(isMobile.any()){
	        		$('body').addClass('nonscroll');
	        		if(destY == (event.target.clientHeight - dragHeight) || destY == 0){
	        			$('body').removeClass('nonscroll');
	        		}
        			
        		}
        	}
        );

        let scrollPaneApi = scrollPane.data('jsp');

        $(window).resize(function () {
            scrollPaneApi.reinitialise();
        });

    }

    $(document).on("touchstart", function(e){
      	if(!$(e.target).closest('.news-items').length > 0 && isMobile.any()){
    		$('body').removeClass('nonscroll')
    	}
    });
    // end jsscrollpane

    // sidebar-company toggle
    $('.sidebar-iconed-title--companies').click(function () {
        $(this).next().toggleClass('hidden');
        return false;
    });
    // end sidebar-company toggle

    // footer cookies close
    $('.footer-cookies .sp-icon').click(function () {
        $(this).parent().addClass('hidden');
    });
    // end footer cookies close

    // selectmenu
    $(".li-form-select").selectmenu({
        // width: $(this).closest('.mich-form-label').width()
    });
    // end selectmenu

    // blog-post message form toggle
    $('.scroll-to-comments').click(function () {
        var $targetElem = $('.blog-post-discussion__header');
        // $targetElem.removeClass('hidden');
        $('html, body').animate({scrollTop: $targetElem.offset().top}, 600);

        return false;
    });

    $('.scroll-to-comments-form').click(function () {
        var $targetForm = $('#post-page-form');
        $targetForm.removeClass('hidden');
        $('html, body').animate({scrollTop: $targetForm.offset().top}, 600);

        return false;
    });
    // end blog-post message form toggle

    // .topic-answer__form toggle
    $('.topic-answer__form .li-popup__header .chev-top').click(function (e) {
        $(this).parent().next().toggleClass('hidden');
    });
    // end .topic-answer__form toggle

    //Chrome Smooth Scroll
    try {
        $.browserSelector();
        if ($("html").hasClass("chrome")) {
            $.smoothScroll();
        }
    } catch (err) {

    }
    ;

    $("img, a").on("dragstart", function (event) {
        event.preventDefault();
    });

});
