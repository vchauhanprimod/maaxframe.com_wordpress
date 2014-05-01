<?php
	$category = get_the_category();
?>

<header class="item-header">
	<h1>
		<a href="<?php the_permalink(); ?>" rel="permalink"><?php the_title(); ?></a>
	</h1>
	<footer class="item-footer">
		<span class="pubdate"><?php echo get_the_date(); ?></span>
		<?php if( thb_show_comments() ) : ?>
			- <span class="comments"><a href="<?php comments_link(); ?>"><?php thb_comments_number(); ?></a></span>
		<?php endif; ?>
		<?php if( !empty($category) ) : ?>
			- <span class="category"><?php _e('in', 'thb_text_domain'); ?> <?php the_category(', '); ?></span>
		<?php endif; ?>
	</footer>
</header>

<div class="item-content">
	<?php if( get_the_excerpt() != '' ) : ?>
		<div class="text">
			<?php the_excerpt(); ?>
		</div>
	<?php endif; ?>
</div>