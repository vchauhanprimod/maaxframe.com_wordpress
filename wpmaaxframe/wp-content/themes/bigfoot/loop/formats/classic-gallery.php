<?php
	$post_id = thb_get_page_ID();
	$post_layout = thb_get_post_meta($post_id, 'single_layout');
	$image_size = '';

	if( $post_id ) {
		$is_sidebar = thb_is_page_sidebar_active();
	}
	else {
		$is_sidebar = function_exists('dynamic_sidebar') && is_active_sidebar('post-sidebar');
	}

	if( $is_sidebar ) {
		$image_size = 'thumb-760-cropped';
	} else {
		$image_size = 'large-cropped';
	}

	$post_featured_image = thb_get_featured_image(get_the_ID(), $image_size);
	$post_gallery = thb_get_post_meta(get_the_ID(), 'gallery_shortcode');
?>

<header class="item-header<?php if( !empty($post_gallery) ) : ?> w-featured-image<?php endif; ?>">
	<h1>
		<a href="<?php the_permalink(); ?>" rel="permalink"><?php the_title(); ?></a>
	</h1>
	<footer class="item-footer">
		<span class="author"><?php _e('Posted by', 'thb_text_domain'); ?> <?php the_author_posts_link(); ?></span>
		<span class="pubdate"><?php _e('on', 'thb_text_domain'); ?> <?php echo get_the_date(); ?></span>
		<span class="comments" data-icon="i"><a href="<?php comments_link(); ?>"><?php thb_comments_number(); ?></a></span>
	</footer>
</header>

<?php
	echo do_shortcode('[thb_gallery size="'. $image_size . '" gallery_id="gallery-stream" link="file"]');
?>

<div class="item-content">
	<?php if( get_the_excerpt() != '' ) : ?>
		<div class="text">
			<?php the_excerpt(); ?>
		</div>
	<?php endif; ?>

	<a class="readmore" href="<?php the_permalink(); ?>">Read more</a>
</div>