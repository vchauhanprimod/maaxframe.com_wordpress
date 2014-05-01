<?php
	$video_url = thb_get_post_meta( get_the_ID(), 'video_url' );
?>

<?php if( !empty($video_url) ) : ?>
	<?php echo do_shortcode('[thb_video url="'. $video_url .'"]'); ?> 
<?php endif; ?>