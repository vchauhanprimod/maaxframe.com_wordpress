<?php
	$post_id = get_the_ID();
	$post_layout = thb_get_post_meta($post_id, 'single_layout');
	$meta = thb_get_post_meta_all($post_id);
	extract($meta);
?>

<header class="item-header" data-icon="<?php thb_get_post_icon(get_the_ID()); ?>">
	<h1>
		<a href="<?php the_permalink(); ?>" rel="permalink">
			<?php echo $quote; ?>
		</a>
	</h1>
	
	<?php if( !empty($quote_author) ) : ?>
		<cite>
			<?php if( !empty($quote_url) ) : ?>
				<a href="<?php echo $quote_url; ?>"><?php echo $quote_author; ?></a>
			<?php else : ?>
				<?php echo $quote_author; ?>
			<?php endif; ?>
		</cite>
	<?php endif; ?>
</header>