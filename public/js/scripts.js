/*===================================
Author       : Bestwebcreator.
Template Name: Shopwise - eCommerce Bootstrap 4 HTML Template
Version      : 1.0
===================================*/

/*===================================*
PAGE JS
*===================================*/

var
  shop,
  core,
  cart,
  variables,
  //-----
  createLoader,
  loaderId;

core = window.TheCore;
variables = window.MyGlobalVariables;

(function ($) {
  'use strict';

  $(function () {
    shop = new window.TheShop();
    cart = new window.TheCart();

    // Lazy loader (pictures, videos, etc.)
    var lazyFn = function () {
      if ($.fn.lazy) {
        $('.lazy').lazy({
          effect: "fadeIn",
          effectTime: 800,
          threshold: 50,
          // callback
          afterLoad: function (element) {
            $(element).css({'background': 'none'});
          }
        });
      }
    };

    /*===================================*
    01. LOADING JS
    /*===================================*/
    $(function () {
      setTimeout(function () {
        $(".preloader").delay(500).fadeOut(700, function () {
          $(this).remove();
        }).addClass('loaded');
      }, 800);
    });

    /*===================================*
    02. BACKGROUND IMAGE JS
    *===================================*/
    /*data image src*/
    $(".background_bg").each(function () {
      var attr = $(this).attr('data-img-src');
      if (typeof attr !== typeof undefined && attr !== false) {
        $(this).css('background-image', 'url(' + attr + ')');
      }
    });

    /*===================================*
    03. ANIMATION JS
    *===================================*/
    $(function () {

      function ckScrollInit(items, trigger) {
        items.each(function () {
          var ckElement = $(this),
            AnimationClass = ckElement.attr('data-animation'),
            AnimationDelay = ckElement.attr('data-animation-delay');

          ckElement.css({
            '-webkit-animation-delay': AnimationDelay,
            '-moz-animation-delay': AnimationDelay,
            'animation-delay': AnimationDelay,
            opacity: 0
          });

          var ckTrigger = (trigger) ? trigger : ckElement;

          ckTrigger.waypoint(function () {
            ckElement.addClass("animated").css("opacity", "1");
            ckElement.addClass('animated').addClass(AnimationClass);
          }, {
            triggerOnce: true,
            offset: '90%',
          });
        });
      }

      ckScrollInit($('.animation'));
      ckScrollInit($('.staggered-animation'), $('.staggered-animation-wrap'));

    });

    /*===================================*
    04. MENU JS
    *===================================*/
    //Show Hide dropdown-menu Main navigation
    $(document).on('ready', function () {
      $('.dropdown-menu a.dropdown-toggler').on('click touchend', function () {
        //var $el = $( this );
        //var $parent = $( this ).offsetParent( ".dropdown-menu" );
        if (!$(this).next().hasClass('show')) {
          $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }

        $(this).parent("li").toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function () {
          $('.dropdown-menu .show').removeClass("show");
        });

        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');

        lazyFn();

        return false;
      });
    });

    //Hide Navbar Dropdown After Click On Links
    var navBar = $(".header_wrap");
    var navbarLinks = navBar.find(".navbar-collapse ul li a.page-scroll");

    $.each(navbarLinks, function () {

      var navbarLink = $(this);

      navbarLink.on('click', function () {
        navBar.find(".navbar-collapse").collapse('hide');
        $("header").removeClass("active");
      });

    });

    //Main navigation Active Class Add Remove
    $('.navbar-toggler').on('click', function () {
      $("header").toggleClass("active");
      if ($('.search-overlay').hasClass('open')) {
        $(".search-overlay").removeClass('open');
        $(".search_trigger").removeClass('open');
      }
    });

    $(document).on('ready', function () {
      if ($('.header_wrap').hasClass("fixed-top") && !$('.header_wrap').hasClass("transparent_header") && !$('.header_wrap').hasClass("no-sticky")) {
        $(".header_wrap").before('<div class="header_sticky_bar d-none"></div>');
      }
    });

    //Main navigation scroll spy for shadow
    $(window).on('scroll', function () {
      var scroll = $(window).scrollTop();

      if (scroll >= 150) {
        $('header.fixed-top').addClass('nav-fixed');
        $('.header_sticky_bar').removeClass('d-none');
        $('header.no-sticky').removeClass('nav-fixed');

      } else {
        $('header.fixed-top').removeClass('nav-fixed');
        $('.header_sticky_bar').addClass('d-none');
      }

    });

    var setHeight = function () {
      var height_header = $(".header_wrap").height();
      $('.header_sticky_bar').css({'height': height_header});
    };

    $(window).on('load', function () {
      setHeight();
    });

    $(window).on('resize', function () {
      setHeight();
    });

    $('.sidetoggle').on('click', function () {
      $(this).addClass('open');
      $('body').addClass('sidetoggle_active');
      $('.sidebar_menu').addClass('active');
      $("body").append('<div id="header-overlay" class="header-overlay"></div>');
    });

    $(document).on('click', '#header-overlay, .sidemenu_close', function () {
      $('.sidetoggle').removeClass('open');
      $('body').removeClass('sidetoggle_active');
      $('.sidebar_menu').removeClass('active');
      $('#header-overlay').fadeOut('3000', function () {
        $('#header-overlay').remove();
      });
      return false;
    });

    function removeBackdrop() {
      $('body').find('.custom-backdrop').remove();
    }

    function backdropClick(backdrop) {
      backdrop.on('click', function () {
        removeBackdrop();
      });
    }

    function createBackdrop() {
      let bckdp = $('<div class="custom-backdrop" />');
      $('body').append(bckdp);
      backdropClick(bckdp);
    }

    $(".categories_btn").on('click', function () {
      removeBackdrop();
      createBackdrop();
      $('.side_navbar_toggler').attr('aria-expanded', 'false');
      $('#navbarSidetoggle').removeClass('show');
    });

    $(".side_navbar_toggler").on('click', function () {
      removeBackdrop();
      createBackdrop();
      $('.categories_btn').attr('aria-expanded', 'false');
      $('#navCatContent').removeClass('show');
    });

    $(".pr_search_trigger").on('click', function () {
      $(this).toggleClass('show');
      $('.product_search_form').toggleClass('show');
    });

    var rclass = true;

    $("html").on('click', function () {
      if (rclass) {
        $('.categories_btn').addClass('collapsed');
        $('.categories_btn,.side_navbar_toggler').attr('aria-expanded', 'false');
        $('#navCatContent,#navbarSidetoggle').removeClass('show');
      }
      rclass = true;
    });

    $(".categories_btn,#navCatContent,#navbarSidetoggle .navbar-nav,.side_navbar_toggler").on('click', function () {
      rclass = false;
    });

    /*===================================*
    05. SMOOTH SCROLLING JS
    *===================================*/
    // Select all links with hashes

    var topheaderHeight = $(".top-header").innerHeight();
    var mainheaderHeight = $(".header_wrap").innerHeight();
    var headerHeight = mainheaderHeight - topheaderHeight - 20;
    $('a.page-scroll[href*="#"]:not([href="#"])').on('click', function () {
      $('a.page-scroll.active').removeClass('active');
      $(this).closest('.page-scroll').addClass('active');
      // On-page links
      if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
        // Figure out element to scroll to
        var target = $(this.hash),
          speed = $(this).data("speed") || 800;
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

        // Does a scroll target exist?
        if (target.length) {
          // Only prevent default if animation is actually gonna happen
          event.preventDefault();
          $('html, body').animate({
            scrollTop: target.offset().top - headerHeight
          }, speed);
        }
      }
    });
    $(window).on('scroll', function () {
      var lastId,
        // All list items
        menuItems = $(".header_wrap").find("a.page-scroll"),
        topMenuHeight = $(".header_wrap").innerHeight() + 20,
        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function () {
          var items = $($(this).attr("href"));
          if (items.length) {
            return items;
          }
        });
      var fromTop = $(this).scrollTop() + topMenuHeight;

      // Get id of current scroll item
      var cur = scrollItems.map(function () {
        if ($(this).offset().top < fromTop)
          return this;
      });
      // Get the id of the current element
      cur = cur[cur.length - 1];
      var id = cur && cur.length ? cur[0].id : "";

      if (lastId !== id) {
        lastId = id;
        // Set/remove active class
        menuItems.closest('.page-scroll').removeClass("active").end().filter("[href='#" + id + "']").closest('.page-scroll').addClass("active");
      }

    });

    $('.more_slide_open').slideUp();
    $('.more_categories').on('click', function () {
      $(this).toggleClass('show');
      $('.more_slide_open').slideToggle();
    });

    /*===================================*
    06. SEARCH JS
    *===================================*/

    $(".close-search").on("click", function () {
      $(".search_wrap,.search_overlay").removeClass('open');
      $("body").removeClass('search_open');
    });

    var removeClass = true;
    $(".search_wrap").after('<div class="search_overlay"></div>');
    $(".search_trigger").on('click', function () {
      $(".search_wrap,.search_overlay").toggleClass('open');
      $("body").toggleClass('search_open');
      removeClass = false;
      if ($('.navbar-collapse').hasClass('show')) {
        $(".navbar-collapse").removeClass('show');
        $(".navbar-toggler").addClass('collapsed');
        $(".navbar-toggler").attr("aria-expanded", false);
      }
    });
    $(".search_wrap form").on('click', function () {
      removeClass = false;
    });
    $("html").on('click', function () {
      if (removeClass) {
        $("body").removeClass('open');
        $(".search_wrap,.search_overlay").removeClass('open');
        $("body").removeClass('search_open');
      }
      removeClass = true;
    });

    /*===================================*
    07. SCROLLUP JS
    *===================================*/
    $(window).on('scroll', function () {
      if ($(this).scrollTop() > 150) {
        $('.scrollup').stop().fadeIn();
      } else {
        $('.scrollup').fadeOut();
      }
    });

    $(".scrollup").on('click', function (e) {
      e.preventDefault();
      $('html, body').animate({
        scrollTop: 0
      }, 600);
      return false;
    });

    /*===================================*
    08. PARALLAX JS
    *===================================*/
    $(window).on('load', function () {
      $('.parallax_bg').parallaxBackground();
    });

    /*===================================*
    09. MASONRY JS
    *===================================*/
    $(window).on("load", function () {
      var $grid_selectors = $(".grid_container");
      var filter_selectors = ".grid_filter > li > a";
      if ($grid_selectors.length > 0) {
        $grid_selectors.imagesLoaded(function () {
          if ($grid_selectors.hasClass("masonry")) {
            $grid_selectors.isotope({
              itemSelector: '.grid_item',
              percentPosition: true,
              layoutMode: "masonry",
              masonry: {
                columnWidth: '.grid-sizer'
              },
            });
          } else {
            $grid_selectors.isotope({
              itemSelector: '.grid_item',
              percentPosition: true,
              layoutMode: "fitRows",
            });
          }
        });
      }

      //isotope filter
      $(document).on("click", filter_selectors, function () {
        $(filter_selectors).removeClass("current");
        $(this).addClass("current");
        var dfselector = $(this).data('filter');
        if ($grid_selectors.hasClass("masonry")) {
          $grid_selectors.isotope({
            itemSelector: '.grid_item',
            layoutMode: "masonry",
            masonry: {
              columnWidth: '.grid_item'
            },
            filter: dfselector
          });
        } else {
          $grid_selectors.isotope({
            itemSelector: '.grid_item',
            layoutMode: "fitRows",
            filter: dfselector
          });
        }
        return false;
      });

      $('.portfolio_filter').on('change', function () {
        $grid_selectors.isotope({
          filter: this.value
        });
      });

      $(window).on("resize", function () {
        setTimeout(function () {
          $grid_selectors.find('.grid_item').removeClass('animation').removeClass('animated'); // avoid problem to filter after window resize
          $grid_selectors.isotope('layout');
        }, 300);
      });
    });

    $('.link_container').each(function () {
      $(this).magnificPopup({
        delegate: '.image_popup',
        type: 'image',
        mainClass: 'mfp-zoom-in',
        removalDelay: 500,
        gallery: {
          enabled: true
        }
      });
    });

    /*===================================*
    10. SLIDER JS
    *===================================*/
    function carousel_slider() {
      $('.carousel_slider').each(function () {
        var $carousel = $(this);
        $carousel.owlCarousel({
          rtl: true,
          dots: $carousel.data("dots"),
          loop: $carousel.data("loop"),
          items: $carousel.data("items"),
          margin: $carousel.data("margin"),
          mouseDrag: $carousel.data("mouse-drag"),
          touchDrag: $carousel.data("touch-drag"),
          autoHeight: $carousel.data("autoheight"),
          center: $carousel.data("center"),
          nav: $carousel.data("nav"),
          rewind: $carousel.data("rewind"),
          navText: ['<i class="ion-ios-arrow-left"></i>', '<i class="ion-ios-arrow-right"></i>'],
          autoplay: $carousel.data("autoplay"),
          animateIn: $carousel.data("animate-in"),
          animateOut: $carousel.data("animate-out"),
          autoplayTimeout: $carousel.data("autoplay-timeout"),
          smartSpeed: $carousel.data("smart-speed"),
          responsive: $carousel.data("responsive")
        });

        $carousel.on('changed.owl.carousel', function (e) {
          cart.assignEventsToAddOrUpdateBtn();
          lazyFn();
        });
      });
    }

    function slick_slider() {
      $('.slick_slider').each(function () {
        var $slick_carousel = $(this);
        $slick_carousel.slick({
          prevArrow: '<button type="button" class="btn slick-prev"></button>',
          nextArrow: '<button type="button" class="btn slick-next"></button>',
          arrows: $slick_carousel.data("arrows"),
          dots: $slick_carousel.data("dots"),
          infinite: $slick_carousel.data("infinite"),
          centerMode: $slick_carousel.data("center-mode"),
          vertical: $slick_carousel.data("vertical"),
          fade: $slick_carousel.data("fade"),
          cssEase: $slick_carousel.data("css-ease"),
          autoplay: $slick_carousel.data("autoplay"),
          verticalSwiping: $slick_carousel.data("vertical-swiping"),
          autoplaySpeed: $slick_carousel.data("autoplay-speed"),
          speed: $slick_carousel.data("speed"),
          pauseOnHover: $slick_carousel.data("pause-on-hover"),
          draggable: $slick_carousel.data("draggable"),
          slidesToShow: $slick_carousel.data("slides-to-show"),
          slidesToScroll: $slick_carousel.data("slides-to-scroll"),
          swipeToSlide: true,
          asNavFor: $slick_carousel.data("as-nav-for"),
          focusOnSelect: $slick_carousel.data("focus-on-select"),
          responsive: $slick_carousel.data("responsive"),
          useTransform: true,
        });
      });
    }

    $(document).on("ready", function () {
      carousel_slider();
      slick_slider();
    });

    /*===================================*
    12. POPUP JS
    *===================================*/
    $('.content-popup').magnificPopup({
      type: 'inline',
      preloader: true,
      mainClass: 'mfp-zoom-in',
    });

    $('.image_gallery').each(function () { // the containers for all your galleries
      $(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
          enabled: true,
        },
      });
    });

    $('.video_popup, .iframe_popup').magnificPopup({
      type: 'iframe',
      removalDelay: 160,
      mainClass: 'mfp-zoom-in',
      preloader: false,
      fixedContentPos: false
    });

    /*===================================*
    13. Select dropdowns
    *===================================*/

    if ($('select').length) {
      // Traverse through all dropdowns
      $.each($('select'), function (i, val) {
        var $el = $(val);

        if ($el.val() === "") {
          $el.addClass('first_null');
        }

        if (!$el.val()) {
          $el.addClass('not_chosen');
        }

        $el.on('change', function () {
          if (!$el.val())
            $el.addClass('not_chosen');
          else
            $el.removeClass('not_chosen');
        });

      });
    }

    /*==============================================================
    15. DROPDOWN JS
    ==============================================================*/
    if ($(".custome_select").length > 0) {
      $(document).on('ready', function () {
        $(".custome_select").msDropdown();
      });
    }

    if ($(".selectric_dropdown").length > 0) {
      if ($.fn.selectric) {
        $.fn.selectric.defaults = $.extend($.fn.selectric.defaults, {
          arrowButtonMarkup: '<b class="button iconize ion-chevron-down"></b>',
        });
      }
      $(document).on('ready', function () {
        $(".selectric_dropdown").selectric();
        $(".selectric_dropdown.selectric_dropdown_changeable_stuffs").selectric({
          optionsItemBuilder: function (itemData) {
            var
              el, str = '',
              colorHex, colorName, size, guarantee,
              colorSpan = '', sizeSpan = '', guaranteeSpan = '';

            el = $(itemData.element);

            if (el.attr('data-show-text')) {
              str = itemData.text;
            }
            colorHex = el.attr('data-color-hex');
            colorName = el.attr('data-color-name');
            size = el.attr('data-size');
            guarantee = el.attr('data-guarantee');

            if (colorHex || colorName) {
              colorSpan = '<div class="product_color_switch d-inline-block mx-2">'

              if (colorHex) {
                colorSpan += '<span class="border" style="background-color: ' + colorHex + ';"></span>';
              }
              if (colorName) {
                colorSpan += '<div class="d-inline-block mr-2">' + colorName + '</div>';
              }

              colorSpan += '</div>';
            }
            if (size) {
              sizeSpan = '<span class="product_size_switch mx-2"><span>' + size + '</span></span>';
            }
            if (guarantee) {
              guaranteeSpan = '<p class="mx-2 mb-0 d-inline-block pr_desc">' + guarantee + '</p>';
            }

            if (!colorHex && !colorName && !size && !guarantee) {
              colorSpan = '<div class="product_color_switch d-inline-block mx-2">'
              colorSpan += '<div class="d-inline-block mr-2">محصول انتخابی</div>';
              colorSpan += '</div>';
            }

            return str + colorSpan + sizeSpan + guaranteeSpan;
          },
          labelBuilder: function (currItem) {
            var
              el, str = '',
              colorHex, colorName, size, guarantee,
              colorSpan = '', sizeSpan = '', guaranteeSpan = '';

            el = $(currItem.element);
            if (el.attr('data-show-text')) {
              str = currItem.text;
            }
            colorHex = el.attr('data-color-hex');
            colorName = el.attr('data-color-name');
            size = el.attr('data-size');
            guarantee = el.attr('data-guarantee');

            if (colorHex || colorName) {
              colorSpan = '<div class="product_color_switch d-inline-block mx-2">'

              if (colorHex) {
                colorSpan += '<span class="border" style="background-color: ' + colorHex + ';"></span>';
              }
              if (colorName) {
                colorSpan += '<div class="d-inline-block mr-2">' + colorName + '</div>';
              }

              colorSpan += '</div>';
            }
            if (size) {
              sizeSpan = '<span class="product_size_switch mx-2"><span>' + size + '</span></span>';
            }
            if (guarantee) {
              guaranteeSpan = '<p class="mx-2 mb-0 d-inline-block pr_desc">' + guarantee + '</p>';
            }

            if (!colorHex && !colorName && !size && !guarantee) {
              colorSpan = '<div class="product_color_switch d-inline-block mx-2">' +
                '<div class="d-inline-block mr-2">محصول انتخابی</div>' +
                '</div>';
            }

            return str + colorSpan + sizeSpan + guaranteeSpan;
          }
        });
      });
    }

    /*===================================*
    16.MAP JS
    *===================================*/
    if (window.L) {
      (function (leaflet) {
        var map_selector = $('#map');
        if (map_selector.length) {
          var map = leaflet.map(
            map_selector.get(0)).setView([map_selector.data("latitude"), map_selector.data("longitude")],
            map_selector.data("zoom") || 14
          );

          leaflet.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
          }).addTo(map);

          leaflet.marker([map_selector.data("latitude"), map_selector.data("longitude")]).addTo(map)
            .bindPopup('ما اینجا هستیم')
            .openPopup();
        }
      })(window.L);
    }

    /*===================================*
    17. COUNTDOWN JS
    *===================================*/
    $('.countdown_time').each(function () {
      var endTime = $(this).data('time');
      $(this).countdown(endTime, function (tm) {
        $(this).html(tm.strftime('<div class="countdown_box"><div class="countdown-wrap"><span class="countdown days">%D </span><span class="cd_text">روز</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown hours">%H</span><span class="cd_text">ساعت</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown minutes">%M</span><span class="cd_text">دقیقه</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown seconds">%S</span><span class="cd_text">ثانیه</span></div></div>'));
      });
    });

    /*===================================*
    18. List Grid JS
    *===================================*/
    $('.shorting_icon').on('click', function () {
      if ($(this).hasClass('grid')) {
        $('.shop_container').removeClass('list').addClass('grid');
        $(this).addClass('active').siblings().removeClass('active');
      } else if ($(this).hasClass('list')) {
        $('.shop_container').removeClass('grid').addClass('list');
        $(this).addClass('active').siblings().removeClass('active');
      }
      $(".shop_container").append('<div class="loading_pr"><div class="mfp-preloader"></div></div>');
      setTimeout(function () {
        $('.loading_pr').remove();
        // $container.isotope('layout');
      }, 800);
    });

    /*===================================*
    19. TOOLTIP JS
    *===================================*/
    $(function () {
      $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover',
      });
    });
    $(function () {
      $('[data-toggle="popover"]').popover();
    });
    $(function () {
      $('[data-toggle="tab"]').tab();
    });

    /*===================================*
    20. PRODUCT COLOR JS
    *===================================*/
    $('.product_color_switch span').each(function () {
      var get_color = $(this).attr('data-color');
      if (get_color) {
        $(this).css("background-color", get_color);
      }
    });

    $('.product_size_switch:not(.product_size_switch_multi) span')
      .each(function () {
        $(this).on("click", function () {
          $(this).siblings(this).removeClass('active').end().addClass('active');
        })
      });
    $('.product_color_switch:not(.product_color_switch_multi)')
      .each(function () {
        $(this).on('click', function () {
          $(this).find('span').siblings(this).removeClass('active').end().addClass('active')
        });
      });
    $('.product_color_switch_multi,.product_size_switch_multi,.product_model_switch_multi')
      .each(function () {
        $(this).on('click', function () {
          $(this).find('span').toggleClass('active');
        })
      });

    /*===================================*
    21. QUICKVIEW POPUP + ZOOM IMAGE + PRODUCT SLIDER JS
    *===================================*/
    var image = $('#product_img');
    //var zoomConfig = {};
    var zoomActive = false;

    zoomActive = !zoomActive;
    if (zoomActive) {
      if ($(image).length > 0) {
        $(image).elevateZoom({
          cursor: "crosshair",
          easing: true,
          gallery: 'pr_item_gallery',
          zoomType: "inner",
          galleryActiveClass: "active",
          zoomWindowWidth: 600,
          zoomWindowHeight: 600,
        });
      }
    } else {
      $.removeData(image, 'elevateZoom');//remove zoom instance from image
      $('.zoomContainer:last-child').remove();// remove zoom container from DOM
    }

    $.magnificPopup.defaults.callbacks = {
      open: function () {
        $('body').addClass('zoom_image');
      },
      close: function () {
        // Wait until overflow:hidden has been removed from the html tag
        setTimeout(function () {
          $('body').removeClass('zoom_image');
          $('body').removeClass('zoom_gallery_image');
          $('.zoomContainer').slice(1).remove();
        }, 100);
      }
    };

    // Set up gallery on click
    var galleryZoom = $('#pr_item_gallery');
    galleryZoom.magnificPopup({
      delegate: 'a',
      type: 'image',
      gallery: {
        enabled: true
      },
      callbacks: {
        elementParse: function (item) {
          item.src = item.el.attr('data-zoom-image');
        }
      }
    });

    // Zoom image when click on icon
    $('.product_img_zoom').on('click', function () {
      var atual = $('#pr_item_gallery a').attr('data-zoom-image');
      $('body').addClass('zoom_gallery_image');
      $('#pr_item_gallery .item').each(function () {
        if (atual == $(this).find('.product_gallery_item').attr('data-zoom-image')) {
          return galleryZoom.magnificPopup('open', $(this).index());
        }
      });
    });

    var inpQuantity = $('input[name="quantity"]');
    $('.plus').on('click', function () {
      var val, inp, max;
      inp = $(this).prev();
      val = inp.val();
      val = val && !isNaN(parseInt(val, 10)) ? parseInt(val, 10) : 0;
      max = inp.attr('data-max-cart-count');
      max = max && !isNaN(parseInt(max, 10)) ? parseInt(max, 10) : 0;
      if (val >= 0 && (0 !== max && val < max)) {
        inp.val(+inp.val() + 1);
      }
      inpQuantity.trigger('input');
    });
    $('.minus').on('click', function () {
      var val, inp;
      inp = $(this).next();
      val = inp.val();
      val = val && !isNaN(parseInt(val, 10)) ? parseInt(val, 10) : 0;
      if (val > 1) {
        if (inp.val() > 1) inp.val(+inp.val() - 1);
      }
      inpQuantity.trigger('input');
    });
    inpQuantity.off('input').on('input', function () {
      var val, max;
      val = $(this).val();
      val = val && !isNaN(parseInt(val, 10)) ? parseInt(val, 10) : 0;
      max = $(this).attr('data-max-cart-count');
      max = max && !isNaN(parseInt(max, 10)) ? parseInt(max, 10) : 0;
      if (val <= 0) {
        $(this).val(1);
      } else if ((0 !== max && val > max)) {
        $(this).val(max);
      } else {
        $(this).val(val);
      }
    });

    /*==========================================
   22. PRICE FILTER JS - [MOVED TO PRODUCT SEARCH]
   *==========================================*/

    /*===================================*
    23. RATING STAR JS
    *===================================*/
    $(document).on("ready", function () {
      $('.star_rating span').on('click', function () {
        var onStar = parseFloat($(this).data('value'), 10); // The star currently selected
        var stars = $(this).parent().children('.star_rating span');
        for (var i = 0; i < stars.length; i++) {
          $(stars[i]).removeClass('selected');
        }
        for (i = 0; i < onStar; i++) {
          $(stars[i]).addClass('selected');
        }
      });
    });

    /*===================================*
    24. CHECKBOX CHECK THEN ADD CLASS JS
    *===================================*/
    $('.create-account,.different_address').hide();
    $('#createaccount:checkbox').on('change', function () {
      if ($(this).is(":checked")) {
        $('.create-account').slideDown();
      } else {
        $('.create-account').slideUp();
      }
    });
    $('#differentaddress:checkbox').on('change', function () {
      if ($(this).is(":checked")) {
        $('.different_address').slideDown();
      } else {
        $('.different_address').slideUp();
      }
    });

    /*===================================*
    25. Cart Page Payment option
    *===================================*/
    $(document).on('ready', function () {
      $('[name="payment_option"]').on('change', function () {
        var $value = $(this).attr('value');
        $('.payment-text').slideUp();
        $('[data-method="' + $value + '"]').slideDown();
      });
    });

    /*===================================*
    26. ONLOAD POPUP JS
    *===================================*/

    $(window).on('load', function () {
      setTimeout(function () {
        $("#onload-popup").modal('show', {}, 500);
      }, 3000);

    });

    /*===================================*
    MY CUSTOM CODES
    *===================================*/
    $(function () {
      if ($.fn.mSwitch) {
        $('.mswitch').mSwitch();
      }

      var tabbedSlier = $('.__tabbed_slider_multi');
      if (tabbedSlier.length) {
        $(window).on('resize', function () {
          if ($(window).outerWidth() > 991) {
            tabbedSlier.addClass('show');
          } else {
            var id = tabbedSlier.attr('id');
            if (id) {
              var expanded = $('[data-target="#' + id + '"]').attr('aria-expanded');
              if (expanded && 'false' === expanded) {
                tabbedSlier.removeClass('show');
              }
            }
          }
        });
      }

      lazyFn();
    });

    $(function () {
      var recoverCodeBtn = $('#resendCode');
      recoverCodeBtn.on('click', function () {
        if (createLoader) {
          createLoader = false;
          loaderId = shop.showLoader();
        }
        shop.request(variables.url.resendCode.forgetPassword, 'POST', function () {
          shop.hideLoader(loaderId);
          var type, message;
          type = this.type;
          message = this.data;
          shop.toasts.toast(message, {
            type: type,
          });
          createLoader = true;
        }, {}, true, function () {
          shop.hideLoader(loaderId);
          createLoader = true;
        });
      });
    });

    /*===================================*
    *===================================*/
    //$( window ).on( "load", function() {
//		document.onkeydown = function(e) {
//			if(e.keyCode == 123) {
//			 return false;
//			}
//			if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
//			 return false;
//			}
//			if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
//			 return false;
//			}
//			if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
//			 return false;
//			}
//
//			if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
//			 return false;
//			}
//		 };
//
//		$("html").on("contextmenu",function(){
//			return false;
//		});
//	});

  });

})(jQuery);