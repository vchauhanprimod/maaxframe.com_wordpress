<?php
	$thb_page_id = thb_get_page_ID();
	$image_sizes = thb_config('core/photogallery', 'image_sizes');
	$image_size = thb_config('core/photogallery', 'image_size');
	$item_thumb_rel = thb_config('core/photogallery', 'item_thumb_rel');

	$slideshow = new THB_Slideshow( $thb_page_id, 'photogallery_slide' );

	if( $image_sizes != '' ) {
		$slides_size = thb_get_post_meta($thb_page_id, 'slides_size');

		if( is_array(current($image_sizes)) ) {
			$columns = thb_get_post_meta($thb_page_id, 'portfolio_columns');

			if( isset( $image_sizes[$columns] ) ) {
				$slideshow->setSize( $image_sizes[$columns][$slides_size] );
			}
			else {
				reset($image_sizes);
				$image_size = current($image_sizes);

				if( isset($image_size[$slides_size]) ) {
					$slideshow->setSize( $image_size[$slides_size] );
				}
				else {
					$slideshow->setSize( $image_size );
				}
			}
		}
		else {
			$slideshow->setSize( $slides_size );
		}
	}
	else {
		$slideshow->setSize( $image_size );
	}

	$slides = $slideshow->getSlides();

	$slides_per_page = thb_get_post_meta($thb_page_id, 'slides_per_page');
	$ajaxloading = !empty($slides_per_page);
	$offset = 0;

	if( $ajaxloading ) {
		$total_slides = count($slides);
		$offset = isset($_GET['offset']) ? $_GET['offset'] : 0;

		$slides = array_slice($slides, $offset * $slides_per_page, $slides_per_page);
	}
?>

<?php if( count($slides) > 0 ) : ?>
	<ul class="thb-photogallery-container" data-url="<?php echo add_query_arg('offset', $offset+1); ?>">
		<?php foreach( $slides as $slide ) : ?>
		<li>
			<a href="<?php echo $slide['full']; ?>" class="item-thumb" rel="<?php echo $item_thumb_rel; ?>" title="<?php echo $slide['caption']; ?>">
				<span class="thb-overlay"></span>
				<img src="<?php echo $slide['url']; ?>" alt="">
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php if( $ajaxloading == 1 && (($offset+1) * $slides_per_page < $total_slides) ) : ?>
	<div id="thb-infinite-scroll-nav">
		<a href="#" id="thb-infinite-scroll-button"><?php echo __('Load more', 'thb_text_domain'); ?></a>
	</div>
<?php endif; ?>