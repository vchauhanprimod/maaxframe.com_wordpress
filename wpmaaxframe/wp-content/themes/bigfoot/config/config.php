<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');
if( !defined('THB_CONFIG_INIT') ) { define('THB_CONFIG_INIT', true); } else { return; }

/**
 * Theme config.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Config
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2012, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * === APPEARANCE ==============================================================
 * - Scripts and styles *
 * - Image sizes
 * - Menu
 * === CORE ====================================================================
 * - Main options page and default tabs
 * - Modules
 * === SIDEBARS ================================================================
 * - Sidebars *
 * - Page templates with sidebar *
 * === OPTIONS =================================================================
 * - Options page
 * - Custom body classes *
 * === GLOBALS =================================================================
 * - Meta data
 * - RSS feed
 * - Favicon
 * - Default script
 * - Google Analytics
 * === THEME CUSTOMIZATIONS ====================================================
 * - post layout control to posts
 * - Woocommerce fix *
 */

$thb_theme = thb_theme();

/**
 * APPEARANCE
 * -----------------------------------------------------------------------------
 */

// Scripts and styles
$template_directory_uri = get_template_directory_uri();

thb_theme()->getFrontend()->addStyle($template_directory_uri . '/css/layout.css', array(
	'name' => 'thb_layout',
	// 'deps' => array('thb_reset'),
	'compress' => false
));

// Responsive
if( !function_exists('thb_html_class_filter') ) {
	function thb_html_class_filter( $classes ) {
		if( thb_get_option('enable_responsive_768') == 1 ) {
			$classes[] = 'responsive_768';
		}

		if( thb_get_option('enable_responsive_480') == 1 ) {
			$classes[] = 'responsive_480';
		}

		return $classes;
	}

	add_filter('thb_html_class', 'thb_html_class_filter');
}

// Style fixes
if( ! function_exists('thb_ie_fixes') ) {
	function thb_ie_fixes() {
		thb_ie();
	}

	// add_action( 'wp_head', 'thb_ie_fixes' );
}

$thb_theme->getFrontend()->addScript( get_template_directory_uri() . '/js/tinynav.js' );
$thb_theme->getFrontend()->addScript( get_template_directory_uri() . '/js/jquery.scrollTo-1.4.3.1-min.js' );

// The image sizes

add_image_size( 'micro', 80, 80, true );
add_image_size( 'thumbnail', 160, 160, true );
add_image_size( 'large', 1160, null, true );

add_image_size( 'large-cropped', 1160, 500, true );

add_image_size( 'thumb-260', 260, null, true );
add_image_size( 'thumb-260-cropped', 360, 175, true );
add_image_size( 'thumb-360', 360, null, true );
add_image_size( 'thumb-360-cropped', 360, 225, true );
add_image_size( 'thumb-560', 560, null, true );
add_image_size( 'thumb-560-cropped', 560, 350, true );
add_image_size( 'thumb-760', 760, null, true );
add_image_size( 'thumb-760-cropped', 760, 410, true );

// Menus

register_nav_menus(array(
	'primary' => __( 'Primary navigation', 'thb_text_domain' )
));

/**
 * CORE
 * -----------------------------------------------------------------------------
 */

// Main options page and default tabs

$thb_page = $thb_theme->getAdmin()->getMainPage();
	$thb_tab = new THB_Tab( __('General', 'thb_text_domain'), 'general' );
	$thb_page->addTab($thb_tab);

	$thb_tab = new THB_Tab( __('Logo & Images', 'thb_text_domain'), 'general_images' );
	$thb_page->addTab($thb_tab);

// Core modules

$thb_layout_page_templates = array(
	'default',
	'template-archives.php',
	'template-blog-classic.php',
	'template-blog-stream.php',
	'template-contact.php',
	'template-photogallery.php',
	'template-portfolio.php',
	'template-showcase.php'
);

$thb_theme->loadModule('core/layout', array(
	'options_logo_position' => array(
		'logo-left'   => __('Left', 'thb_text_domain'),
		'logo-right'  => __('Right', 'thb_text_domain')
	),
	'meta_options_page_boxed' => false,
	'meta_options_gutter' => false,
	'meta_options_subtitle' => array_diff($thb_layout_page_templates, array('template-showcase.php')),
	'meta_options_pageheader_disable' => array_diff($thb_layout_page_templates, array('template-showcase.php'))
));

$thb_theme->loadModule('core/sidebars', array(
	'templates' => array(
		'default',
		'single.php',
		'template-blog-stream.php',
		'template-blog-classic.php'
	)
));

$thb_theme->loadModule('core/blog', array(
	'templates' => array(
		'template-blog-stream.php',
		'template-blog-classic.php'
	)
));

$thb_theme->loadModule('core/lightbox/submodules/magnific-popup', array(
	'skin' => false
));

$thb_theme->loadModule('core/photogallery', array(
	'templates' => array(
		'template-photogallery.php'
	),
	'image_sizes' => array(
		'2' => array('thumb-560-cropped', 'thumb-560'),
		'3' => array('thumb-360-cropped', 'thumb-360'),
		'4' => array('thumb-260-cropped', 'thumb-260')
	),
	'item_thumb_rel' => 'magnificPopupGalleries'
));

$thb_theme->loadModule('core/slideshows', array(
	'submodules' => array(
		'flexslider' => array(
			'image_size' => 'large',
			'templates' => array(
				'default',
				'single.php',
				'template-archives.php',
				'template-blog-classic.php',
				'template-blog-stream.php',
				'template-contact.php',
				'template-page-full.php',
				'template-photogallery.php',
				'template-portfolio.php'
			)
		)
	)
));

$thb_theme->loadModule('core/portfolio', array(
	'templates' => array(
		'template-portfolio.php'
	),
	'grid_templates' => array(
		'template-portfolio.php'
	),
	'ajax' => array(
		'template-portfolio.php'
	),
	'work_details' => false,
	'works_navigation' => false,
	'single' => false,
	'work_slides' => true,
	'grid_image_sizes' => array(
		'2' => array('thumb-560-cropped', 'thumb-560'),
		'3' => array('thumb-360-cropped', 'thumb-360'),
		'4' => array('thumb-260-cropped', 'thumb-260')
	)
));

$thb_theme->loadModule('core/contact', array(
	'duplicable_fields' => true
));

$thb_theme->loadModule('core/seo');
$thb_theme->loadModule('core/social');
$thb_theme->loadModule('core/customfonts');
$thb_theme->loadModule('core/woocommerce', array(
	'disable_core_skin' => true
));

$thb_theme->loadModule('footerstripe', array(
	'templates' => array(
		'default',
		'single.php',
		'template-archives.php',
		'template-blog-classic.php',
		'template-blog-stream.php',
		'template-contact.php',
		'template-page-full.php',
		'template-photogallery.php',
		'template-portfolio.php'
	)
));

$thb_theme->loadModule('core/appearance');

// Theme

$thb_theme->loadModule('customization');

/**
 * SIDEBARS
 * -----------------------------------------------------------------------------
 */

// Footer sidebars

$footer_layout = thb_get_option('footer_layout');

if( !empty($footer_layout) ) {
	$columns = explode(',', $footer_layout);

	for( $i=0; $i<count($columns); $i++ ) {
		$sidebar_name = __('Footer column', 'thb_text_domain') . '#' . ($i+1);
		$thb_theme->addSidebar( $sidebar_name, 'footer-sidebar-' . $i );
	}
}

/**
 * OPTIONS
 * -----------------------------------------------------------------------------
 */

// Theme options page

/**
 * General
 */

$thb_tab = $thb_page->getTab('general');

	// General options

	$thb_container = $thb_tab->createContainer( __('General options', 'thb_text_domain'), 'general_options' );

	$thb_field = new THB_TextField('copyright');
		$thb_field->setLabel( __('Copyright text', 'thb_text_domain') );
		$thb_field->setHelp( __('The copyright text will be displayed at the bottom of the site (Note: accepts basic HTML).', 'thb_text_domain') );
	$thb_container->addField($thb_field);

	$thb_field = new THB_TextField('rss_alternate');
		$thb_field->setLabel( __('Alternate RSS feed URL', 'thb_text_domain') );
		$thb_field->setHelp( __('If you want to use a custom feed service, like Feedburner or others, enter your preferred RSS feed URL. Otherwise the default WordPress RSS feed will be used.', 'thb_text_domain') );
	$thb_container->addField($thb_field);

	$thb_field = new THB_TextareaField('analytics');
		$thb_field->setLabel( __('Google Analytics tracking code', 'thb_text_domain') );
		$thb_field->setHelp( sprintf( __('Paste your Google Analytics code here to enable statistics tracking for this site. For more info <a href="%s">read this article</a>.', 'thb_text_domain'), 'http://support.google.com/analytics/bin/answer.py?hl=en&topic=1006226&answer=1008080' ) );
	$thb_container->addField($thb_field);

/**
 * Images
 */

$thb_tab = $thb_page->getTab('general_images');

	$thb_container = $thb_tab->createContainer( '', 'general_images_options' );

		$thb_field = new THB_UploadField('main_logo');
			$thb_field->setLabel( __('Logo', 'thb_text_domain') );
			$thb_field->setHelp( __('Upload an image to be used as a logo for your site. If this field is left empty, a simple text logo will be used. Please remember to load a properly dimensioned logo.', 'thb_text_domain') );
		$thb_container->addField($thb_field);

		$thb_field = new THB_UploadField('main_logo_retina');
			$thb_field->setLabel( __('Retina Logo', 'thb_text_domain') );
			$thb_field->setHelp( __('Upload an image to be used as a retina logo for your site. For retina upload a double size image.', 'thb_text_domain') );
		$thb_container->addField($thb_field);

		$thb_field = new THB_TextField('favicon');
			$thb_field->setLabel( __('Favicon', 'thb_text_domain') );
			$thb_field->setHelp( __('Paste here the URL of your custom favicon.', 'thb_text_domain') );
		$thb_container->addField($thb_field);

		$thb_field = new THB_TextField('touch_icon_57');
			$thb_field->setLabel( __('Apple Touch Icon 57&times;57', 'thb_text_domain') );
			$thb_field->setHelp( __('Paste here the URL of your custom 57&times;57px Apple Touch Icon. <a href="http://developer.apple.com/library/ios/#documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html">What\'s an Apple Touch Icon</a>?', 'thb_text_domain') );
		$thb_container->addField($thb_field);

		$thb_field = new THB_TextField('touch_icon_72');
			$thb_field->setLabel( __('Apple Touch Icon 72&times;72', 'thb_text_domain') );
			$thb_field->setHelp( __('Paste here the URL of your custom 72&times;72px Apple Touch Icon.', 'thb_text_domain') );
		$thb_container->addField($thb_field);

		$thb_field = new THB_TextField('touch_icon_114');
			$thb_field->setLabel( __('Apple Touch Icon 114&times;114', 'thb_text_domain') );
			$thb_field->setHelp( __('Paste here the URL of your custom 114&times;114px Apple Touch Icon.', 'thb_text_domain') );
		$thb_container->addField($thb_field);

		$thb_field = new THB_TextField('touch_icon_144');
			$thb_field->setLabel( __('Apple Touch Icon 144&times;144', 'thb_text_domain') );
			$thb_field->setHelp( __('Paste here the URL of your custom 144&times;144px Apple Touch Icon.', 'thb_text_domain') );
		$thb_container->addField($thb_field);

/**
 * Responsive options
 */
if( ! function_exists('thb_responsive_options') ) {
	function thb_responsive_options() {
		$thb_tab = thb_theme()->getAdmin()->getMainPage()->getTab('layout');
		$thb_container = $thb_tab->createContainer( __('Responsive', 'thb_text_domain'), 'responsive_options' );

		$thb_field = new THB_CheckboxField( 'enable_responsive_768' );
			$thb_field->setLabel( __( sprintf('Enable above <code>%s</code>', '768px') , 'thb_text_domain') );
			$thb_field->setHelp( __('Tick if you want to enable the responsive layout feature above 768px, eg. tablets.', 'thb_text_domain') );
		$thb_container->addField($thb_field);

		$thb_field = new THB_CheckboxField( 'enable_responsive_480' );
			$thb_field->setLabel( __( sprintf('Enable below <code>%s</code>', '768px') , 'thb_text_domain') );
			$thb_field->setHelp( __('Tick if you want to enable the responsive layout feature below 768px, eg. Mobile devices.', 'thb_text_domain') );
		$thb_container->addField($thb_field);
	}

	add_action( 'after_setup_theme', 'thb_responsive_options' );
}

if( !function_exists('custom_body_classes') ) {
	function custom_body_classes( $classes ) {
		$classes[] = thb_get_option('logo_position');
		return $classes;
	}
}
add_filter( 'body_class', 'custom_body_classes' );

/**
 * GLOBALS
 * -----------------------------------------------------------------------------
 */

// Theme meta data
if( !function_exists('thb_theme_meta') ) {
	function thb_theme_meta() {
		thb_meta('viewport', 'width=device-width, initial-scale=1.0, maximum-scale=1.0');
	}
}
add_action( 'thb_head_meta', 'thb_theme_meta' );

// RSS feed

if( !function_exists('thb_feed') ) {
	remove_action( 'wp_head', 'feed_links', 2 );

	function thb_feed() {
		$feed = get_bloginfo('rss2_url');
		$comments_feed = get_bloginfo('comments_rss2_url');
		if( thb_get_option('rss_alternate') != '' ) {
			$feed = thb_get_option('rss_alternate');
		}

		thb_link( 'alternate', $feed, 'application/rss+xml', array(), get_bloginfo('name') . ' ' . __('RSS Feed', 'thb_text_domain') );
		thb_link( 'alternate', $comments_feed, 'application/rss+xml', array(), get_bloginfo('name') . ' ' . __('Comments Feed', 'thb_text_domain') );
		thb_link( 'pingback', get_bloginfo('pingback_url') );
	}

	add_action('wp_head', 'thb_feed');
}

// Favicon

if( !function_exists('thb_icons') ) {
	function thb_icons() {
		$favicon = thb_get_option('favicon');
		$touch_icon_57 = thb_get_option('touch_icon_57');
		$touch_icon_72 = thb_get_option('touch_icon_72');
		$touch_icon_114 = thb_get_option('touch_icon_114');
		$touch_icon_144 = thb_get_option('touch_icon_144');

		if( !empty($favicon) ) {
			thb_link('Shortcut Icon', $favicon, 'image/x-icon');
		}

		if( !empty($touch_icon_57) ) {
			thb_link('apple-touch-icon', $touch_icon_57, null, array('sizes' => '57x57'));
		}

		if( !empty($touch_icon_72) ) {
			thb_link('apple-touch-icon', $touch_icon_72, null, array('sizes' => '72x72'));
		}

		if( !empty($touch_icon_114) ) {
			thb_link('apple-touch-icon', $touch_icon_114, null, array('sizes' => '114x114'));
		}

		if( !empty($touch_icon_144) ) {
			thb_link('apple-touch-icon', $touch_icon_144, null, array('sizes' => '144x144'));
		}
	}
}
add_action('wp_head', 'thb_icons');

// Default script

$thb_theme->getFrontend()->addScript(get_template_directory_uri() . '/js/theme.js');

// Google Analytics

if( !function_exists('thb_google_analytics') ) {
	function thb_google_analytics(  ) {
		$analytics = stripslashes( thb_get_option('analytics') );
		if( !empty($analytics) ) {
			echo $analytics;
		}
	}
}
add_action('wp_footer', 'thb_google_analytics');

/**
 * Theme customizations
 * -----------------------------------------------------------------------------
 */

$thb_page = $thb_theme->getAdmin()->getMainPage();
	$thb_tab = $thb_page->getTab('layout');
$thb_container = $thb_tab->getContainer('layout_options_footer');

		$sep = '&nbsp;&nbsp;&nbsp;';

		$thb_field = new THB_SelectField( 'footer_layout' );
		$thb_field->setLabel( __('Layout', 'thb_text_domain') );

		$thb_field->addOptionsGroup( __('None', 'thb_text_domain'), array(
			'0' => "-",
		) );

		$thb_field->addOptionsGroup( __('One column', 'thb_text_domain'), array(
			'full-width' => __('Full width', 'thb_text_domain')
		) );

		$thb_field->addOptionsGroup( __('Two columns', 'thb_text_domain'), array(
			'one-half,one-half' => "1/2 $sep 1/2",
			'one-third,two-thirds' => "1/3 $sep 2/3",
			'two-thirds,one-third' => "2/3 $sep 1/3",
			'one-fourth,three-fourths' => "1/4 $sep 3/4",
			'three-fourths,one-fourth' => "3/4 $sep 1/4"
		) );
		$thb_field->addOptionsGroup( __('Three columns', 'thb_text_domain'), array(
			'one-third,one-third,one-third' => "1/3 $sep 1/3 $sep 1/3",
			'one-fourth,one-half,one-fourth' => "1/4 $sep 1/2 $sep 1/4",
			'one-half,one-fourth,one-fourth' => "1/2 $sep 1/4 $sep 1/4",
			'one-fourth,one-fourth,one-half' => "1/4 $sep 1/4 $sep 1/2"
		) );
		$thb_field->addOptionsGroup( __('Four columns', 'thb_text_domain'), array(
			'one-fourth,one-fourth,one-fourth,one-fourth' => "1/4 $sep 1/4 $sep 1/4 $sep 1/4",
		) );

		$thb_field->setHelp( __('Select the columns layout for the footer area. Selecting none will disable the footer area entirely.', 'thb_text_domain') );

		$thb_container->addField($thb_field);

// Add page layout options
// -----------------------------------------------------------------------------

$page_templates = array(
	'default',
	'template-archives.php',
	'template-contact.php',
	'template-blog-stream.php',
	'template-blog-classic.php',
	'template-photogallery.php',
	'template-portfolio.php'
);

$single_post = array(
	'single.php'
);

$page_layout_templates = array_merge( $page_templates, $single_post );

$thb_pages = $thb_theme->getPostType('page');

// Pages options ---------------------------------------------------------------

if( ! function_exists('thb_theme_layout_options') ) {
	function thb_theme_layout_options() {
		global $page_templates, $single_post, $portfolio, $masonry, $page_layout_templates;

		$thb_container = new THB_MetaboxFieldsContainer( '', 'extra_container' );

			$thb_field = new THB_SelectField( 'pageheader_layout' );
			$thb_field->setLabel( __('Page header alignment', 'thb_text_domain') );
				$thb_field->setOptions(array(
					'left'   => __('Left', 'thb_text_domain'),
					'center' => __('Center', 'thb_text_domain'),
					'right'  => __('Right', 'thb_text_domain')
				));
			$thb_container->addField($thb_field);

			$thb_field = new THB_SelectField( 'pageheader_height' );
			$thb_field->setLabel( __('Page header height', 'thb_text_domain') );
				$thb_field->setOptions(array(
					'pageheader-big'     => __('Large', 'thb_text_domain'),
					'pageheader-compact' => __('Compact', 'thb_text_domain')
				));
			$thb_container->addField($thb_field);

			$thb_field = new THB_ColorField( 'background_color' );
			$thb_field->setLabel( __('Background color', 'thb_text_domain') );
			$thb_container->addField($thb_field);

			$thb_field = new THB_NumberField( 'background_opacity' );
			$thb_field->setLabel( __('Background opacity', 'thb_text_domain') );
			$thb_field->setMin('0');
			$thb_field->setMax('1');
			$thb_field->setStep('0.05');
			$thb_field->setDefault('0.85');
			$thb_container->addField($thb_field);

		foreach( $page_layout_templates as $template ) {
			if( thb_is_admin_template($template) ) {
				$post_type = thb_theme()->getPostType( thb_get_post_type_from_template($template) );
				$post_type->getMetabox('layout')->addContainer($thb_container);
			}
		}
	}

	add_action('after_setup_theme', 'thb_theme_layout_options');
}

if( !function_exists('thb_pageheader_alignment') ) {
	function thb_pageheader_alignment( $classes ) {
		$id = thb_get_page_ID();
		$classes[] = 'pageheader-layout-' . thb_get_post_meta($id, 'pageheader_layout');

		return $classes;
	}

	add_action('body_class', 'thb_pageheader_alignment');
}

if( !function_exists('thb_pageheader_height') ) {
	function thb_pageheader_height( $classes ) {
		$id = thb_get_page_ID();
		$classes[] = thb_get_post_meta($id, 'pageheader_height');

		return $classes;
	}

	add_action('body_class', 'thb_pageheader_height');
}

if( !function_exists('thb_portfolio_cols') ) {
	function thb_portfolio_cols( $classes ) {
		$masonry = array(
			'template-portfolio.php',
			'template-photogallery.php'
		);

		if( ! thb_is_page_template( $masonry ) ) {
			return $classes;
		}

		$id = thb_get_page_ID();
		$classes[] = 'masonry_' . thb_get_post_meta($id, 'portfolio_columns') . 'cols';

		return $classes;
	}

	add_action('body_class', 'thb_portfolio_cols');
}

if( !function_exists('thb_page_header_disabled') ) {
	function thb_page_header_disabled( $classes ) {
		$id = thb_get_page_ID();
		if( thb_get_post_meta($id, 'pageheader_disable') ) {
			$classes[] = 'page-header-disabled';
		}

		return $classes;
	}

	add_action('body_class', 'thb_page_header_disabled');
}

if( !function_exists('thb_single_post_style') ) {
	function thb_single_post_style() {
		if( ! thb_is_page_template('template-blog-classic.php') ) {
			return;
		}

		$id = get_the_ID();
		$background_color = thb_get_post_meta($id, 'background_color');
		?>

		<style>
			#post-<?php echo $id; ?> .item-header {
				background-color: <?php echo $background_color; ?>;
			}
		</style>
		<?php
	}

	// add_action('thb_post_before', 'thb_single_post_style');
}

/**
 * Portfolio
 * -----------------------------------------------------------------------------
 */
thb_theme()->getFrontend()->addScript(get_template_directory_uri() . '/js/jquery.cycle2.min.js', array(
	'template-portfolio.php'
));

if( ! function_exists('thb_single_work_overlay') ) {
	function thb_single_work_overlay() {
		if( thb_is_page_template('template-portfolio.php') ) {
			thb_get_template_part('part-single-work-overlay');
		}
	}

	add_action('thb_footer', 'thb_single_work_overlay');
}

if( ! function_exists('thb_get_single_work_slideshow') ) {
	function thb_get_single_work_slideshow( $id ) {
		$thb_slideshow = '';
		ob_start();
		thb_get_template_part('part-single-work-overlay-slideshow', array('id' => $id));
		$thb_slideshow = ob_get_contents();
		ob_end_clean();

		return $thb_slideshow;
	}
}

if( ! function_exists('thb_get_single_work') ) {
	function thb_get_single_work() {
		$id = $_POST['id'];
		$post = get_post($id);
		$return = array();

		if( $post ) {
			$return = array(
				'id' => $id,
				'title' => apply_filters('the_title', $post->post_title),
				'content' => apply_filters('the_content', $post->post_content),
				'slideshow' => thb_get_single_work_slideshow($id)
			);
		}

		echo json_encode($return);
		die();
	}
}

add_action('wp_ajax_nopriv_thb_get_single_work', 'thb_get_single_work');
add_action('wp_ajax_thb_get_single_work', 'thb_get_single_work');

/**
 * Page header featured image
 * -----------------------------------------------------------------------------
 */
if( ! function_exists('thb_featuredimagebackground') ) {
	function thb_featuredimagebackground() {
		$image_size = 'large';

		if( function_exists( 'is_woocommerce' ) && is_shop() ) {
			$image = thb_get_option('woocommerce_shop_pageheader_background_image');

			thb_get_template_part('partial-featuredimage-background', array(
				'featured_image' => thb_image_get_size($image['id'], $image_size),
				'bg_opacity' => thb_get_option('woocommerce_shop_pageheader_background_opacity')
			));
		}
		elseif( function_exists( 'is_woocommerce' ) && is_product_category() ) {
			global $wp_query;
		    $cat = $wp_query->get_queried_object();

			$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );

			thb_get_template_part('partial-featuredimage-background', array(
				'featured_image' => thb_image_get_size($thumbnail_id, $image_size),
				'bg_opacity' => thb_get_option('woocommerce_shop_pageheader_background_opacity')
			));
		}
		else {
			$page_id = thb_get_page_ID();

			$is_dynamic_home = $page_id == 0 && is_front_page() && get_option('show_on_front') == 'posts';
			$image = '';

			$templates = array(
				'default',
				'single.php',
				'single-product.php',
				'single-works.php',
				'template-archives.php',
				'template-blog-classic.php',
				'template-blog-stream.php',
				'template-portfolio.php',
				'template-contact.php',
				'template-photogallery.php'
			);

			if( $page_id !== 0 && !is_singular('works') && !is_attachment() ) {
				$image = thb_get_featured_image($page_id, $image_size);
			}

			if( thb_check_page_template($page_id, $templates) || thb_is_archive() || is_attachment() ) {
				thb_get_template_part('partial-featuredimage-background', array(
					'featured_image' => $image,
					'bg_opacity' => thb_get_post_meta(thb_get_page_ID(), 'background_opacity')
				));
			}
		}
	}

	add_action( 'thb_featuredimagebackground', 'thb_featuredimagebackground' );
}

/**
 * Home page
 * -----------------------------------------------------------------------------
 */
if( ! function_exists('thb_home_add_config_metabox') ) {
	function thb_home_add_config_metabox() {
		 $thb_container = thb_theme()->getPostType('page')->getMetabox('layout')->createContainer(__('Twitter', 'thb_text_domain'), 'home-config-twitter');

			$thb_field = new THB_CheckboxField( 'enable_twitter_home' );
				$thb_field->setLabel( __('Enable', 'thb_text_domain') );
			$thb_container->addField($thb_field);

			$thb_field = new THB_TextField( 'twitter_home_username' );
				$thb_field->setLabel( __('Screen name', 'thb_text_domain') );
			$thb_container->addField($thb_field);

			$thb_field = new THB_NumberField( 'twitter_home_count' );
				$thb_field->setLabel( __('How many tweets', 'thb_text_domain') );
			$thb_container->addField($thb_field);

		$thb_container = thb_theme()->getPostType('page')->getMetabox('layout')->createContainer(__('Social links', 'thb_text_domain'), 'home-config-social');

			$thb_field = new THB_CheckboxField( 'enable_social_home' );
				$thb_field->setLabel( __('Enable social', 'thb_text_domain') );
			$thb_container->addField($thb_field);

			$thb_field = new THB_TextField( 'social_home_services' );
				$thb_field->setLabel( __('Services list', 'thb_text_domain') );
				$thb_field->setHelp( __('Comma separated, order matters. Possible values: twitter, facebook, googleplus, flickr, youtube,	vimeo, pinterest, dribbble,	forrst.', 'thb_text_domain') );
			$thb_container->addField($thb_field);
	}

	if( thb_is_admin_template('template-showcase.php') ) {
		add_action( 'after_setup_theme', 'thb_home_add_config_metabox' );
	}
}

if( !class_exists('THB_HomePageSlideField') ) {
	class THB_HomePageSlideField extends THB_Field {

		/**
		 * The field subkeys.
		 *
		 * @var array
		 **/
		protected $_subKeys = array('post_id', 'id', 'url', 'big_text', 'small_text', 'btn_url', 'btn_text', 'bg_color', 'bg_opacity', 'class');

		/**
		 * Constructor
		 *
		 * @param string $name The field name.
		 **/
		public function __construct( $name )
		{
			parent::__construct($name, THB_TEMPLATE_DIR . '/config/home_page/home-page-slide-field');
		}

	}
}

if( ! function_exists('thb_add_home_page_slides_metabox') ) {
	function thb_add_home_page_slides_metabox() {
		$thb_metabox = new THB_Metabox( __('Slides', 'thb_text_domain'), 'slides' );

			$thb_container = $thb_metabox->createDuplicableContainer( '', 'slides_container' );
				$thb_container->setSortable();
				$thb_container->setIntroText( __("<strong>How to use</strong><br><br>Insert the post type ID in order to populate the slide with the post's featured image, excerpt, title and a button with a link to the single post page.<br>Alternatively, you can insert custom texts and button.<br><br>Please note that if you're using a post type ID, custom texts and background image will override the entry's content.", 'thb_text_domain') );
				$thb_container->addControl( __('Add', 'thb_text_domain'), '' );

				$thb_upload = new THB_HomePageSlideField( 'home_page_slide' );
				$thb_upload->setLabel( __('Home page slide', 'thb_text_domain') );
				$thb_container->setField($thb_upload);

		thb_theme()->getPostType('page')->addMetabox($thb_metabox, array(
			'template-showcase.php'
		));
	}

	add_action( 'after_setup_theme', 'thb_add_home_page_slides_metabox' );
}

if( ! function_exists('thb_get_home_slides') ) {
	function thb_get_home_slides() {
		$home_slides_raw = thb_duplicable_get('home_page_slide', thb_get_page_ID());
		$home_slides = array();
		$image_size = 'large';

		$i=0;
		foreach( $home_slides_raw as $slide ) {
			$slide = $slide['value'];

			$home_slide = (object) array();
			$home_slide->index = $i;
			$home_slide->big_text = '';
			$home_slide->small_text = '';
			$home_slide->btn_url = '';
			$home_slide->btn_text = '';
			$home_slide->bg_picture = '';
			$home_slide->bg_color = '';
			$home_slide->bg_opacity = '0';
			$home_slide->class = '';

			$home_slide->post_id = $slide['post_id'];

			if( !empty($home_slide->post_id) ) {
				$post = get_post($home_slide->post_id);

				if( $post ) {
					$home_slide->big_text = $post->post_title;
					$home_slide->small_text = $post->post_excerpt;
					$home_slide->btn_url = get_permalink($home_slide->post_id);
					$home_slide->btn_text = __('More', 'thb_text_domain');
					$home_slide->bg_picture = thb_get_featured_image($home_slide->post_id, $image_size);
				}
			}

			if( !empty( $slide['big_text'] ) ) {
				$home_slide->big_text = $slide['big_text'];
			}

			if( !empty( $slide['small_text'] ) ) {
				$home_slide->small_text = $slide['small_text'];
			}

			if( !empty( $slide['btn_url'] ) ) {
				$home_slide->btn_url = $slide['btn_url'];
			}

			if( !empty( $slide['btn_text'] ) ) {
				$home_slide->btn_text = $slide['btn_text'];
			}

			if( !empty( $slide['id'] ) ) {
				$home_slide->bg_picture = thb_image_get_size($slide['id'], $image_size);
			}

			$home_slide->bg_color = $slide['bg_color'];
			$home_slide->bg_opacity = $slide['bg_opacity'];

			$is_valid_solid_background = $home_slide->bg_color != '';

			if( $home_slide->bg_picture == '' ) {
				$home_slide->bg_opacity = 1;
			}

			if( !empty( $slide['class'] ) ) {
				$home_slide->class = $slide['class'];
			}

			$home_slides[] = $home_slide;
			$i++;
		}

		return $home_slides;
	}
}

/**
 * Admin tweaks
 */
$thb_theme->getAdmin()->addStyle(THB_TEMPLATE_URL . '/config/home_page/admin.css');
$thb_theme->getAdmin()->addScript(THB_TEMPLATE_URL . '/config/home_page/admin.js');
$thb_theme->getFrontend()->addScript(THB_TEMPLATE_URL . '/config/home_page/hammer.min.js', array(
	'templates' => array('template-showcase.php')
));
$thb_theme->getFrontend()->addScript(THB_TEMPLATE_URL . '/config/home_page/jquery.hammer.min.js', array(
	'templates' => array('template-showcase.php')
));

if( ! function_exists('thb_template_home_admin_body_class') ) {
	function thb_template_home_admin_body_class( $classes ) {
		if( thb_is_admin_template("template-showcase.php") ) {
			$classes .= " thb-no-editor";
		}

		return $classes;
	}

	add_filter( 'admin_body_class', 'thb_template_home_admin_body_class' );
}

$thb_theme->getFrontend()->addScript(THB_FRONTEND_JS_URL . '/jquery.isotope.min.js', array(
	'template-blog-stream.php'
));

$thb_theme->getFrontend()->addStyle(THB_FRONTEND_CSS_URL . '/isotope.css', array(
	'template-blog-stream.php'
));

/**
 * Register plugins
 */
require_once 'register-plugins.php';