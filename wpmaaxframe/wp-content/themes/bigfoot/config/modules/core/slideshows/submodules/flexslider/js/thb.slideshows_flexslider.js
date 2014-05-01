/**
 * Slideshow controller.
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

/**
 * Slideshow
 * -----------------------------------------------------------------------------
 */

/**
 * Run a slideshow.
 * 
 * @param Object config The slideshow configuration.
 * @return void
 */
(function($) {
	$.fn.thb_slideshow = function( config ) {

		if( !$(this).flexslider ) {
			return;
		}

		var useConfig = config !== undefined;

		return this.each(function() {
			var el = $(this);

			if( el.data('running') ) {
				return;
			}

			if( !useConfig ) {
				var id = el.data('id');
				config = $.thb.config.get('flexslider', id, el.attr('id'));
			}

			config.after = function(slider) {
				var slide = slider.slides.eq(slider.currentSlide),
					caption = slide.find('.caption');

				caption.stop().fadeIn(config.animationSpeed / 2);
			};

			config.before = function(slider) {
				var slide = slider.slides.eq(slider.currentSlide),
					caption = slide.find('.caption'),
					captions = el.find('.caption');

				captions.not(caption).hide();
				caption.stop().fadeOut(config.animationSpeed / 2);
				el.trigger('pauseVideos', [slide]);
			};

			config.start = function(slider) {
				if( slider.slides ) {
					config.after(slider);
				}
				else {
					el.find('.caption').stop().delay(config.animationSpeed).fadeIn(config.animationSpeed / 2);
				}
			};

			el.bind('pauseVideos', function(e, slide) {
				$(slide).find('video, iframe').each(function() {
					$(this).data("player").pause();
				});
			});

			el.find("video, iframe").on("change", function(e, state) {
				if( state == "finished" || state == "paused" ) {
					el.flexslider('play');
				}
				else {
					el.flexslider('pause');
				}
			});

			el.flexslider( config );
		});
	};
})(jQuery);

/**
 * Boot
 * -----------------------------------------------------------------------------
 */
(function($) {
	$(window).load(function() {
		$(".thb-slideshow").thb_slideshow();
	});
})(jQuery);