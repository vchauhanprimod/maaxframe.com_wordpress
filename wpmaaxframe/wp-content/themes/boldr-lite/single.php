<?php
/**
 *
 * BoldR Lite WordPress Theme by Iceable Themes | http://www.iceablethemes.com
 *
 * Copyright 2013-2014 Mathieu Sarrasin - Iceable Media
 *
 * Single Post Template
 *
 */

get_header();

?><div class="container" id="main-content"><?php

	?><div id="page-container" class="left with-sidebar"><?php

		if(have_posts()):
		while(have_posts()) : the_post();

		?><div id="post-<?php the_ID(); ?>" <?php post_class("single-post"); ?>><?php

			?><div class="postmetadata"><?php
				?><span class="meta-date"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php
					?><span class="month"><?php the_time('M'); ?></span><?php
					?><span class="day"><?php the_time('d'); ?></span><?php
					?><span class="year"><?php the_time('Y'); ?></span><?php
				?></a></span><?php

				if ( ( comments_open() || get_comments_number()!=0 ) && !post_password_required() ):
				?><span class="meta-comments"><?php
					comments_popup_link( __( 'No', 'boldr' ), __( '1', 'boldr' ), __( '%', 'boldr' ), 'comments-count', '' );
					comments_popup_link( __( 'Comment', 'boldr' ), __( 'Comment', 'boldr' ), __( 'Comments', 'boldr' ), '', __('Comments Off', 'boldr') );
				?></span><?php
				endif;

				?><span class="meta-author"><span><?php _e('by ', 'boldr'); the_author(); ?></span></span><?php

				edit_post_link(__('Edit', 'boldr'), '<span class="editlink">', '</span>');

			?></div><?php

			if (has_post_thumbnail()):
			?><div class="thumbnail"><a href="<?php get_permalink() ?>"><?php
				the_post_thumbnail('large', array('class' => 'scale-with-grid'));
			?></a></div><?php
			endif;

			?><div class="post-contents"><?php
				?><h3 class="entry-title"><?php
				?><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a><?php
				?></h3><?php
				if ( has_category() ):
					?><div class="post-category"><?php _e('Posted in', 'boldr'); ?> <?php the_category(', '); ?></div><?php
				endif;

				the_content();

				?><div class="clear"></div><?php
				$args = array(
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
				wp_link_pages( $args );

				if (has_tag()) the_tags('<div class="tags"><span class="the-tags">'.__('Tags', 'boldr').':</span>', '', '</div>');


			?></div><br class="clear" /><?php

		?></div><?php // end div post

		?><div class="article_nav"><?php
			if ( is_attachment() ): // Use image navigation links on attachment pages, post navigation otherwise
				if ( boldr_adjacent_image_link(false) ): // Is there a previous image ?
				?><div class="previous"><?php previous_image_link(0, __("Previous Image", 'boldr') ); ?></div><?php 
				endif;
				if ( boldr_adjacent_image_link(true) ): // Is there a next image ?
				?><div class="next"><?php next_image_link(0, __("Next Image",'boldr') ); ?></div><?php
				endif;
			else:
				if ("" != get_adjacent_post( false, "", true ) ): // Is there a previous post?
				?><div class="previous"><?php previous_post_link('%link', __("Previous Post", 'boldr') ); ?></div><?php
				endif;
				if ("" != get_adjacent_post( false, "", false ) ): // Is there a next post?
				?><div class="next"><?php next_post_link('%link', __("Next Post", 'boldr') ); ?></div><?php
				endif;
			endif;
			?><br class="clear" /><?php
		?></div><?php


		// Display comments section only if comments are open or if there are comments already.
		if ( comments_open() || get_comments_number()!=0 ):
			?><hr /><?php
			?><div class="comments"><?php comments_template( '', true ); ?></div><?php

			?><div class="article_nav"><?php
				if ("" != get_adjacent_post( false, "", true ) ): // Is there a previous post?
				?><div class="previous"><?php previous_post_link('%link', __("Previous Post", 'boldr') ); ?></div><?php
				endif;
				if ("" != get_adjacent_post( false, "", false ) ): // Is there a next post?
				?><div class="next"><?php next_post_link('%link', __("Next Post", 'boldr') ); ?></div><?php
				endif;
				?><br class="clear" /><?php
			?></div><?php
		endif;

		endwhile;

		else:

		?><h2><?php _e('Not Found', 'boldr'); ?></h2><?php
		?><p><?php _e('What you are looking for isn\'t here...', 'boldr'); ?></p><?php

		endif;

	?></div><?php // End page container

	?><div id="sidebar-container" class="right"><?php get_sidebar(); ?></div><?php

?></div><?php // End main content

get_footer(); ?>