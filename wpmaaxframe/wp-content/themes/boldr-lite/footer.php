<?php
/**
 *
 * BoldR Lite WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013-2014 Mathieu Sarrasin - Iceable Media
 *
 * Footer Template
 *
 */


	if (is_active_sidebar( 'footer-sidebar' ) ):
		?><div id="footer"><div class="container"><ul><?php
			dynamic_sidebar( 'footer-sidebar' );
		?></ul></div></div><?php
	endif;

	?><div id="sub-footer"><div class="container"><?php
		?><div class="sub-footer-left"><?php 

/* You are free to modify or replace this by anything you like as per the terms of the GPL license */ ?>

			Copyright &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.
			<?php printf( __( 'Proudly powered by', 'boldr' ) ); ?><a href="<?php echo esc_url( 'http://wordpress.org/' ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'boldr' ); ?>"> WordPress</a>. BoldR design by <a href="<?php echo esc_url( 'http://www.iceablethemes.com' ); ?>" title="<?php esc_attr_e( 'Iceablethemes', 'boldr' ); ?>">Iceable Themes</a>.

<?php /* Stop editing here */
			?></div><?php

		?><div class="sub-footer-right"><?php
			$footer_menu = array( 'theme_location' => 'footer-menu', 'depth' => 1);
			wp_nav_menu( $footer_menu );
		?></div><?php
	?></div></div><?php // End sub footer

?></div><?php // End main wrap

wp_footer();

?></body></html>