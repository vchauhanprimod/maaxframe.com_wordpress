<?php
/**
 *
 * BoldR Lite WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013-2014 Mathieu Sarrasin - Iceable Media
 *
 * Sidebar Template
 *
 */

?><ul id="sidebar"><?php
if ( ! dynamic_sidebar( 'Sidebar' ) ):
	?><li id="recent" class="widget-container"><?php
		?><h3 class="widget-title"><?php _e( 'Recent Posts', 'boldr' ); ?></h3><?php
		?><ul><?php wp_get_archives( 'type=postbypost&limit=5' ); ?></ul><?php
	?></li><?php
	
	?><li id="archives" class="widget-container"><?php
		?><h3 class="widget-title"><?php _e( 'Archives', 'boldr' ); ?></h3><?php
		?><ul><?php wp_get_archives( 'type=monthly' ); ?></ul><?php
	?></li><?php

	?><li id="meta" class="widget-container"><?php
		?><h3 class="widget-title"><?php _e( 'Meta', 'boldr' ); ?></h3><?php
			?><ul><?php
				wp_register();
				?><li><?php wp_loginout(); ?></li><?php
				wp_meta();
			?></ul><?php
	?></li><?php
endif;
?></ul>