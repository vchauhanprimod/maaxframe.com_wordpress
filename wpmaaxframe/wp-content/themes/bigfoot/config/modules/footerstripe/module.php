<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Footer stripe.
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

$thb_theme = thb_theme();

/**
 * Module configuration
 * -----------------------------------------------------------------------------
 */
$thb_config = array(
	/**
	 * The page templates that implement the page builder functionality.
	 */
	'templates' => array('default')
);
$thb_theme->setConfig('footerstripe', thb_array_asum($thb_config, $config));

/**
 * Including the file with the means to the creation of a Footer Stripe custom post
 * type.
 */
include dirname(__FILE__) . '/posttype.php';

thb_create_footerstripes_posttype();

/**
 * Metabox
 */
if( ! function_exists('thb_add_footerstripe_metabox') ) {
	function thb_add_footerstripe_metabox() {
		if( thb_is_admin_template(thb_config('footerstripe', 'templates')) ) {
			$thb_metabox = thb_theme()->getPostType('page')->getMetabox('layout');
			$thb_container = $thb_metabox->createContainer( __('Footer stripe', 'thb_text_domain'), 'footerstripe_container' );

				$thb_field = new THB_SelectField( 'footerstripe' );
				$thb_field->setLabel( __('Select', 'thb_text_domain') );
					$thb_field->setDynamicOptions('thb_get_footerstripes_for_select');
				$thb_container->addField($thb_field);
		}
	}

	add_action( 'init', 'thb_add_footerstripe_metabox' );
}

/**
 * Scripts and styles
 */
$thb_theme->getAdmin()->addScript( thb_get_module_url('footerstripe') . '/js/admin.js' );