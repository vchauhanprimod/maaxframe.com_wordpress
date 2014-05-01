/**
 * Frontend controller.
 *
 * This file is entitled to manage all the interactions in the frontend.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package JS
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2012, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

var thb_moving = false,
	thb_toggling_menu = false,
	HomePageSlider = {};

/**
 * Boot
 * -----------------------------------------------------------------------------
 */
(function($) {
	$(document).ready(function() {

		// Responsive tabs
		$(".thb-tabs-nav").tinyNav({
			active: 'open',
			callback: function( val ) {
				$(".thb-tabs-nav a[href='" + val + "']").trigger('click');
			}
		});

		// Go top
		$(".gotop").click(function() {
			$.scrollTo(0, 500);
			return false;
		});

		// Menu
		$("#main-nav > div").menu({
			speed: 150
		});

		// Navigation
		if( $("#logo ").length ) {
			$.thb.loadImage($("#logo"), {
				allLoaded: function() {
					var logo_height = $("#logo").outerHeight(),
						nav_wrapper = $(".nav-wrapper"),
						nav_trigger = $("#nav-trigger"),
						nav_wrapper_padding_top = parseInt(nav_wrapper.css("padding-top"), 10),
						nav_wrapper_padding_bottom = parseInt(nav_wrapper.css("padding-bottom"), 10),
						nav_wrapper_height = nav_wrapper.outerHeight() - nav_wrapper_padding_top - nav_wrapper_padding_bottom;

					var new_padding_top = nav_wrapper_padding_top + ((logo_height - nav_wrapper_height) / 2),
						new_padding_bottom = nav_wrapper_padding_bottom + ((logo_height - nav_wrapper_height) / 2);

					nav_wrapper.css({
						'padding-top': new_padding_top,
						'padding-bottom': new_padding_bottom
					});

					nav_trigger
						.css('margin-top', (logo_height - nav_trigger.outerHeight()) / 2 )
						.addClass('thb-loaded');
				}
			});
		}
		else {
			var logo_height = $("#logo").outerHeight(),
				nav_wrapper = $(".nav-wrapper"),
				nav_trigger = $("#nav-trigger"),
				nav_wrapper_padding_top = parseInt(nav_wrapper.css("padding-top"), 10),
				nav_wrapper_padding_bottom = parseInt(nav_wrapper.css("padding-bottom"), 10),
				nav_wrapper_height = nav_wrapper.outerHeight() - nav_wrapper_padding_top - nav_wrapper_padding_bottom;

			var new_padding_top = nav_wrapper_padding_top + ((logo_height - nav_wrapper_height) / 2),
				new_padding_bottom = nav_wrapper_padding_bottom + ((logo_height - nav_wrapper_height) / 2);

			nav_wrapper.css({
				'padding-top': new_padding_top,
				'padding-bottom': new_padding_bottom
			});

			nav_trigger
				.css('margin-top', (logo_height - nav_trigger.outerHeight()) / 2 )
				.addClass('thb-loaded');
		}

		$("#nav-trigger").on("click", function() {
			if( thb_toggling_menu ) {
				return false;
			}

			thb_toggling_menu = true;

			var nav_active = $("body").hasClass("nav-active");

			if( nav_active ) {
				$.thb.transition('.nav-wrapper', function() {
					$(".nav-wrapper").css("visibility", "hidden");
					thb_toggling_menu = false;
				});

				$("#logo").css("visibility", "visible");
				$("body").removeClass("nav-active");
			}
			else {
				$(".nav-wrapper").css("visibility", "visible");

				$.thb.transition('.nav-wrapper', function() {
					$("#logo").css("visibility", "hidden");
					thb_toggling_menu = false;
				});

				setTimeout(function() {
					$("body").addClass("nav-active");
				}, 1);
			}

			return false;
		});

		/**
		 * Portfolio
		 * ---------------------------------------------------------------------
		 */
		function thb_open_single_work_overlay() {
			$(".thb-single-work-overlay").show();

			$.thb.transition(".thb-single-work-overlay", function() {
				if( $("body").hasClass("thb-single-work-overlay-on") ) {
					$("body").css("overflow-y", "hidden");
				}
			});

			setTimeout(function() {
				$("body").addClass("thb-single-work-overlay-on");

				$.thb.transition(".thb-single-work-content", function() {
					if( ! $("body").hasClass("thb-single-work-info") ) {
						$(".thb-single-work-content").hide();
					}

				}, true);
			}, 20);
		}

		function thb_close_single_work_overlay() {
			$.thb.transition(".thb-single-work-overlay", function() {
				$(".thb-single-work-overlay").hide();
				$(".thb-single-work-content").hide();
				$("body").css("overflow-y", "auto");
			});

			$("body").removeClass("thb-single-work-overlay-on");
		}

		function thb_open_single_work_info() {
			$(".thb-single-work-content").show();
			setTimeout(function() {
				$("body").addClass("thb-single-work-info");
			},1);

			if( $("html").hasClass("no-csstransforms") ) {
				var singe_work_info_width = $(".thb-single-work-content").outerWidth();

				$(".thb-single-work-slideshow").css("margin-left", singe_work_info_width);
			}
		}

		function thb_close_single_work_info() {
			$("body").removeClass("thb-single-work-info");

			if( $("html").hasClass("no-csstransforms") ) {
				$(".thb-single-work-slideshow").css("margin-left", 0);
			}
		}

		function thb_load_single_work_details() {
			$("body").removeClass("thb-single-work-info thb-ajax-loading");

			var id = $("#thb-portfolio-container li").eq(index).find("a[data-id]").first().data('id');

			$("body").addClass("thb-ajax-loading");

			$(".thb-single-work-slideshow, .thb-single-work-content .thb-text, .thb-single-work-title").html('');

			$("#thb-next-single-work").css("display", index < total_items - 1 ? "block" : "none");
			$("#thb-prev-single-work").css("display", index > 0 ? "block" : "none");

			$.post(thb_system.ajax_url, {
				action: 'thb_get_single_work',
				id: id
			}, function( data ) {

				$("h1.thb-single-work-title").html(data.title);
				$(".thb-single-work-content .thb-text").html(data.content);

				if( data.content !== '' ) {
					$("#thb-info-single-work").show();
				}
				else {
					$("#thb-info-single-work").hide();
				}

				$(".thb-single-work-slideshow").html(data.slideshow);

				$(".thb-single-work-slideshow .cycle-slideshow")
					.thb_stretcher({
						adapt: true,
						onSlidesLoaded: function() {
							setTimeout(function() {
								$("body").removeClass("thb-ajax-loading");

								$(".thb-single-work-slideshow .cycle-slideshow")
									.addClass("thb-loaded")
									.cycle({
										'fx': 'scrollHorz',
										'easing': 'easeInOutQuint',
										'speed': '500',
										'slides': '> div.slide',
										'timeout': '5000',
										'auto-height': '-1',
										'prev': '#thb-slideshow_prev',
										'next': '#thb-slideshow_next',
										'swipe': true
									})
									.cycle('pause');
							}, 50);
						},
						slides: '> .slide'
					});

			}, 'json');
		}

		if( $("body").hasClass("page-template-template-portfolio-php") ) {
			var total_items = $("#thb-portfolio-container .item").length,
				index = 0;

			$(document).on("click", "#thb-close-single-work", function() {
				thb_close_single_work_overlay();
				return false;
			});

			$.thb.key("esc", function() {
				if( $("body").hasClass("thb-single-work-overlay-on") ) {
					thb_close_single_work_overlay();
				}
			});

			$(document).on("click", "#thb-info-single-work", function() {
				if( $("body").hasClass("thb-single-work-info") ) {
					thb_close_single_work_info();
				}
				else {
					thb_open_single_work_info();
				}

				return false;
			});

			$(document).on("click", "#thb-portfolio-container li a", function() {
				index = $(this).parents(".item").index();

				thb_open_single_work_overlay();
				thb_load_single_work_details();

				return false;
			});

			$.thb.key("right", function() {
				if( $("body").hasClass("thb-single-work-overlay-on") ) {
					// if( index < total_items - 1 ) {
						// index++;
						$(".cycle-slideshow").cycle("next");
						// thb_load_single_work_details();
					// }
				}

				return false;
			});

			$.thb.key("left", function() {
				if( $("body").hasClass("thb-single-work-overlay-on") ) {
					// if( index > 0 ) {
						// index--;
						$(".cycle-slideshow").cycle("prev");
						// thb_load_single_work_details();
					// }
				}

				return false;
			});

			$(document).on("click", "#thb-next-single-work, #thb-prev-single-work", function(e) {
				if( $(e.target).is("#thb-next-single-work") ) {
					index++;
				}
				else {
					index--;
				}

				thb_load_single_work_details();

				return false;
			});
		}

		/**
		 * Photogallery
		 * ---------------------------------------------------------------------
		 */
		if( $("body").hasClass("page-template-template-photogallery-php") ) {
			var container = $('.thb-photogallery-container'),
				load_more = $('#thb-infinite-scroll-button');

			$.thb.isotope({
				filter: '',
				itemsContainer: '.thb-photogallery-container',
				itemsClass: 'li',
				pagContainer: ''
			});

			load_more.on("click dblclick", function() {
				var url = container.attr('data-url');

				$.thb.loadUrl(url, {
					filter: '#page-content',
					after: function(data) {
						var html = $( $(data).outerHTML() ),
							ctn = html.find('.thb-photogallery-container');

						if( ctn.length ) {
							container.attr('data-url', ctn.data('url'));
							container.isotope('insert', $( ctn.html() ), function() {
								var galleries_config = $.thb.config.get('thb_lightbox', 'magnificPopup');
								galleries_config['type'] = 'image';
								galleries_config['gallery'] = {
									'enabled': true
								};

								$("a[rel^='magnificPopupGalleries']").magnificPopup( galleries_config );
							});

							if( !html.find('#thb-infinite-scroll-button').length ) {
								load_more.hide();
							}
						}
					}
				});

				return false;
			});
		}

		// WooCommerce
		// ---------------------------------------------------------------------

		$(".thb-cart-collaterals tr.shipping a").on("click", function() {
			if( $(".shipping-calculator-form").css("display") != "block" ) {
				$.scrollTo( $("form.shipping_calculator a.shipping-calculator-button"), { duration: 500 } );
			}

			$("form.shipping_calculator a.shipping-calculator-button").trigger("click");

			return false;
		});

		// Home page
		// ---------------------------------------------------------------------

		if( $('body').hasClass('page-template-template-showcase-php') ) {
			HomePageSlider.init();

			$(window).resize(function() {
				HomePageSlider.positionElements();
			});

			if( $('.thb-desktop .thb-twitter ul').length ) {
				$('.thb-desktop .thb-twitter ul')
					.cycle({
						speed: 600,
						slides: '> li',
						fx: 'fade',
						log: 'false'
					});

				setTimeout(function() {
					$.thb.loadImage( $(".header-container"), {
						allLoaded: function() {
							HomePageSlider.positionElements();
						}
					} );
				}, 10);
			}
			else {
				$.thb.loadImage( $(".header-container"), {
					allLoaded: function() {
						HomePageSlider.positionElements();
					}
				} );
			}
		}

		// Footer stripe
		// ---------------------------------------------------------------------

		$('#footer-stripe .thb-twitter ul')
			.cycle({
				speed: 600,
				slides: '> li',
				fx: 'fade'
			});
	});
})(jQuery);

/**
 * Home page slider
 */
(function($) {
	window.HomePageSlider = {

		currentSlide: 0,

		init: function() {
			this.container = $("#thb-home-slides");
			this.pictures = $(".thb-home-slide > img");

			this.header = $(".header-container");
			this.footer = $(".home-footer-container");

			this.captions = $(".thb-home-slide-caption");
			this.banners = $(".thb-banner");
			this.homeExpand = $(".thb-home-expand");
			this.controlNext = $(".thb-home-slides-next");
			this.controlPrev = $(".thb-home-slides-prev");
			this.pagerContainer = $(".thb-home-slides-pager");
			this.pager = $(".thb-home-slides-pager a");

			$("body").addClass("thb-loading");

			this.bindEvents();
			this.showHideControls();
			this.loadFrontImage();
		},

		positionElements: function() {
			var $w = $(window),
				header_height = $(".header-container").outerHeight() + ($("#wpadminbar").length ? 28 : 0),
				footer_height = $(".home-footer-container").outerHeight(),
				diff = parseInt( (footer_height - header_height) / 2, 10 );

			if( !footer_height ) {
				footer_height = 48;
			}

			HomePageSlider.captions.css({
				'top' : header_height,
				'bottom' : footer_height
			});

			if( $("html").hasClass("no-csstransforms") ) {
				HomePageSlider.banners.each(function() {
					$(this).css("margin-top", - ($(this).outerHeight() / 2) + diff );
				});
			}
			else {
				HomePageSlider.banners.each(function() {
					$(this).css("margin-top", diff );
				});
			}

			HomePageSlider.pagerContainer.css({
				bottom: footer_height
			});
		},

		loadFrontImage: function() {
			setTimeout(function() {
				if( ! HomePageSlider.pictures.length ) {
					HomePageSlider.container.addClass("thb-slider-loaded");
				}
				else {
					$.thb.loadImage( HomePageSlider.pictures, {
						imageLoaded: function( image ) {
							image.parent().thb_stretcher({
								adapt: false
							});

							image.parent().addClass("thb-slide-loaded");
							$("body").removeClass("thb-loading");

							setTimeout(function() {
								HomePageSlider.container.addClass("thb-slider-loaded");
							}, 10);
						}
					} );
				}
			}, 500);
		},

		bindEvents: function() {
			$.thb.key("right", function() {
				HomePageSlider.right();
			});

			$.thb.key("left", function() {
				HomePageSlider.left();
			});

			HomePageSlider.controlNext.click(function() {
				HomePageSlider.right();
				return false;
			});

			HomePageSlider.controlPrev.click(function() {
				HomePageSlider.left();
				return false;
			});

			HomePageSlider.homeExpand.click(function() {
				if( $("body").hasClass("w-home-expand") ) {
					$(this).attr("data-icon", "u");
					$("body").removeClass("w-home-expand");
				}
				else {
					$(this).attr("data-icon", "p");
					$("body").addClass("w-home-expand");
				}

				return false;
			});

			HomePageSlider.pager.click(function() {
				if( ! HomePageSlider.container.hasClass("thb-slider-loaded") || thb_moving ) {
					return false;
				}

				var target = $(this).data("target");

				HomePageSlider.pager.removeClass("active");
				$(this).addClass("active");

				if( target !== HomePageSlider.currentSlide ) {
					if( target > HomePageSlider.currentSlide ) {
						for(i=HomePageSlider.currentSlide; i<target; i++) {
							HomePageSlider.right(true);
						}
					}
					else {
						for(i=HomePageSlider.currentSlide; i>target; i--) {
							HomePageSlider.left(true);
						}
					}
				}

				return false;
			});

			$('body.thb-mobile').hammer().bind('swipeleft', function() {
				HomePageSlider.right();
				return false;
			});

			$('body.thb-mobile').hammer().bind('swiperight', function() {
				HomePageSlider.left();
				return false;
			});
		},

		right: function( programmatic ) {
			if( ! programmatic && (! HomePageSlider.container.hasClass("thb-slider-loaded") || thb_moving) ) {
				return false;
			}

			var active_slides = $(".thb-home-slide.active"),
				slides = $(".thb-home-slide"),
				last_active = active_slides.last();

			if( active_slides.length < slides.length ) {
				$.thb.transition(last_active, function() {
					thb_moving = false;
				});

				last_active.addClass("out");
				last_active.next().addClass("active");

				this.currentSlide++;
				thb_moving = true;
			}
			else {
				thb_moving = true;

				$("#thb-home-slides").stop().animate({
					"margin-left": -20
				}, 150, 'linear', function() {
					$(this).stop().animate({
						"margin-left": 0
					}, 500, 'easeOutElastic', function() {
						thb_moving = false;
					});
				});
			}

			this.showHideControls();
		},

		left: function( programmatic ) {
			if( ! programmatic && (! HomePageSlider.container.hasClass("thb-slider-loaded") || thb_moving) ) {
				return false;
			}

			var active_slides = $(".thb-home-slide.active"),
				last_active = active_slides.last();

			if( active_slides.length > 1 ) {
				$.thb.transition(last_active, function() {
					thb_moving = false;
				});

				last_active.prev().removeClass("out");
				last_active.removeClass("active");

				this.currentSlide--;
				thb_moving = true;
			}
			else {
				thb_moving = true;

				$("#thb-home-slides").stop().animate({
					"margin-left": 20
				}, 150, 'linear', function() {
					$(this).stop().animate({
						"margin-left": 0
					}, 500, 'easeOutElastic', function() {
						thb_moving = false;
					});
				});
			}

			this.showHideControls();
		},

		showHideControls: function() {
			var active_slides = $(".thb-home-slide.active"),
				slides = $(".thb-home-slide");

			HomePageSlider.controlPrev.css({'visibility': 'visible'});
			HomePageSlider.controlNext.css({'visibility': 'visible'});

			if( active_slides.length === 1 ) {
				HomePageSlider.controlPrev.css({'visibility': 'hidden'});
			}

			if( active_slides.length === slides.length ) {
				HomePageSlider.controlNext.css({'visibility': 'hidden'});
			}

			HomePageSlider.pager.removeClass("active");
			HomePageSlider.pager.eq(active_slides.last().index()).addClass("active");
		}
	};
})(jQuery);