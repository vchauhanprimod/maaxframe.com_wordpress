<?php
/**
 * Show options for ordering
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce, $wp_query, $wp;

if ( 1 == $wp_query->found_posts || ! woocommerce_products_will_display() )
	return;
?>
<form class="thb-shop-ordering" method="get">
	<ul>
		<?php
			$catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
				'menu_order' => __( 'Default sorting', 'woocommerce' ),
				'popularity' => __( 'Popularity', 'woocommerce' ),
				'rating'     => __( 'Rating', 'woocommerce' ),
				'date'       => __( 'Newness', 'woocommerce' ),
				'price'      => __( 'Price', 'woocommerce' ),
				//'price-desc' => __( 'Price: high to low', 'woocommerce' )
			) );

			if ( get_option( 'woocommerce_enable_review_rating' ) == 'no' )
				unset( $catalog_orderby['rating'] );

			foreach ($catalog_orderby as $id => $name){
		  		$selected = str_replace( "='selected'", "", selected( $orderby, $id, false ));
		    	echo '<li class="'.$selected.'"><a href="'.home_url($wp->request).'?orderby='.$id.'">'.$name.'</a></li>';
		  	}
		?>
	<?php
		// Keep query string vars intact
		foreach ( $_GET as $key => $val ) {
			if ( 'orderby' == $key )
				continue;
			echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
		}
	?>
	</ul>
</form>