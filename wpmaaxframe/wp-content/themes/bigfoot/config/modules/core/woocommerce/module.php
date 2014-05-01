<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * WooCommerce.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Modules\WooCommerce
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2012, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

if( !function_exists( 'is_woocommerce' ) ) {
	return;
}

$thb_theme = thb_theme();

/**
 * Module configuration
 * -----------------------------------------------------------------------------
 */
$thb_config = array(
	/**
	 * Disable the default WooCommerce skin.
	 */
	'disable_core_skin' => false
);
$thb_theme->setConfig('core/woocommerce', thb_array_asum($thb_config, $config));

/**
 * Theme customizations
 * -----------------------------------------------------------------------------
 */
if( file_exists( THB_TEMPLATE_DIR . '/woocommerce/theme-woocommerce.php' )) {
	include THB_TEMPLATE_DIR . '/woocommerce/theme-woocommerce.php';
}

add_theme_support('woocommerce');

// Disable WooCommerce styles
if ( thb_config('core/woocommerce', 'disable_core_skin') ) {
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
}

/**
 * Woocommerce product page sidebar
 * -----------------------------------------------------------------------------
 */


/**
 * Theme options tab
 * -----------------------------------------------------------------------------
 */
$thb_page = $thb_theme->getAdmin()->getMainPage();

$thb_tab = new THB_Tab( __('WooCommerce', 'thb_text_domain'), 'woocommerce' );
	$thb_container = $thb_tab->createContainer( __('Shop page options', 'thb_text_domain'), 'woocommerce_shop_options' );

	$thb_field = new THB_SelectField( 'shop_columns' );
		$thb_field->setLabel( __('Columns layout', 'thb_text_domain') );
		$thb_field->setOptions(array(
			'3' => '3',
			'4' => '4'
		));
	$thb_container->addField($thb_field);

	$thb_field = new THB_SelectField( 'shop_sidebar' );
		$thb_field->setLabel( __('Shop page sidebar', 'thb_text_domain') );
		$thb_field->setOptions(array(
			0 => __('No sidebar', 'thb_text_domain')
		));
		$thb_field->setDynamicOptions('thb_get_sidebars_for_select');
	$thb_container->addField($thb_field);

	$thb_field = new THB_SelectField( 'shop_sidebar_position' );
		$thb_field->setLabel( __('Shop page sidebar position', 'thb_text_domain') );
		$thb_field->setOptions(array(
			'sidebar-left' => __('Left', 'thb_text_domain'),
			'sidebar-right' => __('Right', 'thb_text_domain')
		));
	$thb_container->addField($thb_field);

	$thb_field = new THB_NumberField('shop_products_per_page');
		$thb_field->setLabel( __('Products to show', 'thb_text_domain') );
		$thb_field->setHelp( __('Choose how many products will be displayed on Shop page.', 'thb_text_domain') );
	$thb_container->addField($thb_field);


	$thb_container = $thb_tab->createContainer( __('Product page options', 'thb_text_domain'), 'woocommerce_product_options' );

	$thb_field = new THB_NumberField('related_products_per_page');
		$thb_field->setLabel( __('Related product to show', 'thb_text_domain') );
		$thb_field->setHelp( __('Choose how many related posts will be displayed on single product page.', 'thb_text_domain') );
	$thb_container->addField($thb_field);

	$thb_field = new THB_SelectField( 'product_sidebar' );
		$thb_field->setLabel( __('Product page sidebar', 'thb_text_domain') );
		$thb_field->setOptions(array(
			0 => __('No sidebar', 'thb_text_domain')
		));
		$thb_field->setDynamicOptions('thb_get_sidebars_for_select');
	$thb_container->addField($thb_field);

	$thb_field = new THB_SelectField( 'product_sidebar_position' );
		$thb_field->setLabel( __('Product page sidebar position', 'thb_text_domain') );
		$thb_field->setOptions(array(
			'sidebar-left' => __('Left', 'thb_text_domain'),
			'sidebar-right' => __('Right', 'thb_text_domain')
		));
	$thb_container->addField($thb_field);

$thb_page->addTab($thb_tab);

if( !function_exists('thb_shop_columns_layout') ) {
	function thb_shop_columns_layout( $classes ) {
		if( is_woocommerce() ) {
			$classes[] = 'thb-shop-' . thb_get_option('shop_columns') .'col';
		}

		return $classes;
	}

	add_action('body_class', 'thb_shop_columns_layout');
}

if (!function_exists('thb_loop_columns')) {
	function thb_loop_columns() {
		if( is_woocommerce() ) {
			$shop_columns = thb_get_option('shop_columns');
			if( !empty($shop_columns) ) {
				return $shop_columns;
			}

			return '3';
		}
	}

	add_filter('loop_shop_columns', 'thb_loop_columns');
}

if( !function_exists('thb_shop_sidebar') ) {
	function thb_shop_sidebar( $classes ) {
		if( is_woocommerce() ) {
			foreach( $classes as $i => $class ) {
				if( $class == 'w-sidebar' ) {
					unset($classes[$i]);
				}
			}
		}

		return $classes;
	}

	add_filter('body_class', 'thb_shop_sidebar', 99);
}

if( ! function_exists('thb_get_woocommerce_sidebar_name') ) {
	function thb_get_woocommerce_sidebar_name() {
		$sidebar = '';

		if( is_product() ) {
			$sidebar = 'product_sidebar';
		}
		if( is_shop() ) {
			$sidebar = 'shop_sidebar';
		}

		$sidebar_name = thb_get_option($sidebar);
		$sidebar_name = apply_filters( 'thb_get_woocommerce_sidebar_name', $sidebar_name );

		return $sidebar_name;
	}
}

if( !function_exists('thb_woocommerce_body_classes') ) {
	function thb_woocommerce_body_classes( $classes ) {
		if( is_woocommerce() ) {
			$sidebar_name = thb_get_woocommerce_sidebar_name();

			if( is_active_sidebar( $sidebar_name ) ) {
				$classes[] = 'w-sidebar';
				$classes[] = thb_get_option( $sidebar_name . '_position' );
			}
		}

		return $classes;
	}

	add_filter('body_class', 'thb_woocommerce_body_classes', 999);
}

if( !function_exists('thb_woocommerce_sidebar') ) {
	function thb_woocommerce_sidebar() {
		$sidebar_name = thb_get_woocommerce_sidebar_name();
		thb_display_sidebar($sidebar_name);
	}
}

remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
add_action('woocommerce_sidebar', 'thb_woocommerce_sidebar');


remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_filter('woocommerce_show_page_title', '__return_false', 99);

if( !function_exists('woocommerce_output_related_products') ) {
	function woocommerce_output_related_products() {
		$related_per_page = thb_get_option('related_products_per_page');
		woocommerce_related_products($related_per_page,4);
	}
}

add_filter( 'loop_shop_per_page', create_function( '$cols', 'return "' . thb_get_option('shop_products_per_page') . '";' ), 20 );

/**
 * Shortcodes
 * -----------------------------------------------------------------------------
 */

/**
 * Products
 */
$shortcode = new THB_Shortcode('thb_products', 'shortcodes/products', 'core/woocommerce');
$shortcode->setAttributes(array(
	'title' => __('Products', 'thb_text_domain'),
	'columns'  => '4',
	'orderby'  => 'date',
	'order'    => 'desc'
));
$shortcode->setExample('[thb_products colums="4" title="Products"]');
$shortcode->setLabel( __('Products', 'thb_text_domain') );
$shortcode->setType( __('WooCommerce', 'thb_text_domain') );
$thb_theme->addShortcode($shortcode);

/**
 * Recent products
 */
$shortcode = new THB_Shortcode('thb_recent_products', 'shortcodes/recent_products', 'core/woocommerce');
$shortcode->setAttributes(array(
	'title' => __('Recent products', 'thb_text_domain'),
	'per_page' => '12',
	'columns'  => '4',
	'orderby'  => 'date',
	'order'    => 'desc'
));
$shortcode->setExample('[thb_recent_products per_page="12" colums="4" title="Recent products"]');
$shortcode->setLabel( __('Recent products', 'thb_text_domain') );
$shortcode->setType( __('WooCommerce', 'thb_text_domain') );
$thb_theme->addShortcode($shortcode);

/**
 * Featured products
 */
$shortcode = new THB_Shortcode('thb_featured_products', 'shortcodes/featured_products', 'core/woocommerce');
$shortcode->setAttributes(array(
	'title' => __('Featured products', 'thb_text_domain'),
	'per_page' => '12',
	'columns'  => '4',
	'orderby'  => 'date',
	'order'    => 'desc'
));
$shortcode->setExample('[thb_featured_products per_page="12" colums="4" title="Featured products"]');
$shortcode->setLabel( __('Featured products', 'thb_text_domain') );
$shortcode->setType( __('WooCommerce', 'thb_text_domain') );
$thb_theme->addShortcode($shortcode);

/**
 * Sale products
 */
$shortcode = new THB_Shortcode('thb_sale_products', 'shortcodes/sale_products', 'core/woocommerce');
$shortcode->setAttributes(array(
	'title' => __('Sale products', 'thb_text_domain'),
	'per_page' => '12',
	'columns'  => '4',
	'orderby'  => 'date',
	'order'    => 'desc'
));
$shortcode->setExample('[thb_sale_products per_page="12" colums="4" title="Sale products"]');
$shortcode->setLabel( __('Sale products', 'thb_text_domain') );
$shortcode->setType( __('WooCommerce', 'thb_text_domain') );
$thb_theme->addShortcode($shortcode);

/**
 * Best selling products
 */
$shortcode = new THB_Shortcode('thb_best_selling_products', 'shortcodes/best_selling_products', 'core/woocommerce');
$shortcode->setAttributes(array(
	'title' => __('Best selling products', 'thb_text_domain'),
	'per_page' => '12',
	'columns'  => '4',
	'orderby'  => 'date',
	'order'    => 'desc'
));
$shortcode->setExample('[thb_best_selling_products per_page="12" colums="4" title="Best selling products"]');
$shortcode->setLabel( __('Best selling products', 'thb_text_domain') );
$shortcode->setType( __('WooCommerce', 'thb_text_domain') );
$thb_theme->addShortcode($shortcode);

/**
 * Top rated products
 */
$shortcode = new THB_Shortcode('thb_top_rated_products', 'shortcodes/top_rated_products', 'core/woocommerce');
$shortcode->setAttributes(array(
	'title' => __('Top rated products', 'thb_text_domain'),
	'per_page' => '12',
	'columns'  => '4',
	'orderby'  => 'date',
	'order'    => 'desc'
));
$shortcode->setExample('[thb_top_rated_products per_page="12" colums="4" title="Top rated products"]');
$shortcode->setLabel( __('Top rated products', 'thb_text_domain') );
$shortcode->setType( __('WooCommerce', 'thb_text_domain') );
$thb_theme->addShortcode($shortcode);