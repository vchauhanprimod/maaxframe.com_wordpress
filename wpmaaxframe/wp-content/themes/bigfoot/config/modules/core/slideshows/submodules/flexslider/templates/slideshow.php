<?php

global $_wp_additional_image_sizes;

/**
 * Slideshow parameters
 */
$slideshow_smooth_height = $meta['flexslider_smoothHeight'] == 1;
$slideshow_width = $_wp_additional_image_sizes[$slideshow->getSize()]['width'];
$slideshow_height = $slideshow_smooth_height ? '' : $meta['slideshowHeight'];

?>

<div id="<?php echo $id; ?>" class="thb-slideshow flexslider" data-id="<?php echo $slideshow->getId(); ?>">
	<ul class="slides">
		<?php foreach( $slideshow->getSlides() as $slide ) : ?>

			<?php
				if( $slide['type'] == 'image' ) {
					if( $slideshow_smooth_height ) {
						$img = $slide['url'];
					}
					else {
						$img = thb_get_resized_image($slide['id'], $slideshow_width, $slideshow_height, true);
					}
				}
				else {
					$video_data = thb_get_attributes(array(
						'fixed_height' => $slideshow_height,
						'fixed_width' => $slideshow_width
					));
				}
			?>

			<li class="slide" data-thumb="<?php echo $slide['thumb']; ?>" data-type="<?php echo $slide['type']; ?>">
				<?php if( $slide['type'] == 'image' ) : ?>

					<?php if( $slideshow->slidesImagesClickable() ) : ?>
						<a href="<?php echo $slide['link']; ?>">
					<?php endif; ?>

						<img src="<?php echo $img; ?>" alt="">

					<?php if( $slideshow->slidesImagesClickable() ) : ?>
						</a>
					<?php endif; ?>

				<?php else : ?>
					<?php echo thb_do_shortcode('[thb_video url="'. $slide['url'] .'" class="thb_slideshow_video" ' . $video_data . ']'); ?>
				<?php endif; ?>

				<?php if( $slide['caption'] != '' ) : ?>
					<div class="caption">
						<?php echo apply_filters('the_content', $slide['caption']); ?>
					</div>
				<?php endif; ?>
			</li>

		<?php endforeach; ?>
	</ul>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$.thb.config.set('flexslider', '<?php echo $slideshow->getId(); ?>', {
			animation: '<?php echo $meta["flexslider_effects"]; ?>',
			useCSS: false,
			video: true,
			animationLoop: true,
			animationSpeed: <?php echo floatval($meta['transition_speed']) * 1000; ?>,
			slideshowSpeed: <?php echo floatval($meta['delay']) * 1000; ?>,
			smoothHeight: <?php echo $meta['flexslider_smoothHeight']; ?>,
			controlNav: false
			// controlNav: 'thumbnails'
		}, '<?php echo $id; ?>');
	});
</script>