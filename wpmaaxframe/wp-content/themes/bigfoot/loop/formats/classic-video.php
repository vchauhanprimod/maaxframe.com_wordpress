<?php
	$post_id = get_the_ID();
	$post_layout = thb_get_post_meta($post_id, 'single_layout');
	$video_url = thb_get_post_meta($post_id, 'video_url' );
?>

<header class="item-header<?php if( !empty($video_url) ) : ?> w-featured-image<?php endif; ?>">
	<h1>
		<a href="<?php the_permalink(); ?>" rel="permalink"><?php the_title(); ?></a>
	</h1>
	<footer class="item-footer">
		<span class="author"><?php _e('Posted by', 'thb_text_domain'); ?> <?php the_author_posts_link(); ?></span>
		<span class="pubdate"><?php _e('on', 'thb_text_domain'); ?> <?php echo get_the_date(); ?></span>
		<span class="comments" data-icon="i"><a href="<?php comments_link(); ?>"><?php thb_comments_number(); ?></a></span>
	</footer>
</header>

<?php if( !empty($video_url) ) : ?>
	<?php echo do_shortcode('[thb_video url="'. $video_url .'"]'); ?>
<?php endif; ?>

<div class="item-content">
	<?php if( get_the_excerpt() != '' ) : ?>
		<div class="text">
			<?php the_excerpt(); ?>
		</div>
	<?php endif; ?>

	<a class="readmore" href="<?php the_permalink(); ?>">Read more</a>
</div>