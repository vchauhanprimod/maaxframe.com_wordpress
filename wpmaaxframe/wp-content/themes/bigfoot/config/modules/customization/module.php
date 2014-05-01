<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Customization.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *+
 * @package Modules\Customization
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2012, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$thb_theme = thb_theme();
$thb_tc = $thb_theme->getCustomization();

$app_counter = 0;

$thb_tc->addSection( 'thb_general', __('General', 'thb_text_domain') );

$thb_highlight_color = array(
	'a:hover,
	form .required,
	.page-template-template-blog-stream-php #content .item .item-header h1 a:hover,
	.page-template-template-blog-stream-php #content .item.format-quote .item-header h1 a:hover, .page-template-template-blog-stream-php #content .item.format-quote .item-header cite a:hover,
	.page-template-template-blog-classic-php #content .item.format-quote .item-header h1 a:hover, .page-template-template-blog-classic-php #content .item.format-quote .item-header cite a:hover, .home.blog #content .item.format-quote .item-header h1 a:hover, .home.blog #content .item.format-quote .item-header cite a:hover,
	#filterlist li.current a,
	.thb-single-work-overlay .thb-single-work-slideshow #thb-slideshow_prev:hover, .thb-single-work-overlay .thb-single-work-slideshow #thb-slideshow_next:hover,
	.thb-single-work-overlay .thb-single-work-content a:hover,
	.flex-direction-nav li a:hover,
	.page-template-template-showcase-php .home-footer-container .thb-social-home a:hover span' => 'color',

	'.home #header, .woocommerce.archive #header,
	#footer-stripe,
	.single-post .thb-audio-wrapper,
	.thb-navigation ul .current,
	#page-links span,
	.page-template-template-showcase-php #thb-home-slides .thb-home-slide .thb-home-slide-overlay,
	.thb-home-slides-picture-overlay,
	.page-template-template-blog-stream-php #content .item,
	#thb-featuredimage-background .thb-featuredimage-background-overlay,
	.thb-tagcloud a, .thb-tagcloud a:hover' => 'background-color',

	'.thb-navigation ul .current,
	#page-links span,
	.thb-tagcloud a,
	#comments li.bypostauthor .comment_leftcol img' => 'border-color',

	'::-webkit-selection' => 'background-color',
	'::-moz-selection' => 'background-color',
	'::selection' => 'background-color',

	'.thb_overlay' => 'mixin-thb_overlay'
);

if( function_exists('is_woocommerce') ) {
	// WooCommerce -------------------------------------------------------------

	$thb_highlight_color['.woocommerce nav.woocommerce-pagination ul .current,
	.woocommerce #content nav.woocommerce-pagination ul .current'] = 'background-color';

	$thb_highlight_color['.woocommerce nav.woocommerce-pagination ul .current,
	.woocommerce #content nav.woocommerce-pagination ul .current'] = 'border-color';

	$thb_highlight_color['.thb_mini_cart_wrapper .widget_shopping_cart_content .product_list_widget li a:hover,
	.woocommerce .widget_shopping_cart_content .product_list_widget li a:hover,
	.thb-product-numbers'] = 'color';
}

$thb_tc->addColorSetting($thb_highlight_color, '#38a1d2', __('Highlight color', 'thb_text_domain'));
unset($thb_highlight_color);


$thb_tc->addBackgroundSettings( 'body', array(
	'background-color' => '#f2f6f7'
) );

// Home slide ------------------------------------------------------------------

$thb_tc->addSection( 'thb_home_slide', __('Home page slides', 'thb_text_domain') );
	$thb_tc->addDivider( __('Headlines', 'thb_text_domain') );
	$thb_tc->addFontsSettings( '.page-template-template-showcase-php .thb-banner h1', array(
		'font-family' => 'Source+Sans+Pro',
		'font-size' => '92',
		'line-height' => '1.1',
		'letter-spacing' => '-2px',
		'text-variant' => '900',
		'text-transform' => 'none'
	) );

	$thb_tc->addDivider( __('Paragraphs', 'thb_text_domain') );
	$thb_tc->addFontsSettings( '.page-template-template-showcase-php .thb-banner .thb-paragraph', array(
		'font-family' => 'Source+Sans+Pro',
		'font-size' => '38',
		'line-height' => '1.25',
		'letter-spacing' => '0',
		'text-variant' => '300',
		'text-transform' => 'none'
	) );

// Logo ------------------------------------------------------------------------

$thb_tc->addSection( 'thb_header', __('Header', 'thb_text_domain') );
	$thb_tc->addDivider( __('Logo', 'thb_text_domain') );

		$thb_tc->addFontsSettings('#logo a', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '34',
			'line-height'    => '1',
			'letter-spacing' => '-1',
			'text-variant'   => '900',
			'text-transform' => 'uppercase'
		));

		$thb_tc->addColorSetting(array(
			'#logo a' => 'color'
		), '#fff');

	// Menu ------------------------------------------------------------------------

	$thb_tc->addDivider( __('Menu', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.main-navigation div ul > li a', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '16',
			'line-height'    => '1',
			'letter-spacing' => '0',
			'text-variant'   => '700',
			'text-transform' => 'uppercase'
		));

		$thb_tc->addColorSetting(array(
			'#main-nav div ul > li a, #thb-cart-trigger, #nav-trigger' => 'color'
		), '#fff');

	$thb_tc->addDivider( __('Sub menu', 'thb_text_domain') );

		$thb_tc->addFontsSettings('#main-nav div ul li ul > li a', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '14',
			'line-height'    => '1',
			'letter-spacing' => '0',
			'text-variant'   => '700',
			'text-transform' => 'uppercase'
		));

		$thb_tc->addColorSetting(array(
			'#main-nav div ul li ul > li a' => 'color'
		), '#333');

	$thb_tc->addDivider( __('Mobile menu', 'thb_text_domain') );

		$media_nav = array(
			'.responsive_480 .nav-wrapper' => 'mixin-thb_media_nav'
		); 
		
		$thb_tc->addColorSetting( $media_nav, '#777', __('Background color', 'thb_text_domain') );

// -----------------------------------------------------------------------------
// Content
// -----------------------------------------------------------------------------

$thb_tc->addSection( 'thb_content', __('Main content', 'thb_text_domain') );

	$thb_tc->addDivider( __('Page title', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.pageheader h1', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '62',
		'line-height'    => '1.1',
		'letter-spacing' => '-1',
		'text-variant'   => '900',
		'text-transform' => 'uppercase'
	), '');

	$thb_tc->addDivider( __('Page subtitle', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.pageheader h2', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '28',
		'line-height'    => '1.5',
		'letter-spacing' => '1px',
		'text-variant'   => '300',
		'text-transform' => 'uppercase'
	), '');

	$thb_tc->addDivider( __('Text', 'thb_text_domain') );

	$thb_tc->addFontsSettings('body, #content .thb-text, .comment .comment_body', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '18',
		'line-height'    => '1.5',
		'letter-spacing' => '0',
		'text-variant'   => '300',
		'text-transform' => 'none'
	), '');


	$thb_tc->addDivider( __('Headings H1', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.thb-text h1, .textwidget h1, .comment_body h1', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '48',
		'line-height'    => '1.1',
		'letter-spacing' => '-2',
		'text-variant'   => '700',
		'text-transform' => 'none'
	), '');

	$thb_tc->addDivider( __('Headings H2', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.thb-text h2, .textwidget h2, .comment_body h2', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '36',
		'line-height'    => '1.1',
		'letter-spacing' => '-1',
		'text-variant'   => '700',
		'text-transform' => 'uppercase'
	), '');

	$thb_tc->addDivider( __('Headings H3', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.thb-text h3, .textwidget h3, .comment_body h3', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '30',
		'line-height'    => '1.1',
		'letter-spacing' => '-1',
		'text-variant'   => '700',
		'text-transform' => 'uppercase'
	), '');

	$thb_tc->addDivider( __('Headings H4', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.thb-text h4, .textwidget h4, .comment_body h4', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '24',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-variant'   => '700',
		'text-transform' => 'none'
	), '');

	$thb_tc->addDivider( __('Headings H5', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.thb-text h5, .textwidget h5, .comment_body h5', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '18',
		'line-height'    => '1.4',
		'letter-spacing' => '0',
		'text-variant'   => '700',
		'text-transform' => 'none'
	), '');

	$thb_tc->addDivider( __('Headings H6', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.thb-text h6, .textwidget h6, .comment_body h6', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '16',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-variant'   => '700',
		'text-transform' => 'none'
	), '');

// -----------------------------------------------------------------------------
// Blog Classic
// -----------------------------------------------------------------------------

$thb_tc->addSection( 'thb_blog', __('Blog Classic', 'thb_text_domain') );

	$thb_tc->addDivider( __('Header', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.home.blog #content .item .item-header h1, .archive #content .item .item-header h1, .page-template-template-blog-classic-php #content .item .item-header h1', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '36',
			'line-height'    => '1.1',
			'letter-spacing' => '-2',
			'text-variant'   => '900',
			'text-transform' => 'none'
		), '');

	$thb_tc->addDivider( __('Quote', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.home.blog #content .item.format-quote .item-header h1, .archive #content .item.format-quote .item-header h1, .page-template-template-blog-classic-php #content .item.format-quote .item-header h1', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '36',
			'line-height'    => '1.1',
			'letter-spacing' => '0',
			'text-variant'   => '700italic',
			'text-transform' => 'none'
		), '');

	$thb_tc->addDivider( __('Text', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.home.blog #content .item .item-content, .archive #content .item .item-content, .page-template-template-blog-classic-php #content .item .item-content', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '16',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'text-variant'   => '300',
			'text-transform' => 'none'
		), '');

	$thb_tc->addDivider( __('Meta', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.home.blog #content .item.format-quote .item-header cite, .archive #content .item.format-quote .item-header cite, .page-template-template-blog-classic-php #content .item.format-quote .item-header cite, .home.blog #content .item .item-header .item-footer, .archive #content .item .item-header .item-footer, .page-template-template-blog-classic-php #content .item .item-header .item-footer', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '14',
			'line-height'    => '1.7',
			'letter-spacing' => '0',
			'text-variant'   => 'regular',
			'text-transform' => 'uppercase'
		), '');

// -----------------------------------------------------------------------------
// Blog Grid
// -----------------------------------------------------------------------------

$thb_tc->addSection( 'thb_blog', __('Blog Grid', 'thb_text_domain') );

	$thb_tc->addDivider( __('Header', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.page-template-template-blog-stream-php #content .item .item-header h1 a', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '22',
			'line-height'    => '1.1',
			'letter-spacing' => '-1',
			'text-variant'   => '900',
			'text-transform' => 'none'
		), '');

	$thb_tc->addDivider( __('Quote', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.page-template-template-blog-stream-php #content .item.format-quote .item-wrapper .item-header h1 a', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '22',
			'line-height'    => '1.1',
			'letter-spacing' => '0',
			'text-variant'   => '700italic',
			'text-transform' => 'none'
		), '');

	$thb_tc->addDivider( __('Text', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.page-template-template-blog-stream-php #content .item .text', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '14',
			'line-height'    => '1.5',
			'letter-spacing' => '0',
			'text-variant'   => '300',
			'text-transform' => 'none'
		), '');

	$thb_tc->addDivider( __('Meta', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.page-template-template-blog-stream-php #content .item.format-quote .item-header cite, .page-template-template-blog-stream-php #content .item.format-link .linkurl, .page-template-template-blog-stream-php #content .item .item-header .pubdate', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '14',
			'line-height'    => '1.1',
			'letter-spacing' => '0',
			'text-variant'   => 'regular',
			'text-transform' => 'uppercase'
		), '');


// -----------------------------------------------------------------------------
// Portfolio
// -----------------------------------------------------------------------------

$thb_tc->addSection( 'thb_portfolio', __('Portfolio', 'thb_text_domain') );

	$thb_tc->addDivider( __('Title', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.page-template-template-portfolio-php .item .data h1', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '18',
			'line-height'    => '1.1',
			'letter-spacing' => '-1',
			'text-variant'   => '900',
			'text-transform' => 'none'
		), '');

	$thb_tc->addDivider( __('Meta', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.page-template-template-portfolio-php .item .data h2', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '14',
			'line-height'    => '1.1',
			'letter-spacing' => '0',
			'text-variant'   => 'regular',
			'text-transform' => 'uppercase'
		), '');

	$thb_tc->addDivider( __('Single work title', 'thb_text_domain') );

		$thb_tc->addFontsSettings('.thb-single-work-overlay .thb-single-work-title', array(
			'font-family'    => 'Source+Sans+Pro',
			'font-size'      => '24',
			'line-height'    => '1.1',
			'letter-spacing' => '-1',
			'text-variant'   => '900',
			'text-transform' => 'uppercase'
		), '');

// -----------------------------------------------------------------------------
// Sidebar
// -----------------------------------------------------------------------------

$thb_tc->addSection( 'thb_sidebar', __('Sidebar', 'thb_text_domain') );

	$thb_tc->addDivider( __('Widget title', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.widget .widgettitle', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '20',
		'line-height'    => '1.5',
		'letter-spacing' => '0',
		'text-variant'   => '900',
		'text-transform' => 'uppercase'
	), '');

	$thb_tc->addDivider( __('Widget item title', 'thb_text_domain') );

	$thb_tc->addFontsSettings('#thb-sidebar-main .thb-shortcode .list .item .item-title h1', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '16',
		'line-height'    => '1.1',
		'letter-spacing' => '0',
		'text-variant'   => '600',
		'text-transform' => 'none'
	), '');

	$thb_tc->addDivider( __('Widget item text', 'thb_text_domain') );

	$thb_tc->addFontsSettings('.sidebar .widget', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '16',
		'line-height'    => '1.5',
		'letter-spacing' => '0',
		'text-variant'   => '300',
		'text-transform' => 'none'
	), '');

// -----------------------------------------------------------------------------
// Footer
// -----------------------------------------------------------------------------

$thb_tc->addSection( 'thb_footer', __('Footer', 'thb_text_domain') );

// Footer sidebar --------------------------------------------------------------

	$thb_tc->addColorSetting(array(
		'#footer' => 'background-color'

	), '#2e3339', __('Footer background', 'thb_text_domain'));

	$thb_tc->addDivider( __('Widget title', 'thb_text_domain') );

	$thb_tc->addFontsSettings('#page-footer .widget .widgettitle', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '20',
		'line-height'    => '1.5',
		'letter-spacing' => '0',
		'text-variant'   => '900',
		'text-transform' => 'uppercase'
	), '');

	$thb_tc->addDivider( __('Widget item title', 'thb_text_domain') );

	$thb_tc->addFontsSettings('#page-footer .thb-shortcode .list .item .item-title h1', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '16',
		'line-height'    => '1.25',
		'letter-spacing' => '0',
		'text-variant'   => '600',
		'text-transform' => 'none'
	), '');

	$thb_tc->addDivider( __('Widget item text', 'thb_text_domain') );

	$thb_tc->addFontsSettings('#page-footer .sidebar .widget', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '16',
		'line-height'    => '1.5',
		'letter-spacing' => '0',
		'text-variant'   => '300',
		'text-transform' => 'none'
	), '');

// Footer ----------------------------------------------------------------------

	$thb_tc->addDivider( __('Bottom footer', 'thb_text_domain') );

	$thb_tc->addDivider( __('Logo', 'thb_text_domain') );

	$thb_tc->addFontsSettings('#footerlogo', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '14',
		'line-height'    => '1',
		'letter-spacing' => '0',
		'text-variant'   => '700',
		'text-transform' => 'none'
	), '');

	$thb_tc->addColorSetting(array(
		'#footerlogo, .gotop' => 'color'
	), '#ccc');

	$thb_tc->addColorSetting(array(
		'#footerlogo:hover, .gotop:hover' => 'color'
	), '#fff', __('Hover', 'thb_text_domain') );

	$thb_tc->addDivider( __('Text', 'thb_text_domain') );

	$thb_tc->addFontsSettings('#copyright', array(
		'font-family'    => 'Source+Sans+Pro',
		'font-size'      => '14',
		'line-height'    => '1',
		'letter-spacing' => '0',
		'text-variant'   => 'regular',
		'text-transform' => 'none'
	), '');

	$thb_tc->addColorSetting(array(
		'#copyright' => 'color'
	), '#999');

// Overlay ---------------------------------------------------------------------

if( !function_exists('thb_overlay') ) {
	function thb_overlay( $value=null, $selector=null ) {
		$overlay_css = '';
		$overlay_css .= '.thb-overlay {';
			$overlay_css .= 'background: ' . $value . ';' ;
			$overlay_css .= 'background: rgba(' . implode(',', thb_color_hexToRgb($value)) . ', .6);' ;
		$overlay_css .= '}';

		return $overlay_css;
	}
}

if( !function_exists('thb_media_nav') ) {
	function thb_media_nav( $value=null, $selector=null ) {
		$nav_wrapper_css = '';
		$nav_wrapper_css .= '@media screen and (max-width: 767px) {';
			$nav_wrapper_css .= $selector . '{';
			$nav_wrapper_css .= 'background: ' . $value . ';' ;
			$nav_wrapper_css .= '}';
		$nav_wrapper_css .= '}';

		return $nav_wrapper_css;
	}
}

// -----------------------------------------------------------------------------

add_action( 'customize_register', array($thb_tc, 'register') );