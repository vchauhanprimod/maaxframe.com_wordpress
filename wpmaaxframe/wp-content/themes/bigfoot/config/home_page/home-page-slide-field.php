<?php
	if( $field_value_bg_opacity == '' ) {
		$field_value_bg_opacity = '0.85';
	}
?>

<div class="thb-field-home-page-slide-text">
	<a href="#" class="more"><?php _e('Content controls', 'thb_text_domain') ?></a>

	<div class="thb-expandable">
		<label for="<?php echo $field_name_post_id; ?>"><?php _e('Entry ID', 'thb_text_domain'); ?></label>
		<input type="text" value="<?php echo $field_value_post_id; ?>" name="<?php echo $field_name_post_id; ?>">

		<label for="<?php echo $field_name_big_text; ?>"><?php _e('Headline', 'thb_text_domain'); ?></label>
		<textarea name="<?php echo $field_name_big_text; ?>"><?php echo $field_value_big_text; ?></textarea>

		<label for="<?php echo $field_name_small_text; ?>"><?php _e('Paragraph', 'thb_text_domain'); ?></label>
		<textarea name="<?php echo $field_name_small_text; ?>"><?php echo $field_value_small_text; ?></textarea>

		<label for="<?php echo $field_name_btn_text; ?>"><?php _e('Button label', 'thb_text_domain'); ?></label>
		<input type="text" value="<?php echo $field_value_btn_text; ?>" name="<?php echo $field_name_btn_text; ?>">

		<label for="<?php echo $field_name_btn_url; ?>"><?php _e('Button URL', 'thb_text_domain'); ?></label>
		<input type="text" value="<?php echo $field_value_btn_url; ?>" name="<?php echo $field_name_btn_url; ?>">
	</div>
</div>

<div class="thb-field-home-page-slide-presentation">
	<a href="#" class="more"><?php _e('Presentation controls', 'thb_text_domain') ?></a>

	<div class="thb-expandable">
		<label for="<?php echo $field_name_id; ?>"><?php _e('Background image', 'thb_text_domain'); ?></label>
		<?php
			thb_partial_upload(array(
				'field_name_url' => $field_name_url,
				'field_value_url' => $field_value_url,
				'field_name_id' => $field_name_id,
				'field_value_id' => $field_value_id
			));
		?>

		<div class="thb-background">
			<label for="<?php echo $field_name_bg_color; ?>"><?php _e('Background color and opacity', 'thb_text_domain'); ?></label>

			<div class="thb-background-solid">
				<input type="text" class="thb-colorpicker" name="<?php echo $field_name_bg_color; ?>" value="<?php echo $field_value_bg_color; ?>">
			</div>

			<div class="thb-background-opacity">
				<input type="number" step="0.05" min="0" max="1" name="<?php echo $field_name_bg_opacity; ?>" value="<?php echo $field_value_bg_opacity; ?>">
			</div>
		</div>
	</div>
</div>

<div class="thb-field-home-page-slide-secondary">
	<a href="#" class="more"><?php _e('Advanced controls', 'thb_text_domain') ?></a>

	<div class="thb-expandable thb-advanced-controls">
		<label for="<?php echo $field_name_class; ?>"><?php _e('HTML class', 'thb_text_domain'); ?></label>
		<input type="text" value="<?php echo $field_value_class; ?>" name="<?php echo $field_name_class; ?>">
	</div>
</div>