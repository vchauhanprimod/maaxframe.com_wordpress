<div id="thb-featuredimage-background">
	<?php if( $featured_image != '' ) : ?>
		<div class="thb-featuredimage-background-container">
			<img src="" data-src="<?php echo $featured_image; ?>" alt="">
		</div>
	<?php endif; ?>

	<div class="thb-featuredimage-background-overlay"></div>
</div>

<style type="text/css">
	<?php
		$bg_color = thb_get_post_meta(thb_get_page_ID(), 'background_color');

		if( $featured_image == '' ) {
			$bg_opacity = '1';
		}

		if( $bg_opacity == '' ) {
			$bg_opacity = '0.85';
		}
	?>
	#thb-featuredimage-background .thb-featuredimage-background-overlay {
		<?php if( $bg_color != '' ) : ?>
			background: <?php echo $bg_color; ?>;
		<?php endif; ?>

		opacity: <?php echo $bg_opacity; ?>;
		-khtml-opacity: <?php echo $bg_opacity; ?>;
		-moz-opacity: <?php echo $bg_opacity; ?>;
		filter: alpha(opacity=<?php echo $bg_opacity*100; ?>);
		-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $bg_opacity*100; ?>)";


	}
</style>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		jQuery(".thb-featuredimage-background-container")
			.thb_stretcher({
				adapt: false
			});
	}, false);
</script>