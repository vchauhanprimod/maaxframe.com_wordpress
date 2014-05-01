<?php

$atts = array(
	'title' => $title,
	'per_page' => $per_page,
	'columns'  => $columns,
	'orderby'  => $orderby,
	'order'    => $order
);

$shortcode_class = 'thb-shop-' . $atts['columns'] . 'col'; 

?>


<div class="thb-shortcode <?php echo $shortcode_class; ?>">
	<?php if( ! empty($atts['title']) ) : ?>
		<h1 class="thb-shortcode-title"><?php echo thb_text_format($atts['title']); ?></h1>
	<?php endif; ?>

	<?php echo $GLOBALS['woocommerce']->shortcodes->featured_products($atts); ?>
</div>