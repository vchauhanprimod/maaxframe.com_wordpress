<?php
/*
Plugin Name: Kento Pricing Table Free
Plugin URI: http://kentothemes.com
Description: Pure CSS3 & HTML Pricing Table Grid with Unlimted Column.
Version: 1.3
Author: KentoThemes
Author URI: http://kentothemes.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/



define('KENTO_PRICING_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
function kpt_script()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style('kpt-free-css', KENTO_PRICING_PLUGIN_PATH.'css/style.css');
		wp_enqueue_script('kpt-free-ajax-js', plugins_url( '/js/kpt-ajax.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'kpt-free-ajax-js', 'kpt_ajax', array( 'kpt_ajaxurl' => admin_url( 'admin-ajax.php')));
	}
add_action('init', 'kpt_script');






function kpt_ajax_form()
	{
		$kpt_bg_color = get_option( 'kpt_bg_color' );
		$kpt_total_column = $_POST['kpt_total_column'];
		$kpt_total_row  = $_POST['kpt_total_row'];		
		$kpt_table_field = get_option( 'kpt_table_field' );

		
			
			
echo "<table class='price-table-admin' >";
for($j=1; $j<=$kpt_total_row ; $j++)
  {
  echo "<tr>";
  
		for($i=1; $i<=$kpt_total_column; $i++)
			{
				echo "<td>";

		if(!isset($kpt_table_field[$i.$j]))
			{
			 $kpt_table_field[$i.$j] ="";
			}


				echo "<input size='10' name='kpt_table_field[".$i.$j."]' class='kpt-table-field-".$i.$j."' type='text' value='".$kpt_table_field[$i.$j]."' />";
					
				echo "</td>";
				
			}
  echo "</tr>";
  }


	echo "</table>";

		die();
	}



add_action('wp_ajax_kpt_ajax_form', 'kpt_ajax_form');
add_action('wp_ajax_nopriv_kpt_ajax_form', 'kpt_ajax_form');






function kpt_display($cont){

			$kpt_style = "style1";
			$kpt_total_column = get_option( 'kpt_total_column' );
			$kpt_total_row = get_option( 'kpt_total_row' );			
			$kpt_table_field = get_option( 'kpt_table_field' );



$cont.= "<div id='price-table-main' >";
$cont.=  "<div class='price-table' >";
$j = 1;
while($j<=$kpt_total_column)
  {
	$cont.=  "<ul class='price-table-column'>";
				$cont.=  "<li class='price-table-row'>";
				$cont.=  "<ul class='price-table-column-items'>";
				$i = 1;
				while($i<=$kpt_total_row )

			{		
					if($kpt_style=="style1"){
			
						if(empty($kpt_table_field[$j.$i]))
							{
								$cont.=  "<li class='price-table-items li-item-empty'>";
								$cont.=  "<span class='item-empty'>&nbsp;</span>";
								$cont.=  "</li>";
							}
						
						else
							{
								$cont.=  "<li class='price-table-items'>";
								$cont.=  "<div>";
								$cont.=  $kpt_table_field[$j.$i];
								
								$cont.=  "</div>";
								$cont.=  "</li>";
							
							}
						}
	
						
				$i++;
			}
			$cont.=  "</ul>";
	$cont.=  "</li>";
	$cont.=  "</ul>";
	$j++;
  }


$cont.=  "</div>";
$cont.=  "</div>";
return $cont;



}

add_shortcode('kpt', 'kpt_display');

add_filter('wp_head','price_table_style');

function price_table_style()
	{	
		$kpt_column_width = get_option( 'kpt_column_width' );
		$kpt_bg_color = get_option( 'kpt_bg_color' );
		$kpt_table_bg_img = get_option( 'kpt_table_bg_img' );		
			
		echo "<style type='text/css'>";
		
		echo "		
.price-table ul li ul li:first-child{
	background-color: ".$kpt_bg_color." !important;
	border-bottom: 1px solid ".price_table_style_dark_color($kpt_bg_color)." !important;
	color: #FFFFFF !important;
}
	
.price-table ul li ul li:last-child {
	background-color: ".$kpt_bg_color." !important;
	color: #FFFFFF !important;
  
}	
	
.price-table ul li ul li:nth-child(2) {
	background-color: ".$kpt_bg_color." !important;
	color: #FFFFFF !important;
}	

.price-table ul li ul li:last-child div a {
  background-color: ".price_table_style_dark_color($kpt_bg_color).";
}

";
if($kpt_table_bg_img==1)
	{
	echo ".price-table
		{
			background: url('".KENTO_PRICING_PLUGIN_PATH."css/blur_2-t2.jpg') no-repeat scroll 0 0 / 100% auto rgba(0, 0, 0, 0);
		}";	
	}



if(isset($kpt_column_width))
	{

	echo ".price-table ul li ul li
		{
			width: ".$kpt_column_width."px;
		}";


}
		echo "</style>";
	}






function price_table_style_dark_color($kpt_bg_color)
	{
		$input = $kpt_bg_color;
	  
		$col = Array(
			hexdec(substr($input,1,2)),
			hexdec(substr($input,3,2)),
			hexdec(substr($input,5,2))
		);
		$darker = Array(
			$col[0]/2,
			$col[1]/2,
			$col[2]/2
		);

		return "#".sprintf("%02X%02X%02X", $darker[0], $darker[1], $darker[2]);
		
		
	}






////////////////////////////////////////////////////////////

add_action('admin_init', 'kpt_init' );
add_action('admin_menu', 'kpt_menu_init');

function kpt_init(){
	register_setting('kpt_plugin_options', 'kpt_column_width');	
	register_setting('kpt_plugin_options', 'kpt_bg_color');
	register_setting('kpt_plugin_options', 'kpt_total_column');
	register_setting('kpt_plugin_options', 'kpt_total_row');	
	register_setting('kpt_plugin_options', 'kpt_table_field');
	register_setting('kpt_plugin_options', 'kpt_table_bg_img');	
    }
	
function kpt_settings(){
	include('kpt-admin.php');	
}

function kpt_menu_init() {
	add_menu_page(__('KPT','kpt'), __('KPT Settings','kpt'), 'manage_options', 'kpt_settings', 'kpt_settings');
add_submenu_page('kpt_settings', __('KPT Info','menu-kpt'), __('KPT Info','menu-kpt'), 'manage_options', 'kpt_pro', 'kpt_pro');
}

function kpt_pro(){
	include('kpt-pro.php');	
}





add_action( 'admin_enqueue_scripts', 'kpt_color_picker' );

function kpt_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('/js/kpt-ajax.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}











?>