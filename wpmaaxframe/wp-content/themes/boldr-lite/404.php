<?php
/**
 *
 * BoldR Lite WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013-2014 Mathieu Sarrasin - Iceable Media
 *
 * 404 Page Template
 *
 */

get_header();

?><div class="container" id="main-content"><?php

	?><h1 class="page-title"><?php _e('404', 'boldr'); ?></h1><?php

	?><div id="page-container" class="left with-sidebar"><?php

		?><h2><?php _e('Page Not Found', 'boldr'); ?></h2><?php
		?><p><?php _e('What you are looking for isn\'t here...', 'boldr'); ?></p><?php
		?><p><?php _e('Maybe a search will help ?', 'boldr'); ?></p><?php
		get_search_form();

	?></div><?php // End page container

	?><div id="sidebar-container" class="right"><?php
		?><ul id="sidebar"><?php
		dynamic_sidebar( 'sidebar' );
		?></ul><?php
	?></div><?php // End sidebar
?></div><?php // End main content

get_footer(); ?>