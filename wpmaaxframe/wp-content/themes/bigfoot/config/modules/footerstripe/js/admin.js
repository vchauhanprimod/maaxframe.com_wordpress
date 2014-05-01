(function($) {
	$(document).ready(function() {
		var cts = "#thb-fields-container-footerstripes_content_type_",
			content_type = $("select[name='footerstripes_content_type']"),
			content_types = $(cts + "twitter, " + cts + "call-to-action, " + cts + "social");

		content_types.hide();

		content_type.on("change", function() {
			content_types.hide();
			$("#thb-fields-container-footerstripes_content_type_" + $(this).val()).show();
		});

		content_type.trigger("change");
	});
})(jQuery);