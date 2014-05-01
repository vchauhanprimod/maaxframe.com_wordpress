<?php
	$post_featured_image = thb_get_post_thumbnail_src(get_the_ID(), 'thumb-360');
?>

<?php if( !empty($post_featured_image) ) : ?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="item-thumb">
		<span class="thb-overlay"></span>
		<img src="<?php echo $post_featured_image; ?>" alt="">
	</a>
<?php endif; ?>

<div class="item-wrapper<?php if( !empty($post_featured_image) ) : ?> w-featured-image<?php endif; ?>">
	<header class="item-header">
		<h1>
			<a href="<?php the_permalink(); ?>" rel="permalink"><?php the_title(); ?></a>
		</h1>
		<time pubdate class="pubdate">
			<?php echo get_the_date(); ?>
		</time>
	</header>

	<?php if( get_the_excerpt() != '' ) : ?>
		<div class="text">
			<?php the_excerpt(); ?>
		</div>
	<?php endif; ?>

	<footer class="item-footer">
		<span class="comments" data-icon="i"><a href="<?php comments_link(); ?>"><?php thb_comments_number(); ?></a></span>
		<span class="thb-format-icon"><?php thb_get_post_icon(get_the_ID()); ?></span>
	</footer>
</div>