<?php
/**
 *
 * BoldR Lite WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013-2014 Mathieu Sarrasin - Iceable Media
 *
 * Page Template
 *
 */

get_header();

if(have_posts()) :
while(have_posts()) : the_post();

	?><div class="container" id="main-content"><?php

		?><h1 class="page-title"><?php the_title(); ?></h1><?php

		?><div id="page-container" <?php post_class("left with-sidebar"); ?>><?php

				the_content();
				$boldr_link_pages_args = array(
					'before'           => '<br class="clear" /><div class="paged_nav">' . __('Pages:', 'boldr'),
					'after'            => '</div>',
					'link_before'      => '<span>',
					'link_after'       => '</span>',
					'next_or_number'   => 'number',
					'nextpagelink'     => __('Next page', 'boldr'),
					'previouspagelink' => __('Previous page', 'boldr'),
					'pagelink'         => '%',
					'echo'             => 1
				);
				wp_link_pages( $boldr_link_pages_args );
				?><br class="clear" /><?php
				edit_post_link(__('Edit', 'boldr'), '<span class="editlink">', '</span><br class="clear" />');
				?><br class="clear" /><?php

				// Display comments section only if comments are open or if there are comments already.
				if ( comments_open() || get_comments_number()!=0 ):

					?><div class="comments"><?php
					comments_template( '', true );
					next_comments_link(); previous_comments_link();
					?></div><?php

				endif;

	endwhile;
	
	else:
		?><h2><?php _e('Not Found', 'boldr'); ?></h2><?php
		?><p><?php _e('What you are looking for isn\'t here...', 'boldr'); ?></p><?php
	endif;

	?></div><?php // End page container

	?><div id="sidebar-container" class="right"><?php
		?><ul id="sidebar"><?php dynamic_sidebar( 'sidebar' ); ?></ul><?php
	?></div><?php // End sidebar

?></div><?php // End main content

get_footer(); ?>