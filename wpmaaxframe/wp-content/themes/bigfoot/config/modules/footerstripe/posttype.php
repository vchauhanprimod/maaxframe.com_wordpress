<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Footer stripe custom post type.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Modules\FooterStripe
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2012, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Footer Stripe post type
 * -----------------------------------------------------------------------------
 */
if( !function_exists('thb_create_footerstripes_posttype') ) {
	/**
	 * Create a Footer Stripe custom post type and add it to the theme.
	 *
	 * @return void
	 */
	function thb_create_footerstripes_posttype() {
		/**
		 * The post type labels.
		 *
		 * @see http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		$thb_footerstripes_labels = array(
			'name'               => __('Footer Stripes', 'thb_text_domain'),
			'singular_name'      => __('Footer Stripe', 'thb_text_domain'),
			'add_new'            => __('Add new', 'thb_text_domain'),
			'add_new_item'       => __('Add new Footer Stripe', 'thb_text_domain'),
			'edit'               => __('Edit', 'thb_text_domain'),
			'edit_item'          => __('Edit Footer Stripe', 'thb_text_domain'),
			'new_item'           => __('New Footer Stripe', 'thb_text_domain'),
			'view'               => __('View Footer Stripe', 'thb_text_domain'),
			'view_item'          => __('View Footer Stripe', 'thb_text_domain'),
			'search_items'       => __('Search Footer Stripes', 'thb_text_domain'),
			'not_found'          => __('No Footer Stripes found', 'thb_text_domain'),
			'not_found_in_trash' => __('No Footer Stripes found in Trash', 'thb_text_domain'),
			'parent'             => __('Parent Footer Stripe', 'thb_text_domain')
		);

		/**
		 * The post type arguments.
		 *
		 * @see http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		$thb_footerstripes_args = array(
			'labels'            => $thb_footerstripes_labels,
			'public'            => true,
			'show_ui'           => true,
			'capability_type'   => 'post',
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => 'footerstripes', 'with_front' => true ),
			'query_var'         => true,
			'show_in_nav_menus' => false,
			'supports'          => array('title')
		);

		/**
		 * Create the post type object.
		 */
		$thb_footerstripes = new THB_PostType('footerstripes', $thb_footerstripes_args);
		$thb_footerstripes->setPublicContent(false);

		/**
		 * Add the post type to the theme instance.
		 */
		thb_theme()->addPostType($thb_footerstripes);

		/**
		 * Post type metaboxes
		 */
		add_action('wp_loaded', 'thb_add_footerstripes_posttype_config_metabox');
	}
}

if( ! function_exists('thb_add_footerstripes_posttype_config_metabox') ) {
	function thb_add_footerstripes_posttype_config_metabox() {
		$consumer_key = thb_get_option('twitter_consumer_key');
		$consumer_secret = thb_get_option('twitter_consumer_secret');
		$oauth_token = thb_get_option('twitter_oauth_token');
		$oauth_token_secret = thb_get_option('twitter_oauth_token_secret');
		$config_note = '';

		if( $consumer_key == '' || $consumer_secret == '' || $oauth_token == '' || $oauth_token_secret == '' ) {
			$config_note = __('Make sure to fill the required Twitter API settings in the "Theme options > Social" tab.', 'thb_text_domain');
		}

		$post_type = thb_theme()->getPostType('footerstripes');

		$thb_metabox = new THB_Metabox( __('Footer contents', 'thb_text_domain'), 'footerstripes_config' );
			$thb_container = $thb_metabox->createContainer( '', 'footerstripes_config_container' );

				$thb_field = new THB_SelectField( 'footerstripes_content_type' );
				$thb_field->setLabel( __('Content type', 'thb_text_domain') );
					$thb_field->setOptions(array(
						'twitter'        => __('Twitter', 'thb_text_domain'),
						'call-to-action' => __('Call to action', 'thb_text_domain'),
						'social' => __('Social', 'thb_text_domain')
					));
				$thb_container->addField($thb_field);

			// Twitter

			$thb_container = $thb_metabox->createContainer( __('Twitter', 'thb_text_domain'), 'footerstripes_content_type_twitter' );
				$thb_container->setIntroText($config_note);

				$thb_field = new THB_TextField( 'footerstripes_twitter_username' );
				$thb_field->setLabel( __('Username', 'thb_text_domain') );
				$thb_container->addField($thb_field);

				$thb_field = new THB_NumberField( 'footerstripes_twitter_num' );
				$thb_field->setLabel( __('Tweets #', 'thb_text_domain') );
				$thb_container->addField($thb_field);

			// Social
			$thb_container = $thb_metabox->createContainer( __('Social', 'thb_text_domain'), 'footerstripes_content_type_social' );

				$thb_field = new THB_TextField( 'footerstripes_social_services' );
				$thb_field->setLabel( __('Services', 'thb_text_domain') );
				$thb_field->setHelp( __('Comma separated, order matters', 'thb_text_domain') . '. ' . __('Possible values', 'thb_text_domain') . ': twitter, facebook, googleplus, flickr, youtube, vimeo, pinterest, dribbble, forrst.' );
				$thb_container->addField($thb_field);

			// Call to action

			$thb_container = $thb_metabox->createContainer( __('Call to action', 'thb_text_domain'), 'footerstripes_content_type_call-to-action' );

				$thb_field = new THB_TextField( 'footerstripes_call-to-action_big_text' );
				$thb_field->setLabel( __('Big text', 'thb_text_domain') );
				$thb_container->addField($thb_field);

				$thb_field = new THB_TextareaField( 'footerstripes_call-to-action_small_text' );
				$thb_field->setLabel( __('Small text', 'thb_text_domain') );
				$thb_container->addField($thb_field);

				$thb_field = new THB_TextField( 'footerstripes_call-to-action_btn_text' );
				$thb_field->setLabel( __('Button text', 'thb_text_domain') );
				$thb_container->addField($thb_field);

				$thb_field = new THB_TextField( 'footerstripes_call-to-action_btn_url' );
				$thb_field->setLabel( __('Button URL', 'thb_text_domain') );
				$thb_container->addField($thb_field);

		$post_type->addMetabox($thb_metabox);
	}
}

if( ! function_exists('thb_get_footerstripes_for_select') ) {
	function thb_get_footerstripes_for_select() {
		$footer_stripes = get_posts(array(
			'post_type' => 'footerstripes'
		));

		$footer_stripes_select = array(
			0 => __('No footer stripe', 'thb_text_domain')
		);

		foreach( $footer_stripes as $fs ) {
			$footer_stripes_select[$fs->ID] = $fs->post_title;
		}

		return $footer_stripes_select;
	}
}