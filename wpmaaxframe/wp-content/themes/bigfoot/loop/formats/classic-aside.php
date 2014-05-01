<?php
	$post_id = get_the_ID();
	$post_layout = thb_get_post_meta($post_id, 'single_layout');
?>

<div class="item-content">
	<?php if( get_the_excerpt() != '' ) : ?>
		<div class="text">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>
</div>