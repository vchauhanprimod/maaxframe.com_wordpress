<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Photogallery.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Modules\Core\Photogallery
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
	 * A list of page templates that implement the Photogallery module.
	 */
	'templates' => array(),

	/**
	 * In Photogallery page templates, this are the image sizes
	 * to be used by photo thumbnails. The first element of the array is the
	 * fixed height version, the second one the variable height version.
	 */
	'image_sizes' => array(),

	/**
	 * In case of not specifying the Photogallery image sizes, assign a default
	 * one to the Photogallery thumbnails.
	 */
	'image_size' => 'large',

	/**
	 * In Photogallery page templates displaying a grid, select the label of the
	 * columns control.
	 */
	'grid_templates_columns_label' => __('Gallery columns', 'thb_text_domain'),

	/**
	 * In Photogallery page templates displaying a grid, select how many columns
	 * to display.
	 */
	'grid_templates_columns' => array(
		'2' => '2',
		'3' => '3',
		'4' => '4'
	),

	/**
	 * The rel attribute to be assigned to the Photogallery thumbnails.
	 */
	'item_thumb_rel' => ''
);
$thb_theme->setConfig('core/photogallery', thb_array_asum($thb_config, $config));

/**
 * Photogallery metabox
 * -----------------------------------------------------------------------------
 */
if( !function_exists('thb_photogallery_container') ) {
	function thb_photogallery_container() {
		$thb_theme = thb_theme();
		$thb_photogallery_page_template = thb_config('core/photogallery', 'templates');

		if( thb_is_admin_template( $thb_photogallery_page_template ) ) {
			$thb_metabox = new THB_Metabox( __('Gallery', 'thb_text_domain'), 'slideshow' );

				$thb_container = $thb_metabox->createContainer( __('Configuration', 'thb_text_domain'), 'photogallery_masonry_details_container' );
				$thb_field = new THB_NumberField( 'slides_per_page' );
					$thb_field->setLabel( __('Pictures per page', 'thb_text_domain') );
					// $thb_field->setHelp( __('In case of AJAX loading, chose how many pictures to display.', 'thb_text_domain') );
					$thb_field->setHelp( __('Choose how many pictures to load dinamically. Leaving this empty will show all the images available.', 'thb_text_domain') );
				$thb_container->addField($thb_field);

				$thb_field = new THB_SelectField( 'portfolio_columns' );
				$thb_field->setLabel( thb_config('core/photogallery', 'grid_templates_columns_label') );
					$thb_field->setOptions( thb_config('core/photogallery', 'grid_templates_columns') );
				$thb_container->addField($thb_field);

				$image_sizes = thb_config('core/photogallery', 'image_sizes');

				if( !empty($image_sizes) ) {
					$slides_size_options = array();

					if( is_array(current($image_sizes)) ) {
						$slides_size_options[] = __('Fixed', 'thb_text_domain');
						$slides_size_options[] = __('Variable', 'thb_text_domain');
					}
					else {
						$slides_size_options[$image_sizes[0]] = __('Fixed', 'thb_text_domain');
						$slides_size_options[$image_sizes[1]] = __('Variable', 'thb_text_domain');
					}

					$thb_field = new THB_SelectField( 'slides_size' );
						$thb_field->setLabel( __('Photos height', 'thb_text_domain') );
						$thb_field->setOptions($slides_size_options);
					$thb_container->addField($thb_field);
				}

				$thb_container = $thb_metabox->createDuplicableContainer( __('Photos', 'thb_text_domain'), 'photogallery_slides' );
				$thb_container->setSortable();

					$thb_container->addControl( __('Add photo', 'thb_text_domain'), 'add_image', 'images.png', array(
						'action' => 'thb_add_multiple_slides',
						'title' => __('Add photos', 'thb_text_domain')
					) );

					$field = new THB_SlideField( 'photogallery_slide' );
					$field->setLabel( __('Slide', 'thb_text_domain') );
					$thb_container->setField($field);

			$thb_theme->getPostType('page')->addMetabox($thb_metabox);
		}
	}

	add_action('after_setup_theme', 'thb_photogallery_container');
}

if( ! function_exists('thb_photogallery') ) {
	function thb_photogallery() {
		thb_get_module_template_part('core/photogallery', 'photogallery');
	}
}

/**
 * Scripts and styles
 * -----------------------------------------------------------------------------
 */
$thb_theme->getFrontend()->addStyle(THB_FRONTEND_CSS_URL . '/isotope.css', array(
	'templates' => thb_config('core/photogallery', 'templates')
));

$thb_theme->getFrontend()->addScript(THB_FRONTEND_JS_URL . '/jquery.isotope.min.js', array(
	'templates' => thb_config('core/photogallery', 'templates')
));