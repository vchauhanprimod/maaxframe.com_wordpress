<?php
	$sizes = thb_get_post_meta(get_the_ID(), 'single_featured_size');
	$post_featured_image = thb_get_post_thumbnail_src(get_the_ID(), $sizes);
?>

<?php if( !empty($post_featured_image) ) : ?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="item-thumb">
		<span class="thb-overlay"></span>
		<img src="<?php echo $post_featured_image; ?>" alt="">
	</a>
<?php endif; ?>