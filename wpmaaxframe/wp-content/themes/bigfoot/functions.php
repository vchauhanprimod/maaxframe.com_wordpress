<?php
/**
 * Dev functions and definitions.
 *
 * @package WordPress
 * @subpackage BigFoot
 * @since BigFoot 1.0
 */

/**
 * Framework and configuration.
 * PLEASE LEAVE THIS AREA UNTOUCHED, IN ORDER NOT TO BREAK CORE FUNCTIONALITY.
 * -----------------------------------------------------------------------------
 */
if( !defined('THB_THEME_KEY') ) define( 'THB_THEME_KEY', 'bigfoot' ); // Required, not displayed anywhere.

include 'framework/boot.php'; // Framework
include 'config/config.php'; // Theme setup

/**
 * You can start adding your custom functions from here!
 * -----------------------------------------------------------------------------
 */

if( !isset($content_width) ) $content_width = 1400;

/**
 * Prints jQuery in footer on front-end.
 */
// function ds_print_jquery_in_footer( &$scripts) {
// 	if ( ! is_admin() ) {
// 		$scripts->add_data( 'jquery', 'group', 1 );
// 		$scripts->add_data( 'swfobject', 'group', 1 );
// 		$scripts->add_data( 'comment-reply', 'group', 1 );
// 	}
// }
// add_action( 'wp_default_scripts', 'ds_print_jquery_in_footer' );