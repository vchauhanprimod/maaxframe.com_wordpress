<?php
global $post, $product, $woocommerce;
$attachment_ids = $product->get_gallery_attachment_ids();
?>
<div class="thb-product-slideshow-wrapper images">

	<?php
		if ( has_post_thumbnail() && empty($attachment_ids) ) :
		$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
	?>
		<div class="item">
			<a href="<?php echo $src[0] ?>" rel="magnificPopupImage">
				<?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ?>
			</a>
		</div>

	<?php else : ?>

	<div class="thb-product-slideshow flexslider" id="thb-product-<?php echo get_the_ID(); ?>">
		<ul class="slides">
			<?php
				if ( $attachment_ids ) {
					$loop = 0;

					foreach ( $attachment_ids as $attachment_id ) {

						$image_link = wp_get_attachment_url( $attachment_id );

						if ( ! $image_link )
							continue;

						$classes = array();
						$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
						$image_class = esc_attr( implode( ' ', $classes ) );
						$image_title = esc_attr( get_the_title( $attachment_id ) );

						printf( '<li class="slide"><a href="'. $image_link .'" class="thb-lightbox" rel="magnificPopupGalleries">%s</a></li>', wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ), wp_get_attachment_url( $attachment_id ) );

						$loop++;
					}
				}
			?>
		</ul>
	</div>
	<?php endif; ?>

	<?php if ( count($attachment_ids) > 1 ) : ?>
	<div class="thb-product-slideshow-carousel flexslider" id="thb-product-carousel-<?php echo get_the_ID(); ?>">
		<ul class="slides">
			<?php
				if ( $attachment_ids ) {
					$loop = 0;

					foreach ( $attachment_ids as $attachment_id ) {

						$image_link = wp_get_attachment_url( $attachment_id );

						if ( ! $image_link )
							continue;

						$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
						$image_class = esc_attr( implode( ' ', $classes ) );
						$image_title = esc_attr( get_the_title( $attachment_id ) );

						printf( '<li class="slide">'. $image .'</li>', wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ), wp_get_attachment_url( $attachment_id ) );

						$loop++;
					}
				}
			?>
		</ul>
	</div>
	<?php endif; ?>

</div>
<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
			jQuery('#thb-product-carousel-<?php echo get_the_ID(); ?>').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				itemWidth: 90,
				itemMargin: 0,
				keyboard: false,
				asNavFor: '#thb-product-<?php echo get_the_ID(); ?>'
			});

			jQuery('#thb-product-<?php echo get_the_ID(); ?>').flexslider({
				animation: "slide",
				controlNav: false,
				animationLoop: false,
				slideshow: false,
				keyboard: true,
				smoothHeight: true,
		    	sync: "#thb-product-carousel-<?php echo get_the_ID(); ?>"
			});
	}, false);
</script>