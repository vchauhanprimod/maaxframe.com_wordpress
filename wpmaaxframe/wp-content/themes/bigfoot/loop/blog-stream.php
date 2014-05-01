<?php
	$thb_page_id = thb_get_page_ID();
	thb_post_query();
?>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		jQuery.thb.isotope({
			itemsContainer: '#stream-container',
			itemsClass: '.hentry',
		});
	}, false);
</script>

<div id="stream-container">
	<?php if( have_posts() ) : $i=1; while( have_posts() ) : the_post(); ?>
		<?php thb_post_before(); ?>
		<?php
			$post_id = get_the_ID();
			$post_classes = thb_get_post_classes( $i, array('item list'), 2 );
			$post_classes[] = 'stream';
		?>

		<div id="post-<?php echo $post_id; ?>" <?php post_class($post_classes); ?>>
			<?php thb_post_start(); ?>
			<?php get_template_part( 'loop/formats/stream-' . thb_get_post_format() ); ?>
			<?php thb_post_end(); ?>
		</div>

		<?php thb_post_after(); ?>
	<?php $i++; endwhile; ?>

	<?php else : ?>

		<div class="notice warning">
			<p><?php _e('Sorry, there aren\'t posts to be shown!', 'thb_text_domain'); ?></p>
		</div>

	<?php endif; ?>

</div>

<?php thb_pagination( array( 'type' => 'numbers' ) ); ?>