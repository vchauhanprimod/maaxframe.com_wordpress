<?php
/**
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */
	$footer_layout = thb_get_option('footer_layout');

	if( empty($footer_layout) ) {
		return;
	}

	$columns_classes = explode(',', $footer_layout);
	$columns_number = count($columns_classes);

	$display_footer = false;
	for( $i=0; $i<$columns_number; $i++ ) {
		if( !$display_footer && is_active_sidebar('footer-sidebar-' . $i) ) {
			$display_footer = true;
		}
	}

	if( !$display_footer )
		return;

?>

<?php thb_footer_sidebar_before(); ?>
<section id="page-footer" class="sidebar">

	<?php thb_footer_sidebar_start(); ?>

	<?php $i=0; foreach( $columns_classes as $class ) : ?>
		<?php if( is_active_sidebar('footer-sidebar-' . $i) ) : ?>
			<section class="col <?php echo $class; ?>">
				<?php dynamic_sidebar( 'footer-sidebar-' . $i ); ?>
			</section>
		<?php endif; ?>
	<?php $i++; endforeach; ?>

	<?php thb_footer_sidebar_end(); ?>

</section>
<?php thb_footer_sidebar_after(); ?>