jQuery(document).ready(function(jQuery)
	{
	jQuery(".kpt-corner-gradient").change(function()
		{
			var kpt_corner_gradient_value=jQuery(this).val();
			jQuery("#kpt-corner-gradient-value").html(kpt_corner_gradient_value)});
		
			jQuery("#kpt-total-column, #kpt-total-row").blur(function(){
				var kpt_total_row=jQuery("#kpt-total-row").val();
				var kpt_total_column=jQuery("#kpt-total-column").val();
				jQuery.ajax(
					{
					type:"POST",
					url:kpt_ajax.kpt_ajaxurl,
					data:{action:"kpt_ajax_form",kpt_total_row:kpt_total_row,kpt_total_column:kpt_total_column},
					success:function(data)
						{
						jQuery("#kpt-total-data").html(data)
						}
					})
		});
					
	})