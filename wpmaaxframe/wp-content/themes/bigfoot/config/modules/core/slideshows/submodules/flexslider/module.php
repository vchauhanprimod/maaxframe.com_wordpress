<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Flexslider implementation of the Slideshow module.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Modules\Core\Slideshow\Flexslider
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
	 * Slideshow default image size
	 */
	'image_size' => 'slideshow'
);
$thb_theme->setConfig('core/slideshows/submodules/flexslider', thb_array_asum($thb_config, $config));

/**
 * Module bootstrap
 * -----------------------------------------------------------------------------
 */

/**
 * Set the module to require the post type to be loaded.
 */
thb_theme()->setConfigKey('core/slideshows', 'post_type', true);

if( !function_exists('thb_flexslider_register_templates') ) {
	function thb_flexslider_register_templates( $templates ) {
		$flexslider_templates = thb_config('core/slideshows/submodules/flexslider', 'templates');

		foreach( $flexslider_templates as $t ) {
			if( !in_array($t, $templates) ) {
				$templates[] = $t;
			}
		}

		return $templates;
	}

	add_filter('thb_slideshows_templates', 'thb_flexslider_register_templates');
}

if( !function_exists('thb_flexslider_register') ) {
	/**
	 * Register the "flexslider" type as a valid Slideshow type.
	 *
	 * @param array $types The Slideshow types.
	 * @return array
	 */
	function thb_flexslider_register( $types ) {
		$types['flexslider'] = 'Flexslider';
		return $types;
	}

	add_filter('thb_slideshows_types', 'thb_flexslider_register');
}

if( ! function_exists('thb_flexslider_config_create_container') ) {
	/**
	 * Created the Flexslider configuration options container.
	 *
	 * @param strubg $label The container label.
	 * @param strubg $slug The container slug.
	 * @return THB_MetaboxFieldsContainer
	 */
	function thb_flexslider_config_create_container( $label='', $slug='flexslider_options' ) {
		if( empty($label) ) {
			$label = __('Flexslider options', 'thb_text_domain');
		}

		$thb_container = new THB_MetaboxFieldsContainer( $label, $slug );

		$field = new THB_NumberField( 'slideshowHeight' );
		$field->setLabel( __('Height', 'thb_text_domain') );
		$field->setMin('0');
		$thb_container->addField($field);

		$field = new THB_YesNoField( 'flexslider_smoothHeight' );
		$field->setLabel( __('Variable height', 'thb_text_domain') );
		$field->setDefault('1');
		$thb_container->addField($field);

		$field = new THB_SelectField( 'flexslider_effects' );
		$field->setLabel( __('Effects', 'thb_text_domain') );
		$field->setOptions(array(
			'fade' => __('Fade', 'thb_text_domain'),
			'slide' => __('Slide', 'thb_text_domain')
		));
		$thb_container->addField($field);

		return $thb_container;
	}
}

if( !function_exists('thb_flexslider_config_container') ) {
	/**
	 * Add the Flexslider configuration options to the Slideshow config metabox.
	 *
	 * @param THB_Metabox $thb_metabox The Slideshow configuration metabox.
	 * @return THB_Metabox
	 */
	function thb_flexslider_config_container( $thb_metabox ) {
		$thb_container = thb_flexslider_config_create_container();
		$thb_metabox->addContainer( $thb_container );

		return $thb_metabox;
	}

	add_filter('thb_slideshow_config_metabox', 'thb_flexslider_config_container');
}

/**
 * Scripts and styles
 * -----------------------------------------------------------------------------
 */
if( !function_exists('thb_flexslider_scripts_and_styles') ) {
	function thb_flexslider_scripts_and_styles() {
		$config = thb_config('core/slideshows');
		$thb_flex = thb_get_module_url('core/slideshows/submodules/flexslider');
		
		thb_theme()->getFrontend()->addScript($thb_flex . '/js/config.js', array(
			'templates' => $config['templates']
		));
		
		thb_theme()->getFrontend()->addScript($thb_flex . '/js/thb.slideshows_flexslider.js', array(
			'templates' => $config['templates']
		));
	}

	add_action('wp_loaded', 'thb_flexslider_scripts_and_styles');
}