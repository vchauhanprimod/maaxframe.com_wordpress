<?php
	/**
	 * Slides
	 */
	$slideshow = new THB_Collection($id, 'work_slide');
	$slideshow->setSize('full');

	if( thb_get_featured_image($id) != '' ) {
		$slideshow->addCoverSlide( get_post_thumbnail_id($id) );
	}

	$slides = $slideshow->getSlides();

	/**
	 * Image size
	 */
	$image_size = $slideshow->getSize();

	/**
	 * Class
	 */
	$class = array('cycle-slideshow');
	if( count($slides) > 1 ) { $class[] = 'w-cycle-nav'; }
?>

<?php if( count($slides) > 1 ) : ?>
	<a href="#" id="thb-slideshow_prev">
		<?php echo __('Previous', 'thb_text_domain') ?>
	</a>
	<a href="#" id="thb-slideshow_next">
		<?php echo __('Next', 'thb_text_domain') ?>
	</a>
<?php endif; ?>

<div class="<?php echo implode(' ', $class); ?>">
	<?php foreach($slides as $slide ) : ?>
		<?php
			$slide_data = array(
				'type' => $slide['type']
			);

			if( $slide_data['type'] != 'image' ) {
				$slide_data['autoplay'] = $slide['autoplay'];
			}
		?>
		<div class="slide" <?php thb_data_attributes($slide_data); ?>>
			<?php if( $slide['type'] == 'image' ) : ?>
				<img src="<?php echo thb_image_get_size($slide['id'], $image_size); ?>" alt="">
			<?php else : ?>
				<?php
					$attributes = thb_get_attributes(array(
						'url' => $slide['url'],
						'class' => 'thb_slideshow_video thb-noFit',
						'autoplay' => $slide['autoplay'],
						'loop' => $slide['loop']
					));
					echo thb_do_shortcode('[thb_video ' . $attributes . ']');
				?>
			<?php endif; ?>

			<?php if( $slide['caption'] != '' ) : ?>
				<div class="caption">
					<?php echo thb_text_format($slide['caption'], true); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>