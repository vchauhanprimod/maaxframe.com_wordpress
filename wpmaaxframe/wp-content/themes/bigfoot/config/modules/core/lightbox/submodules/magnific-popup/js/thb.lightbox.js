jQuery.thb.config.set('thb_lightbox', 'magnificPopup', {
	mainClass: 'mfp-with-zoom', // this class is for CSS animation below

	zoom: {
		enabled: true, // By default it's false, so don't forget to enable it

		duration: 300, // duration of the effect, in milliseconds
		easing: 'ease-in-out', // CSS transition easing function

		// The "opener" function should return the element from which popup will be zoomed in
		// and to which popup will be scaled down
		// By defailt it looks for an image tag:
		opener: function(openerElement) {
			// openerElement is the element on which popup was initialized, in this case its <a> tag
			// you don't need to add "opener" option if this code matches your needs, it's defailt one.
			return openerElement.is('img') ? openerElement : openerElement.find('img');
		}
	}
});

(function($) {
	if( typeof String.prototype.endsWith !== 'function' ) {
		String.prototype.endsWith = function(suffix) {
			return this.indexOf(suffix, this.length - suffix.length) !== -1;
		};
	}

	$(document).ready(function() {
		var images = $.thb.config.get('thb_lightbox', 'images');
		var videos = $.thb.config.get('thb_lightbox', 'videos');
		var elements_selector = [];

		if( images.elements ) {
			elements_selector.push(images.elements);
		}

		if( videos.elements ) {
			elements_selector.push(videos.elements);
		}

		if( elements_selector.length === 0 ) {
			return;
		}

		var elements = $(elements_selector.join(','));

		/**
		 * Filters and NexGEN Gallery compatibility fix
		 */
		elements = elements.filter(function() {
			var is_next_gen = $(this).parents('[class*="ngg-"]').length > 0;
			var is_no_thb_lightbox = $(this).hasClass('thb-no_lightbox');

			return !is_next_gen && !is_no_thb_lightbox;
		});

		elements.each(function() {
			if( $(this).attr('rel') != 'magnificPopupGalleries' ) {
				if( $(this).is(images.elements) ) {
					$(this).attr('rel', 'magnificPopupImages');
				}

				if( $(this).is(videos.elements) ) {
					$(this).attr('rel', 'magnificPopupVideos');
				}
			}
		});

		// Galleries
		if( images.elements ) {
			$('.gallery, .thb-gallery, .thb-slideshow').each(function() {
				var id = $(this).attr('id'),
					links = $(this).find('a:has(img)');

				links.each(function() {
					if( $(this).is( $(images.elements) ) ) {
						$(this).attr('rel', 'magnificPopupGalleries');
					}
				});
			});
		}

		var images_config = $.thb.config.get('thb_lightbox', 'magnificPopup');
		images_config['type'] = 'image';

		var videos_config = $.thb.config.get('thb_lightbox', 'magnificPopup');
		videos_config['type'] = 'iframe';

		var galleries_config = $.thb.config.get('thb_lightbox', 'magnificPopup');
		galleries_config['type'] = 'image';
		galleries_config['gallery'] = {
			'enabled': true
		};

		$("a[rel^='magnificPopupImages']").magnificPopup( images_config );
		$("a[rel^='magnificPopupVideos']").magnificPopup( videos_config );
		$("a[rel^='magnificPopupGalleries']").magnificPopup( galleries_config );
	});
})(jQuery);