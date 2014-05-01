<?php
// Scripts and styles


if( !function_exists('thb_woocommerce_script_ands_styles') ) {
	function thb_woocommerce_script_ands_styles() {
		$template_directory_uri = get_template_directory_uri();
		$thb_frontend = thb_theme()->getFrontend();

		$thb_frontend->addScript( $template_directory_uri . '/woocommerce/js/footable.js' );
		$thb_frontend->addStyle($template_directory_uri . '/woocommerce/css/footable-0.1.css' );

		$thb_frontend->addStyle($template_directory_uri . '/woocommerce/css/woocommerce.css', array(
			'deps' => array(),
			'name' => 'thb_woocommerce'
		));
	}

	thb_woocommerce_script_ands_styles();
}

if( !function_exists('thb_woocommerce_image_size_shop_single') ) {
	function thb_woocommerce_image_size_shop_single($size) {
		return array(
			'width' => 560,
			'height' => null,
			'crop' => false
		);
	}
}

if( !function_exists('thb_woocommerce_image_size_shop_catalog') ) {
	function thb_woocommerce_image_size_shop_catalog($size) {
		return array(
			'width' => 360,
			'height' => 360,
			'crop' => true
		);
	}
}

if( !function_exists('thb_woocommerce_image_size_shop_thumbnail') ) {
	function thb_woocommerce_image_size_shop_thumbnail($size) {
		return array(
			'width' => 95,
			'height' => 95,
			'crop' => true
		);
	}
}

if( !function_exists('thb_woocommerce_image_sizes') ) {
	function thb_woocommerce_image_sizes(  ) {
		add_filter('woocommerce_get_image_size_shop_thumbnail', 'thb_woocommerce_image_size_shop_thumbnail', 999);
		add_filter('woocommerce_get_image_size_shop_catalog', 'thb_woocommerce_image_size_shop_catalog', 999);
		add_filter('woocommerce_get_image_size_shop_single', 'thb_woocommerce_image_size_shop_single', 999);
	}

	add_action('after_setup_theme', 'thb_woocommerce_image_sizes');
}

/**
 * Layout
 * -----------------------------------------------------------------------------
 */

// Footable
// -----------------------------------------------------------------------------

if( !function_exists('thb_cart_footable') ) {
	function thb_cart_footable() {
		if( !is_cart() ) {
			return;
		}
	?>

		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				jQuery('.shop_table.cart').footable();
			}, false);
		</script>
	<?php
	}

	add_action('thb_head', 'thb_cart_footable', 25);
}

// Minicart
// -----------------------------------------------------------------------------

if( !function_exists('thb_check_minicart_display') ) {
	function thb_check_minicart_display() {
		if( thb_get_option('enable_shop_cart_allpages') == 1 ) {
			$enable_shop_cart = true;
		}
		else {
			$enable_shop_cart = is_woocommerce() || is_cart() || is_checkout();
		}

		return $enable_shop_cart;
	}
}

if( !function_exists('thb_check_minicart_display_classes') ) {
	function thb_check_minicart_display_classes( $classes ) {
		if( thb_check_minicart_display() == true ) {
			$classes[] = 'thb-w-mini-cart';
		}

		return $classes;
	}

	add_filter('body_class', 'thb_check_minicart_display_classes');
}

if( !function_exists('thb_minicart_script') ) {
	function thb_minicart_script() {

		if( thb_check_minicart_display() == false ) {
			return;
		}

		?>

		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				if( !jQuery('body').hasClass('thb-mobile') ) {
					var thb_cart_over = false;

					jQuery(document)
						.on("mouseenter", ".thb-mini-cart-icon", function() {
							if( thb_cart_over === false ) {
								thb_cart_over = true;

								jQuery(this).find('.thb_mini_cart_wrapper').css('display','block');
								setTimeout(function() {
									jQuery('body').addClass("thb-mini-cart-active");
								}, 1);
							}
						})
						.on("mouseleave", ".thb-mini-cart-icon", function() {
							if( thb_cart_over === true ) {
								jQuery.thb.transition(jQuery(this).find('.thb_mini_cart_wrapper'), function( el ) {
									thb_cart_over = false;
									jQuery(el).css('display', 'none');
								});

								jQuery('body').removeClass("thb-mini-cart-active");
							}
						});
				}
			}, false);
		</script>
		<?php
	}

	add_action('thb_head', 'thb_minicart_script', 25);
}

if( !function_exists('thb_woocommerce_theme_before_content') ) {
	function thb_woocommerce_theme_before_content() { ?>
	<?php if( !is_product() ) { ?>
		<header class="pageheader">
			<h1><?php woocommerce_page_title(); ?></h1>
			<h2><?php woocommerce_result_count(); ?></h2>
		</header><!-- /.pageheader -->
	<?php } ?>
	<?php if( is_shop() ) { ?>
		<?php woocommerce_catalog_ordering(); ?>
	<?php } ?>
		<?php get_template_part('partial-header-closure'); ?>
			<?php thb_page_before(); ?>
				<section id="content">
	<?php
	}
}

if( !function_exists('thb_woocommerce_theme_after_content') ) {
	function thb_woocommerce_theme_after_content() { ?>
			</section>
		<?php thb_page_after();
	}
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
add_filter( 'woocommerce_before_main_content', 'thb_woocommerce_theme_before_content', 10 );
add_filter( 'woocommerce_after_main_content', 'thb_woocommerce_theme_after_content', 20 );

// Single product
// -----------------------------------------------------------------------------

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 70 );

if( !function_exists('thb_single_product_summary') ) {
	function thb_single_product_summary() {
		?>
		<div class="thb-product-header">
			<?php
				woocommerce_template_single_title();
				woocommerce_template_loop_rating();
				woocommerce_template_single_price();
			?>
		</div>
		<div class="thb-product-excerpt thb-text">
			<?php
				woocommerce_template_single_excerpt();
			?>
		</div>
		<?php
	}

	add_action('woocommerce_single_product_summary', 'thb_single_product_summary');
}

// Share product
// -----------------------------------------------------------------------------

if( !function_exists('thb_product_share') ) {
	function thb_product_share() {
		get_template_part('woocommerce/single-product/share.php');
	}
}
add_action('woocommerce_share', 'thb_product_share');

// Upsell products
// -----------------------------------------------------------------------------

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'thb_upsell_display', 15);

if( !function_exists('thb_upsell_display') ) {
	function thb_upsell_display( $posts_per_page = '-1', $columns = 4, $orderby = 'rand' ) {
		woocommerce_get_template( 'single-product/up-sells.php', array(
			'posts_per_page'  => $posts_per_page,
			'orderby'    => $orderby,
			'columns'    => $columns
		));
	}
}


// Product wrapping
// -----------------------------------------------------------------------------

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

if( !function_exists('thb_loop_product_start') ) {
	function thb_loop_product_start() {
		global $product;

		echo "<div class='thb-product-image-wrapper item-thumb'>";
			echo "<a href='". get_permalink() ."'>";
				woocommerce_show_product_loop_sale_flash();
				if ( ! $product->is_in_stock() ) {
					echo "<span class='thb-out-of-stock'>";
						_e('Out of stock', 'woocommerce');
					echo "</span>";
				}
				echo "<span class='thb-overlay'></span>";
				woocommerce_template_loop_product_thumbnail();
			echo "</a>";
		echo "</div>";
		echo "<div class='thb-product-description'>";
	}

	add_action( 'woocommerce_before_shop_loop_item', 'thb_loop_product_start', 999 );
}
if( !function_exists('thb_loop_product_end') ) {
	function thb_loop_product_end() {
			global $post, $product;
			$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
			echo $product->get_categories( ', ', '<span class="posted_in">' . _n( '', '', $size, 'woocommerce' ) . ' ', '</span>' );

			woocommerce_template_loop_rating();

			echo '<div class="thb-add-to-cart-wrapper">';
				woocommerce_template_loop_price();
				woocommerce_template_loop_add_to_cart();
			echo "</div>";
		echo "</div>";
	}

	add_action( 'woocommerce_after_shop_loop_item', 'thb_loop_product_end', 999 );
}

// Checkout

// remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
// remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

// if( !function_exists('thb_woocommerce_before_checkout_form') ) {
// 	function thb_woocommerce_before_checkout_form() {
// 		echo "<div class='col2-set' id='thb-login-register'>";
// 			echo "<h3>" . __( 'Checkout method', 'thb_text_domain' ) . "</h3>";
// 			echo "<div class='col-1'>";
// 				woocommerce_checkout_login_form();
// 			echo "</div>";
// 	}

// 	add_action('woocommerce_before_checkout_form', 'thb_woocommerce_before_checkout_form', 999);
// }

/**
 * Options
 */

// Change the shop loop items

// add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 999 );

/**
 * Cart
 * -----------------------------------------------------------------------------
 */

if( !function_exists('thb_mini_cart_fragments') ) {
	function thb_mini_cart_fragments( $fragments ) {
		global $woocommerce;

		$cart_class = '';
		if( sizeof($woocommerce->cart->cart_contents) > 0 ) {
			$cart_class = 'minicart-full';
		}

		$fragments['.thb-product-numbers'] = "<span class='thb-product-numbers " . $cart_class . "'>" . $woocommerce->cart->cart_contents_count . "</span>";

		return $fragments;
	}

	add_filter('add_to_cart_fragments', 'thb_mini_cart_fragments');
}

if( !function_exists('thb_woo_cart') ) {
	function thb_woo_cart() {
		global $woocommerce, $cart_class;

		if( thb_check_minicart_display() == false ) {
			return;
		}

		echo "<div class='thb-mini-cart-icon ". $cart_class ."'>";
			echo "<span class='thb-product-numbers'>" . $woocommerce->cart->cart_contents_count . "</span>";
			echo "<a href='" . $woocommerce->cart->get_cart_url() . "' id='thb-cart-trigger'>a</a>";
			echo "<div class='thb_mini_cart_wrapper'>";
				echo "<div class='widget_shopping_cart_content'>";
					woocommerce_mini_cart();
				echo "</div>";
			echo "</div>";
		echo "</div>";
	}

	add_action( 'thb_nav_after', 'thb_woo_cart' );
}

if( !function_exists('thb_woocommerce_extra_options') ) {
	function thb_woocommerce_extra_options() {
		$thb_theme = thb_theme();

		$thb_page = $thb_theme->getAdmin()->getMainPage();

		$thb_tab = $thb_page->getTab( 'woocommerce' );
			$thb_container = $thb_tab->createContainer( __('Shop general options', 'thb_text_domain'), 'woocommerce_general_options' );

			$thb_field = new THB_YesNoField( 'enable_shop_cart_allpages' );
				$thb_field->setLabel( __('Enable shop cart on all pages', 'thb_text_domain') );
				$thb_field->setHelp( __('Choose if you want to enable the shop cart on header on all pages or only for "Shop" related pages.', 'thb_text_domain') );
			$thb_container->addField($thb_field);

		$thb_container = $thb_tab->getContainer( 'woocommerce_shop_options' );

			$thb_field = new THB_SelectField('woocommerce_shop_pageheader_layout');
			$thb_field->setLabel( __('Page header layout', 'thb_text_domain') );
			$thb_field->setOptions(array(
				'left'  => __('Left', 'thb_text_domain'),
				'center'  => __('Center', 'thb_text_domain'),
				'right' => __('Right', 'thb_text_domain')
			));
			$thb_container->addField($thb_field);

			$thb_field = new THB_SelectField( 'woocommerce_shop_pageheader_height' );
			$thb_field->setLabel( __('Page header height', 'thb_text_domain') );
				$thb_field->setOptions(array(
					'pageheader-big'     => __('Large', 'thb_text_domain'),
					'pageheader-compact' => __('Compact', 'thb_text_domain')
				));
			$thb_field->setHelp( __('This setting will affect also the shop\'s archive and taxonomy pages.', 'thb_text_domain') );
			$thb_container->addField($thb_field);

			$thb_field = new THB_UploadField('woocommerce_shop_pageheader_background_image');
				$thb_field->setLabel( __('Page header background image', 'thb_text_domain') );
			$thb_container->addField($thb_field);

			$thb_field = new THB_NumberField('woocommerce_shop_pageheader_background_opacity');
				$thb_field->setMin(0);
				$thb_field->setMax(1);
				$thb_field->setStep(0.05);
				$thb_field->setLabel( __('Page header background opacity', 'thb_text_domain') );
			$thb_container->addField($thb_field);
	}

	add_action('after_setup_theme', 'thb_woocommerce_extra_options');
}

if( ! function_exists('thb_woo_pageheader_body_classes') ) {
	function thb_woo_pageheader_body_classes( $classes ) {
		if( is_shop() ) {
			$classes[] = 'pageheader-layout-' . thb_get_option('woocommerce_shop_pageheader_layout');
		}

		if( is_woocommerce() ) {
			$classes[] = thb_get_option('woocommerce_shop_pageheader_height');
		}

		return $classes;
	}

	add_filter('body_class', 'thb_woo_pageheader_body_classes');
}

/**
 * Check demo store mode
 * -----------------------------------------------------------------------------
 */

if( !function_exists('thb_woo_demostore_mode') ) {
	function thb_woo_demostore_mode( $classes ) {
		$demo_mode = get_option('woocommerce_demo_store');

		if( $demo_mode == 'yes' ) {
			$classes[] = 'thb-demostore-mode';
		}

		return $classes;
	}

	add_filter('body_class', 'thb_woo_demostore_mode');
}

/**
 * Mobile single product header
 * -----------------------------------------------------------------------------
 */

if( !function_exists('thb_single_product_mobile_header') ) {
	function thb_single_product_mobile_header(  ) {
		echo "<div class='thb_product_mobile_header'>";
				woocommerce_template_single_title();
				woocommerce_template_loop_rating();
				woocommerce_template_single_price();
		echo "</div>";
	}

	add_action('woocommerce_before_single_product','thb_single_product_mobile_header');
}

/**
 * Check cart content
 * -----------------------------------------------------------------------------
 */

if( ! function_exists('thb_woo_cart_content') ) {
	function thb_woo_cart_content( $classes ) {
		global $woocommerce;

		if( is_cart() && sizeof( $woocommerce->cart->get_cart() ) == 0 ) {
			$classes[] = 'thb-woocommerce-cartempty';
		}

		return $classes;
	}

	add_filter('body_class', 'thb_woo_cart_content');
}

if( !function_exists('thb_woo_scripts') ) {
	function thb_woo_scripts() {
		?>
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				jQuery('.product_list_widget > li > a > img').each(function() {
					jQuery(this).parent().before(this);
					jQuery(this).wrap('<div class="thb_list_widget_img" />');
				});

				jQuery('.product_list_widget > li > a').each(function() {
					if (jQuery.trim(jQuery(this).text()).length > 30 ) { jQuery(this).text(jQuery.trim(jQuery(this).text()).substr(0, 30) + "..."); }
				});

				jQuery('.product_list_widget > li > .thb_list_widget_img').each(function() {
					jQuery(this).parent().children('a').prepend(this);
				});
			}, false);
		</script>
		<?php
	}

	add_action('thb_footer', 'thb_woo_scripts');
}