<?php
	$post_gallery = thb_get_post_meta(get_the_ID(), 'gallery_shortcode');
	echo do_shortcode('[thb_gallery size="large-cropped" gallery_id="gallery-stream" link="file"]');
?>