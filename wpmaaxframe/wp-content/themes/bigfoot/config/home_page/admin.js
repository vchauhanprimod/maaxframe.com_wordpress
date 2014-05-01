(function($) {

	$(document).ready(function() {
		$(document).on("click", ".thb-field-home-page-slide-field a.more", function() {
			$(this).parent().find("> div").toggle();

			return false;
		});

		// $(document).on("click", ".thb-field-home-page-slide-field input[type='checkbox']", function() {
		// 	if( $(this).is(':checked') ) {
		// 		$(this).attr("value", "1");
		// 	}
		// 	else {
		// 		$(this).attr("value", "0");
		// 	}
		// });

		$(document).on("change", ".background_type_selection", function() {
			var type = $(this).val(),
				background = $(this).parent();

			background.find("> div").hide();
			background.find("> div.thb-background-" + type).show();

			return false;
		});
	});

})(jQuery);