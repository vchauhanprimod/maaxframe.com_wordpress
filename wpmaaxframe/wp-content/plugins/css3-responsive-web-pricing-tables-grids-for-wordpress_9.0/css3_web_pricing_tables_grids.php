<?php
/*
Plugin Name: CSS3 Responsive Web Pricing Tables Grids
Plugin URI: http://codecanyon.net/item/css3-responsive-web-pricing-tables-grids-for-wordpress/629172?ref=QuanticaLabs
Description: CSS3 Responsive Web Pricing Tables Grids plugin.
Author: QuanticaLabs
Author URI: http://codecanyon.net/user/QuanticaLabs/portfolio?ref=QuanticaLabs
Version: 9.0
*/

//settings link
function css3_grid_settings_link($links) 
{ 
  $settings_link = '<a href="options-general.php?page=css3_grid_admin" title="Settings">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links;
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'css3_grid_settings_link' );

//admin
if(is_admin())
{
	function css3_grid_admin_init()
	{
		wp_register_script('css3_grid_admin', plugins_url('js/css3_grid_admin.js', __FILE__), array(), "1.0");
		wp_register_script('jquery-carouFredSel', plugins_url('js/jquery.carouFredSel-6.1.0-packed.js', __FILE__));
		wp_register_script('jquery-easing', plugins_url('js/jquery.easing.1.3.js', __FILE__));
		wp_register_script('jquery-touchSwipe', plugins_url('js/jquery.touchSwipe.min.js', __FILE__));
		wp_register_style('css3_grid_font_yanone', 'http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz');
		wp_register_style('css3_grid_style_admin', plugins_url('admin/style.css', __FILE__));
		wp_register_style('css3_grid_table1_style', plugins_url('table1/css3_grid_style.css', __FILE__));
		wp_register_style('css3_grid_table2_style', plugins_url('table2/css3_grid_style.css', __FILE__));
		wp_register_style('css3_grid_responsive', plugins_url('responsive.css', __FILE__));
	}
	add_action('admin_init', 'css3_grid_admin_init');

	function css3_grid_admin_print_scripts()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tabs');
		wp_enqueue_script('css3_grid_admin');
		wp_enqueue_script('jquery-carouFredSel');
		wp_enqueue_script('jquery-easing');
		wp_enqueue_script('jquery-touchSwipe');
		//pass data to javascript
		$data = array(
			'imgUrl' =>  plugins_url('img/', __FILE__),
			'siteUrl' => get_site_url(),
			'selectedShortcodeId' => (isset($_POST["action"]) && $_POST["action"]=="save_css3_grid" ? $_POST["shortcodeId"] : "")
		);
		wp_localize_script('css3_grid_admin', 'config', $data);
		wp_enqueue_style('css3_grid_font_yanone');
		wp_enqueue_style('css3_grid_style_admin');
		wp_enqueue_style('css3_grid_table1_style');
		wp_enqueue_style('css3_grid_table2_style');
		wp_enqueue_style('css3_grid_responsive');
	}
	
	function css3_grid_admin_menu()
	{	
		$page = add_options_page('CSS3 Web Pricing Tables Grids', 'CSS3 Web Pricing Tables Grids', 'administrator', 'css3_grid_admin', 'css3_grid_admin_page');
		add_action('admin_print_scripts-' . $page, 'css3_grid_admin_print_scripts');
	}
	add_action('admin_menu', 'css3_grid_admin_menu');
	
	function css3_grid_stripslashes_deep($value)
	{
		$value = is_array($value) ?
					array_map('stripslashes_deep', $value) :
					stripslashes($value);

		return $value;
	}
	function css3_grid_ajax_get_settings()
	{
		echo "css3_start" . json_encode(css3_grid_stripslashes_deep(get_option('css3_grid_shortcode_settings_' . $_POST["id"]))) . "css3_end";
		exit();
	}
	add_action('wp_ajax_css3_grid_get_settings', 'css3_grid_ajax_get_settings');
	
	function css3_grid_ajax_delete()
	{
		echo "css3_start" . delete_option($_POST["id"]) . "css3_end";
		exit();
	}
	add_action('wp_ajax_css3_grid_delete', 'css3_grid_ajax_delete');
	
	function css3_grid_ajax_get_font_subsets()
	{
		if($_POST["font"]!="")
		{
			$subsets = '';
			$fontExplode = explode(":", $_POST["font"]);
			//get google fonts
			$google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyB4_VClnbxilxqjZd7NbysoHwAXX1ZGdKQ';
			$fontsJson = wp_remote_retrieve_body(wp_remote_get($google_api_url, array('sslverify' => false )));
			$fontsArray = json_decode($fontsJson);
			$fontsCount = count($fontsArray->items);
			for($i=0; $i<$fontsCount; $i++)
			{
				if($fontsArray->items[$i]->family==$fontExplode[0])
				{
					for($j=0; $j<count($fontsArray->items[$i]->subsets); $j++)
					{
						$subsets .= '<option value="' . $fontsArray->items[$i]->subsets[$j] . '">' . $fontsArray->items[$i]->subsets[$j] . '</option>';
					}
					break;
				}
			}
			echo "css3_start" . $subsets . "css3_end";
		}
		exit();
	}
	add_action('wp_ajax_css3_grid_get_font_subsets', 'css3_grid_ajax_get_font_subsets');
	
	function css3_grid_ajax_preview()
	{
		$responsiveStepWidth = "";
		for($i=0; $i<count($_POST["responsiveStepWidth"]); $i++)
		{
			$responsiveStepWidth .= $_POST["responsiveStepWidth"][$i];
			if($i+1<count($_POST["responsiveStepWidth"]));
				$responsiveStepWidth .= "|";
		}
		$responsiveButtonWidth = "";
		for($i=0; $i<count($_POST["responsiveButtonWidth"]); $i++)
		{
			$responsiveButtonWidth .= $_POST["responsiveButtonWidth"][$i];
			if($i+1<count($_POST["responsiveButtonWidth"]));
				$responsiveButtonWidth .= "|";
		}
		$responsiveHeaderFontSize = "";
		for($i=0; $i<count($_POST["responsiveHeaderFontSize"]); $i++)
		{
			$responsiveHeaderFontSize .= $_POST["responsiveHeaderFontSize"][$i];
			if($i+1<count($_POST["responsiveHeaderFontSize"]));
				$responsiveHeaderFontSize .= "|";
		}
		$responsivePriceFontSize = "";
		for($i=0; $i<count($_POST["responsivePriceFontSize"]); $i++)
		{
			$responsivePriceFontSize .= $_POST["responsivePriceFontSize"][$i];
			if($i+1<count($_POST["responsivePriceFontSize"]));
				$responsivePriceFontSize .= "|";
		}
		$responsivePermonthFontSize = "";
		for($i=0; $i<count($_POST["responsivePermonthFontSize"]); $i++)
		{
			$responsivePermonthFontSize .= $_POST["responsivePermonthFontSize"][$i];
			if($i+1<count($_POST["responsivePermonthFontSize"]));
				$responsivePermonthFontSize .= "|";
		}
		$responsiveContentFontSize = "";
		for($i=0; $i<count($_POST["responsiveContentFontSize"]); $i++)
		{
			$responsiveContentFontSize .= $_POST["responsiveContentFontSize"][$i];
			if($i+1<count($_POST["responsiveContentFontSize"]));
				$responsiveContentFontSize .= "|";
		}
		$responsiveButtonsFontSize = "";
		for($i=0; $i<count($_POST["responsiveButtonsFontSize"]); $i++)
		{
			$responsiveButtonsFontSize .= $_POST["responsiveButtonsFontSize"][$i];
			if($i+1<count($_POST["responsiveButtonsFontSize"]));
				$responsiveButtonsFontSize .= "|";
		}
		$widths = "";
		for($i=0; $i<count($_POST["widths"]); $i++)
		{
			$widths .= $_POST["widths"][$i];
			if($i+1<count($_POST["widths"]));
				$widths .= "|";
		}
		$responsiveWidths = "";
		for($i=0; $i<count($_POST["responsiveWidths"]); $i++)
		{
			$responsiveWidths .= $_POST["responsiveWidths"][$i];
			if($i+1<count($_POST["responsiveWidths"]));
				$responsiveWidths .= "|";
		}
		$aligments = "";
		for($i=0; $i<count($_POST["aligments"]); $i++)
		{
			$aligments .= $_POST["aligments"][$i];
			if($i+1<count($_POST["aligments"]));
				$aligments .= "|";
		}
		$actives = "";
		for($i=0; $i<count($_POST["actives"]); $i++)
		{
			$actives .= (int)$_POST["actives"][$i];
			if($i+1<count($_POST["actives"]));
				$actives .= "|";
		}
		$hiddens = "";
		for($i=0; $i<count($_POST["hiddens"]); $i++)
		{
			$hiddens .= (int)$_POST["hiddens"][$i];
			if($i+1<count($_POST["hiddens"]));
				$hiddens .= "|";
		}
		$ribbons = "";
		for($i=0; $i<count($_POST["ribbons"]); $i++)
		{
			$ribbons .= $_POST["ribbons"][$i];
			if($i+1<count($_POST["ribbons"]));
				$ribbons .= "|";
		}
		$heights = "";
		for($i=0; $i<count($_POST["heights"]); $i++)
		{
			$heights .= $_POST["heights"][$i];
			if($i+1<count($_POST["heights"]));
				$heights .= "|";
		}
		$responsiveHeights = "";
		for($i=0; $i<count($_POST["responsiveHeights"]); $i++)
		{
			$responsiveHeights .= $_POST["responsiveHeights"][$i];
			if($i+1<count($_POST["responsiveHeights"]));
				$responsiveHeights .= "|";
		}
		$paddingsTop = "";
		for($i=0; $i<count($_POST["paddingsTop"]); $i++)
		{
			$paddingsTop .= (int)$_POST["paddingsTop"][$i];
			if($i+1<count($_POST["paddingsTop"]));
				$paddingsTop .= "|";
		}
		$paddingsBottom = "";
		for($i=0; $i<count($_POST["paddingsBottom"]); $i++)
		{
			$paddingsBottom .= (int)$_POST["paddingsBottom"][$i];
			if($i+1<count($_POST["paddingsBottom"]));
				$paddingsBottom .= "|";
		}
		$texts = "";
		for($i=0; $i<count($_POST["texts"]); $i++)
		{
			$texts .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $_POST["texts"][$i])));
			if($i+1<count($_POST["texts"]));
				$texts .= "|";
		}
		$tooltips = "";
		for($i=0; $i<count($_POST["tooltips"]); $i++)
		{
			$tooltips .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $_POST["tooltips"][$i])));
			if($i+1<count($_POST["tooltips"]));
				$tooltips .= "|";
		}
		$headerFontSubsets = "";
		for($i=0; $i<count($_POST["headerFontSubset"]); $i++)
		{
			$headerFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $_POST["headerFontSubset"][$i])));
			if($i+1<count($_POST["headerFontSubset"]));
				$headerFontSubsets .= "|";
		}
		$priceFontSubsets = "";
		for($i=0; $i<count($_POST["priceFontSubset"]); $i++)
		{
			$priceFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $_POST["priceFontSubset"][$i])));
			if($i+1<count($_POST["priceFontSubset"]));
				$priceFontSubsets .= "|";
		}
		$permonthFontSubsets = "";
		for($i=0; $i<count($_POST["permonthFontSubset"]); $i++)
		{
			$permonthFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $_POST["permonthFontSubset"][$i])));
			if($i+1<count($_POST["permonthFontSubset"]));
				$permonthFontSubsets .= "|";
		}
		$contentFontSubsets = "";
		for($i=0; $i<count($_POST["contentFontSubset"]); $i++)
		{
			$contentFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $_POST["contentFontSubset"][$i])));
			if($i+1<count($_POST["contentFontSubset"]));
				$contentFontSubsets .= "|";
		}
		$buttonsFontSubsets = "";
		for($i=0; $i<count($_POST["buttonsFontSubset"]); $i++)
		{
			$buttonsFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $_POST["buttonsFontSubset"][$i])));
			if($i+1<count($_POST["buttonsFontSubset"]));
				$buttonsFontSubsets .= "|";
		}
		
		echo "css3_start" . do_shortcode("[css3_grid_print id='" . $_POST["shortcodeId"] . "' kind='" . (int)$_POST["kind"] . "' style='" . (int)$_POST["styleForTable" . (int)$_POST["kind"]] . "' hoverType='" . $_POST["hoverTypeForTable" . (int)$_POST["kind"]] . "' responsive='" . $_POST["responsive"] . "' responsiveHideCaptionColumn='" . (int)$_POST["responsiveHideCaptionColumn"] . "' responsiveSteps='" . (int)$_POST["responsiveSteps"] . "' responsiveStepWidth='" . $responsiveStepWidth . "' responsiveButtonWidth='" . $responsiveButtonWidth . "' responsiveHeaderFontSize='" . $responsiveHeaderFontSize . "' responsivePriceFontSize='" . $responsivePriceFontSize . "' responsivePermonthFontSize='" . $responsivePermonthFontSize . "' responsiveContentFontSize='" . $responsiveContentFontSize . "' responsiveButtonsFontSize='" . $responsiveButtonsFontSize . "' priceFontCustom='" . $_POST["priceFontCustom"] . "' priceFont='" . $_POST["priceFont"] . "' priceFontSubsets='" . $priceFontSubsets . "' priceFontSize='" . $_POST["priceFontSize"] . "' headerFontCustom='" . $_POST["headerFontCustom"] . "' headerFont='" . $_POST["headerFont"] . "' headerFontSubsets='" . $headerFontSubsets . "' headerFontSize='" . $_POST["headerFontSize"] . "' permonthFontCustom='" . $_POST["permonthFontCustom"] . "' permonthFont='" . $_POST["permonthFont"] . "' permonthFontSubsets='" . $permonthFontSubsets . "' permonthFontSize='" . $_POST["permonthFontSize"] . "' contentFontCustom='" . $_POST["contentFontCustom"] . "' contentFont='" . $_POST["contentFont"] . "' contentFontSubsets='" . $contentFontSubsets . "' contentFontSize='" . $_POST["contentFontSize"] . "' buttonsFontCustom='" . $_POST["buttonsFontCustom"] . "' buttonsFont='" . $_POST["buttonsFont"] . "' buttonsFontSubsets='" . $buttonsFontSubsets . "' buttonsFontSize='" . $_POST["buttonsFontSize"] . "' slidingColumns='" . $_POST["slidingColumns"] . "' visibleColumns='" . (int)$_POST["visibleColumns"] . "' scrollColumns='" . (int)$_POST["scrollColumns"] . "' slidingNavigation='" . (int)$_POST["slidingNavigation"] . "' slidingNavigationArrows='" . (int)$_POST["slidingNavigationArrows"] . "' slidingArrowsStyle='" . $_POST["slidingArrowsStyle"] . "' slidingPagination='" . (int)$_POST["slidingPagination"] . "' slidingPaginationPosition='" . $_POST["slidingPaginationPosition"] . "' slidingPaginationStyle='" . $_POST["slidingPaginationStyle"] . "' slidingOnTouch='" . (int)$_POST["slidingOnTouch"] . "' slidingOnMouse='" . (int)$_POST["slidingOnMouse"] . "' slidingThreshold='" . (int)$_POST["slidingThreshold"] . "' slidingAutoplay='" . (int)$_POST["slidingAutoplay"] . "' slidingEffect='" . $_POST["slidingEffect"] . "' slidingEasing='" . $_POST["slidingEasing"] . "' slidingDuration='" . (int)$_POST["slidingDuration"] . "' columns='" . (int)$_POST["columns"] . "' rows='" . (int)$_POST["rows"] . "' hiddenRows='" . (int)$_POST["hiddenRows"] . "' hiddenRowsButtonExpandText='" . $_POST["hiddenRowsButtonExpandText"] . "' hiddenRowsButtonCollapseText='" . $_POST["hiddenRowsButtonCollapseText"] . "' texts='" . $texts . "' tooltips='" . $tooltips . "' widths='" . $widths . "' responsivewidths='" . $responsiveWidths . "' aligments='" . $aligments . "' actives='" . $actives . "' hiddens='" . $hiddens . "' ribbons='" . $ribbons . "' heights='" . $heights . "' responsiveheights='" . $responsiveHeights . "' paddingstop='" . $paddingsTop . "' paddingsbottom='" . $paddingsBottom . "']") . "css3_end";
		exit();
	}
	add_action('wp_ajax_css3_grid_preview', 'css3_grid_ajax_preview');
	
	function css3_grid_admin_page()
	{
		$error = "";
		$message = "";
		if(isset($_POST["action"]) && $_POST["action"]=="save_css3_grid")
		{
			if($_POST["shortcodeId"]!="")
			{
				$css3_grid_options = array(
					'columns' => $_POST['columns'],
					'rows' => $_POST['rows'],
					'hiddenRows' => $_POST['hiddenRows'],
					'hiddenRowsButtonExpandText' => $_POST["hiddenRowsButtonExpandText"],
					'hiddenRowsButtonCollapseText' => $_POST["hiddenRowsButtonCollapseText"],
					'kind' => $_POST['kind'],
					'styleForTable1' => $_POST["styleForTable1"],
					'styleForTable2' => $_POST["styleForTable2"],
					'hoverTypeForTable1' => $_POST["hoverTypeForTable1"],
					'hoverTypeForTable2' => $_POST["hoverTypeForTable2"],
					'responsive' => $_POST['responsive'],
					'responsiveHideCaptionColumn' => $_POST['responsiveHideCaptionColumn'],
					'responsiveSteps' => $_POST['responsiveSteps'],
					'responsiveStepWidth' => $_POST['responsiveStepWidth'],
					'responsiveButtonWidth' => $_POST['responsiveButtonWidth'],
					'responsiveHeaderFontSize' => $_POST['responsiveHeaderFontSize'],
					'responsivePriceFontSize' => $_POST['responsivePriceFontSize'],
					'responsivePermonthFontSize' => $_POST['responsivePermonthFontSize'],
					'responsiveContentFontSize' => $_POST['responsiveContentFontSize'],
					'responsiveButtonsFontSize' => $_POST['responsiveButtonsFontSize'],
					'priceFontCustom' => $_POST['priceFontCustom'],
					'priceFont' => $_POST['priceFont'],
					'priceFontSubset' => $_POST['priceFontSubset'],
					'priceFontSize' => $_POST['priceFontSize'],
					'headerFontCustom' => $_POST['headerFontCustom'],
					'headerFont' => $_POST['headerFont'],
					'headerFontSubset' => $_POST['headerFontSubset'],
					'headerFontSize' => $_POST['headerFontSize'],
					'permonthFontCustom' => $_POST['permonthFontCustom'],
					'permonthFont' => $_POST['permonthFont'],
					'permonthFontSubset' => $_POST['permonthFontSubset'],
					'permonthFontSize' => $_POST['permonthFontSize'],
					'contentFontCustom' => $_POST['contentFontCustom'],
					'contentFont' => $_POST['contentFont'],
					'contentFontSubset' => $_POST['contentFontSubset'],
					'contentFontSize' => $_POST['contentFontSize'],
					'buttonsFontCustom' => $_POST['buttonsFontCustom'],
					'buttonsFont' => $_POST['buttonsFont'],
					'buttonsFontSubset' => $_POST['buttonsFontSubset'],
					'buttonsFontSize' => $_POST['buttonsFontSize'],
					'slidingColumns' => $_POST['slidingColumns'],
					'visibleColumns' => $_POST['visibleColumns'],
					'scrollColumns' => $_POST['scrollColumns'],
					'slidingNavigation' => $_POST['slidingNavigation'],
					'slidingNavigationArrows' => $_POST['slidingNavigationArrows'],
					'slidingArrowsStyle' => $_POST['slidingArrowsStyle'],
					'slidingPagination' => $_POST['slidingPagination'],
					'slidingPaginationPosition' => $_POST['slidingPaginationPosition'],
					'slidingPaginationStyle' => $_POST['slidingPaginationStyle'],
					'slidingOnTouch' => $_POST['slidingOnTouch'],
					'slidingOnMouse' => $_POST['slidingOnMouse'],
					'slidingThreshold' => $_POST['slidingThreshold'],
					'slidingAutoplay' => $_POST['slidingAutoplay'],
					'slidingEffect' => $_POST['slidingEffect'],
					'slidingEasing' => $_POST['slidingEasing'],
					'slidingDuration' => $_POST['slidingDuration'],
					'widths' => $_POST['widths'],
					'responsiveWidths' => $_POST['responsiveWidths'],
					'aligments' => $_POST['aligments'],
					'actives' => $_POST['actives'],
					'hiddens' => $_POST['hiddens'],
					'ribbons' => $_POST['ribbons'],
					'heights' => $_POST['heights'],
					'responsiveHeights' => $_POST['responsiveHeights'],
					'paddingsTop' => $_POST['paddingsTop'],
					'paddingsBottom' => $_POST['paddingsBottom'],
					'texts' => $_POST['texts'],
					'tooltips' => $_POST['tooltips']
				);
				//add if not exist or update if exist
				$updated = true;
				if(!get_option('css3_grid_shortcode_settings_' . $_POST["shortcodeId"]))
					$updated = false;
				/*echo "<pre style='white-space: normal;'>";
				var_export($css3_grid_options);
				echo "</pre>";*/
				update_option('css3_grid_shortcode_settings_' . $_POST["shortcodeId"], $css3_grid_options);
				$message .= "Settings saved!" . ($updated ? " (overwritten)" : "");
				$message .= "<br />Please use<br />[css3_grid id='" . $_POST["shortcodeId"] . "']<br />shortcode to put css3 grid table on your page.";
			}
			else
			{
				$error .= "Please fill 'Shortcode id' field!";
			}
		}
		else if(isset($_POST["action"]) && $_POST["action"]=="import_from_file")
		{
			$importedOptions = json_decode(file_get_contents($_FILES['import_from_file_input']['tmp_name']),true);
			$importedOptionsCount = count($importedOptions);
			$importedIds = "";
			for($i=0; $i<$importedOptionsCount; $i++)
			{
				$name = $importedOptions[$i]["name"];
				unset($importedOptions[$i]["name"]);
				$importedIds .= "<br />" . substr($name, 29);
				update_option($name, $importedOptions[$i]);
			}
			if($importedIds!="")
				$message .= "Import completed successfully! Imported pricing tables:" . $importedIds;
			else
				$error .= "No data for import found!";
		}
		$css3GridAllShortcodeIds = array();
		/*if(function_exists('is_multisite') && is_multisite()) 
		{
			global $blog_id;
			global $wpdb;
			$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
			$query = "SELECT meta_key, meta_value FROM {$wpdb->sitemeta} WHERE site_id='" . $blog_id . "' AND meta_key LIKE '%css3_grid_shortcode_settings%'";
			$allOptions = $wpdb->get_results($query, ARRAY_A);
			foreach($allOptions as $key => $value)
			{
				if(substr($value["meta_key"], 0, 28)=="css3_grid_shortcode_settings")
					$css3GridAllShortcodeIds[] = $value["meta_key"];
			}
		}
		else
		{*/
			$allOptions = get_alloptions();
			foreach($allOptions as $key => $value)
			{
				if(substr($key, 0, 28)=="css3_grid_shortcode_settings")
					$css3GridAllShortcodeIds[] = $key;
			}
		//}
		//sort shortcode ids
		sort($css3GridAllShortcodeIds);
		?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2>CSS3 Web Pricing Tables Grids settings</h2>
		</div>
		<?php
		if($error!="" || $message!="")
		{
		?>
		<div class="<?php echo ($message!="" ? "updated" : "error"); ?> settings-error"> 
			<p style="line-height: 150%;font-weight: bold;">
				<?php echo ($message!="" ? $message : $error); ?>
			</p>
		</div>
		<?php
		}
		$shortcodesSelect = "<br />
			<select name='inset'>
				<option value='-1'>choose shortcode...</option>
				<optgroup label='Table 1'>
					<option value='caption'>caption</option>
					<option value='header_title'>header title</option>
					<option value='price'>price</option>
					<option value='button'>button</option>
					<option value='button_orange'>button orange</option>
					<option value='button_yellow'>button yellow</option>
					<option value='button_lightgreen'>button lightgreen</option>
					<option value='button_green'>button green</option>
				</optgroup>
				<optgroup label='Table 2'>
					<option value='caption2'>caption</option>
					<option value='header_title2'>header title</option>
					<option value='price2'>price</option>
					<option value='button1'>button style 1</option>
					<option value='button2'>button style 2</option>
					<option value='button3'>button style 3</option>
					<option value='button4'>button style 4</option>
				</optgroup>
				<optgroup label='Yes icons'>";
		for($i=0; $i<21; $i++)
			$shortcodesSelect .= "<option value='tick_" . ($i<9 ? "0" : "") . ($i+1) . "'>style " . ($i+1) . "</option>";
		$shortcodesSelect .= "</optgroup>
				<optgroup label='No icons'>";
		for($i=0; $i<21; $i++)
			$shortcodesSelect .= "<option value='cross_" . ($i<9 ? "0" : "") . ($i+1) . "'>style " . ($i+1) . "</option>";
		$shortcodesSelect .= "</optgroup>
			</select>
			<span class='css3_grid_tooltip css3_grid_admin_info'>
				<span>
					<div class='css3_grid_tooltip_column'>
						<strong>Yes icons</strong>";
						for($i=0; $i<11; $i++)
							$shortcodesSelect .= "<img src='" . plugins_url("img/tick_" . ($i<9 ? "0" : "") . ($i+1) . ".png", __FILE__) . "' /><label>&nbsp;style " . ($i+1) . "</label><br />";
		$shortcodesSelect .= "
					</div>
					<div class='css3_grid_tooltip_column'>
						<strong>Yes icons</strong>";
						for($i=11; $i<21; $i++)
							$shortcodesSelect .= "<img src='" . plugins_url("img/tick_" . ($i+1) . ".png", __FILE__) . "' /><label>&nbsp;style " . ($i+1) . "</label><br />";
		$shortcodesSelect .= "
					</div>
					<div class='css3_grid_tooltip_column'>
						<strong>No icons</strong>";
					for($i=0; $i<11; $i++)
							$shortcodesSelect .= "<img src='" . plugins_url("img/cross_" . ($i<9 ? "0" : "") . ($i+1) . ".png", __FILE__) . "' /><label>&nbsp;style " . ($i+1) . "</label><br />";
		$shortcodesSelect .= "
					</div>
					<div class='css3_grid_tooltip_column'>
						<strong>No icons</strong>";
					for($i=11; $i<21; $i++)
							$shortcodesSelect .= "<img src='" . plugins_url("img/cross_" . ($i+1) . ".png", __FILE__) . "' /><label>&nbsp;style " . ($i+1) . "</label><br />";
		$shortcodesSelect .= "
					</div>
				</span>
			</span>
			<br />
			<label>tooltip: </label><input class='css3_grid_tooltip_input' type='text' name='tooltips[]' value='' />";
		//get google fonts
		$google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyB4_VClnbxilxqjZd7NbysoHwAXX1ZGdKQ';
		$fontsJson = wp_remote_retrieve_body(wp_remote_get($google_api_url, array('sslverify' => false )));
		$fontsArray = json_decode($fontsJson);

		//$fontsJson = file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyB4_VClnbxilxqjZd7NbysoHwAXX1ZGdKQ');
		//$fontsArray = json_decode($fontsJson);
		
		$fontsHtml = "";
		$fontsCount = count($fontsArray->items);
		for($i=0; $i<$fontsCount; $i++)
		{
			$variantsCount = count($fontsArray->items[$i]->variants);
			if($variantsCount>1)
			{
				for($j=0; $j<$variantsCount; $j++)
				{
					$fontsHtml .= '<option value="' . $fontsArray->items[$i]->family . ":" . $fontsArray->items[$i]->variants[$j] . '">' . $fontsArray->items[$i]->family . ":" . $fontsArray->items[$i]->variants[$j] . '</option>';
				}
			}
			else
			{
				$fontsHtml .= '<option value="' . $fontsArray->items[$i]->family . '">' . $fontsArray->items[$i]->family . '</option>';
			}
		}
		?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="css3_grid_settings" enctype="multipart/form-data">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="editShortcodeId">Choose shortcode id</label>
						</th>
						<td>
							<select name="editShortcodeId" id="editShortcodeId">
								<option value="-1">choose...</option>
								<?php
									for($i=0; $i<count($css3GridAllShortcodeIds); $i++)
										echo "<option value='$css3GridAllShortcodeIds[$i]'>" . substr($css3GridAllShortcodeIds[$i], 29) . "</option>";
								?>
							</select>
							<img style="display: none; cursor: pointer;" id="deleteButton" src="<?php echo WP_PLUGIN_URL; ?>/css3_web_pricing_tables_grids/img/delete.png" alt="del" title="Delete this pricing table" />
							<span id="ajax_loader" style="display: none;"><img style="margin-bottom: -3px;" src="<?php echo WP_PLUGIN_URL; ?>/css3_web_pricing_tables_grids/img/ajax-loader.gif" /></span>
							<span class="description">Choose the shortcode id for editing</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="shortcodeId">Or type new shortcode id *</label>
						</th>
						<td>
							<input type="text" class="regular-text" value="" id="shortcodeId" name="shortcodeId">
							<span class="description">Unique identifier for css3_grid shortcode. Don't use special characters.</span>
						</td>
					</tr>
				</tbody>
			</table>
			<div id="css3_grid_configuration_tabs">
				<ul class="nav-tabs">
					<li class="nav-tab">
						<a href="#tab-main">
							<?php _e('Main configuration', 'css3_grid'); ?>
						</a>
					</li>
					<li class="nav-tab">
						<a href="#tab-responsive">
							<?php _e('Responsive', 'css3_grid'); ?>
						</a>
					</li>
					<li class="nav-tab">
						<a href="#tab-fonts">
							<?php _e('Fonts configuration', 'css3_grid'); ?>
						</a>
					</li>
					<li class="nav-tab">
						<a href="#tab-sliding">
							<?php _e('Sliding configuration', 'css3_grid'); ?>
						</a>
					</li>
					<li class="nav-tab">
						<a href="#tab-import-export">
							<?php _e('Import/export', 'css3_grid'); ?>
						</a>
					</li>
				</ul>
				<div id="tab-main">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="kind">Type</label>
								</th>
								<td>
									<select name="kind" id="kind">
										<option value="1">Table 1</option>
										<option value="2">Table 2</option>
									</select>
									<span class="description">One of two available kinds of table.</span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="style">Style</label>
								</th>
								<td>
									<select name="styleForTable1" id="styleForTable1">
										<option value="1">Style 1</option>
										<option value="2">Style 2</option>
										<option value="3">Style 3</option>
										<option value="4">Style 4</option>
										<option value="5">Style 5</option>
										<option value="6">Style 6</option>
										<option value="7">Style 7</option>
										<option value="8">Style 8</option>
										<option value="9">Style 9</option>
										<option value="10">Style 10</option>
										<option value="11">Style 11</option>
										<option value="12">Style 12</option>
										<option value="13">Style 13 (medicenter blue)</option>
										<option value="14">Style 14 (medicenter green)</option>
										<option value="15">Style 15 (medicenter orange)</option>
										<option value="16">Style 16 (medicenter red)</option>
										<option value="17">Style 17 (medicenter turquoise)</option>
										<option value="18">Style 18 (medicenter violet)</option>
									</select>
									<select name="styleForTable2" id="styleForTable2" style="display: none;">
										<option value="1">Style 1</option>
										<option value="2">Style 2</option>
										<option value="3">Style 3</option>
										<option value="4">Style 4</option>
										<option value="5">Style 5</option>
										<option value="6">Style 6</option>
										<option value="7">Style 7</option>
										<option value="8">Style 8</option>
									</select>
									<span class="description">Specifies the style version of the table.</span>
								</td>
							</tr>
							<tr valign="top" class="css3_hover_type_row">
								<th scope="row">
									<label for="hoverType">Hover type</label>
								</th>
								<td>
									<select name="hoverTypeForTable1" id="hoverTypeForTable1">
										<option value="active">Active</option>
										<option value="light">Light</option>
										<option value="disabled">Disabled</option>
									</select>
									<select name="hoverTypeForTable2" id="hoverTypeForTable2" style="display: none;">
										<option value="active">Active</option>
										<option value="disabled">Disabled</option>
									</select>
									<span class="description">Specifies the hover effect for the columns.</span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="columns">Columns</label>
								</th>
								<td>
									<input style="float: left;" type="text" readonly="readonly" class="regular-text" value="3" id="columns" name="columns" maxlength="2">
									<a href="#" class="css3_grid_less" title="less"></a>
									<a href="#" class="css3_grid_more" title="more"></a>
									<span style="float: left;margin-top: 2px;" class="description">Number of columns.</span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="rows">Rows</label>
								</th>
								<td>
									<input style="float: left;" type="text" readonly="readonly" class="regular-text" value="9" id="rows" name="rows" maxlength="2">
									<a href="#" class="css3_grid_less" title="less"></a>
									<a href="#" class="css3_grid_more" title="more"></a>
									<span style="float: left;margin-top: 2px;" class="description">Number of rows.</span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="hiddenRows">Hidden rows</label>
								</th>
								<td>
									<input style="float: left;" type="text" readonly="readonly" class="regular-text" value="0" id="hiddenRows" name="hiddenRows" maxlength="2">
									<a href="#" class="css3_grid_less css3_grid_to_zero" title="less"></a>
									<a href="#" class="css3_grid_more" title="more"></a>
									<span style="float: left;margin-top: 2px;" class="description">Number of hidden rows<br />at the bottom (for long tables).</span>
								</td>
							</tr>
							<tr valign="top" class="css3_hidden_rows_row">
								<th scope="row">
									<label for="hiddenRowsButtonExpandText">Hidden rows button expand text</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="Click here to expand!" id="hiddenRowsButtonExpandText" name="hiddenRowsButtonExpandText">
								</td>
							</tr>
							<tr valign="top" class="css3_hidden_rows_row">
								<th scope="row">
									<label for="hiddenRowsButtonCollapseText">Hidden rows button collapse text</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="Click here to collapse!" id="hiddenRowsButtonCollapseText" name="hiddenRowsButtonCollapseText">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-responsive">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="responsive">Responsive</label>
								</th>
								<td>
									<select name="responsive" id="responsive">
										<option value="0">no</option>
										<option value="1">yes</option>
									</select>
									<span class="description">Enable or disable responsive feature (fit for different resolutions).</span>
								</td>
							</tr>
							<tr valign="top" class="responsiveStepsRow">
								<th scope="row">
									<label for="responsiveSteps">Responsive steps (sizes)</label>
								</th>
								<td>
									<input style="float: left;" type="text" readonly="readonly" class="regular-text" value="3" id="responsiveSteps" name="responsiveSteps" maxlength="2">
									<a href="#" class="css3_grid_less" title="less"></a>
									<a href="#" class="css3_grid_more" title="more"></a>
								</td>
							</tr>
							<tr valign="top" class="responsiveStepRow responsiveStepRow1">
								<th scope="row">
									<label>Screen width 1</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="1009" name="responsiveStepWidth[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveStepRow responsiveStepRow2">
								<th scope="row">
									<label>Screen width 2</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="767" name="responsiveStepWidth[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveStepRow responsiveStepRow3">
								<th scope="row">
									<label>Screen width 3</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="479" name="responsiveStepWidth[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveButtonWidthRow responsiveButtonWidthRow1">
								<th scope="row">
									<label>Responsive button width 1</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveButtonWidth[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveButtonWidthRow responsiveButtonWidthRow2">
								<th scope="row">
									<label>Responsive button width 2</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveButtonWidth[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveButtonWidthRow responsiveButtonWidthRow3">
								<th scope="row">
									<label>Responsive button width 3</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveButtonWidth[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveHideCaptionColumnRow">
								<th scope="row">
									<label for="responsiveHideCaptionColumn">Hide caption (first) column on small resolutions</label>
								</th>
								<td>
									<select name="responsiveHideCaptionColumn" id="responsiveHideCaptionColumn">
										<option value="0">no</option>
										<option value="1">yes</option>
									</select>
									<span class="description">If set to 'yes' you can adjust screen width value in responsive.css file (line 5).</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-fonts">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label class="css3_grid_bold">
										Header font
									</label>
								</th>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="headerFontCustom">Enter font name</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="headerFontCustom" name="headerFontCustom">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="headerFont">or choose Google font</label>
								</th>
								<td>
									<select name="headerFont" id="headerFont" class="google_font_chooser">
										<option value=""><?php _e("Default", 'css3_grid'); ?></option>
										<?php
											echo $fontsHtml;
										?>
									</select>
									<span id="ajax_loader_header_font" style="display: none;"><img style="margin-bottom: -3px;" src="<?php echo WP_PLUGIN_URL; ?>/css3_web_pricing_tables_grids/img/ajax-loader.gif" /></span>
								</td>
							</tr>
							<tr valign="top" class="fontSubsetRow">
								<th scope="row">
									<label for="headerFontSubset">Google font subset</label>
								</th>
								<td>
									<select name="headerFontSubset[]" id="headerFontSubset" class="fontSubset" multiple="multiple"></select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="headerFontSize">Font size (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="headerFontSize" name="headerFontSize">
								</td>
							</tr>
							<tr valign="top" class="responsiveHeaderFontSizeRow responsiveHeaderFontSizeRow1">
								<th scope="row">
									<label>Responsive font size 1 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveHeaderFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveHeaderFontSizeRow responsiveHeaderFontSizeRow2">
								<th scope="row">
									<label>Responsive font size 2 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveHeaderFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveHeaderFontSizeRow responsiveHeaderFontSizeRow3">
								<th scope="row">
									<label>Responsive font size 3 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveHeaderFontSize[]">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label class="css3_grid_bold">
										Price font
									</label>
								</th>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="priceFontCustom">Enter font name</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="priceFontCustom" name="priceFontCustom">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="priceFont">or choose Google font</label>
								</th>
								<td>
									<select name="priceFont" id="priceFont" class="google_font_chooser">
										<option value=""><?php _e("Default", 'css3_grid'); ?></option>
										<?php
											echo $fontsHtml;
										?>
									</select>
									<span id="ajax_loader_price_font" style="display: none;"><img style="margin-bottom: -3px;" src="<?php echo WP_PLUGIN_URL; ?>/css3_web_pricing_tables_grids/img/ajax-loader.gif" /></span>
								</td>
							</tr>
							<tr valign="top" class="fontSubsetRow">
								<th scope="row">
									<label for="priceFontSubset">Google font subset</label>
								</th>
								<td>
									<select name="priceFontSubset[]" id="priceFontSubset" class="fontSubset" multiple="multiple"></select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="priceFontSize">Font size (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="priceFontSize" name="priceFontSize">
								</td>
							</tr>
							<tr valign="top" class="responsivePriceFontSizeRow responsivePriceFontSizeRow1">
								<th scope="row">
									<label>Responsive font size 1 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsivePriceFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsivePriceFontSizeRow responsivePriceFontSizeRow2">
								<th scope="row">
									<label>Responsive font size 2 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsivePriceFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsivePriceFontSizeRow responsivePriceFontSizeRow3">
								<th scope="row">
									<label>Responsive font size 3 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsivePriceFontSize[]">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label class="css3_grid_bold">
										Per month font
									</label>
								</th>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="permonthFontCustom">Enter font name</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="permonthFontCustom" name="permonthFontCustom">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="permonthFont">or choose Google font</label>
								</th>
								<td>
									<select name="permonthFont" id="permonthFont" class="google_font_chooser">
										<option value=""><?php _e("Default", 'css3_grid'); ?></option>
										<?php
											echo $fontsHtml;
										?>
									</select>
									<span id="ajax_loader_header_font" style="display: none;"><img style="margin-bottom: -3px;" src="<?php echo WP_PLUGIN_URL; ?>/css3_web_pricing_tables_grids/img/ajax-loader.gif" /></span>
								</td>
							</tr>
							<tr valign="top" class="fontSubsetRow">
								<th scope="row">
									<label for="permonthFontSubset">Google font subset</label>
								</th>
								<td>
									<select name="permonthFontSubset[]" id="permonthFontSubset" class="fontSubset" multiple="multiple"></select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="permonthFontSize">Font size (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="permonthFontSize" name="permonthFontSize">
								</td>
							</tr>
							<tr valign="top" class="responsivePermonthFontSizeRow responsivePermonthFontSizeRow1">
								<th scope="row">
									<label>Responsive font size 1 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsivePermonthFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsivePermonthFontSizeRow responsivePermonthFontSizeRow2">
								<th scope="row">
									<label>Responsive font size 2 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsivePermonthFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsivePermonthFontSizeRow responsivePermonthFontSizeRow3">
								<th scope="row">
									<label>Responsive font size 3 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsivePermonthFontSize[]">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label class="css3_grid_bold">
										Content font
									</label>
								</th>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="contentFontCustom">Enter font name</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="contentFontCustom" name="contentFontCustom">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="contentFont">or choose Google font</label>
								</th>
								<td>
									<select name="contentFont" id="contentFont" class="google_font_chooser">
										<option value=""><?php _e("Default", 'css3_grid'); ?></option>
										<?php
											echo $fontsHtml;
										?>
									</select>
									<span id="ajax_loader_header_font" style="display: none;"><img style="margin-bottom: -3px;" src="<?php echo WP_PLUGIN_URL; ?>/css3_web_pricing_tables_grids/img/ajax-loader.gif" /></span>
								</td>
							</tr>
							<tr valign="top" class="fontSubsetRow">
								<th scope="row">
									<label for="contentFontSubset">Google font subset</label>
								</th>
								<td>
									<select name="contentFontSubset[]" id="contentFontSubset" class="fontSubset" multiple="multiple"></select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="contentFontSize">Font size (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="contentFontSize" name="contentFontSize">
								</td>
							</tr>
							<tr valign="top" class="responsiveContentFontSizeRow responsiveContentFontSizeRow1">
								<th scope="row">
									<label>Responsive font size 1 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveContentFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveContentFontSizeRow responsiveContentFontSizeRow2">
								<th scope="row">
									<label>Responsive font size 2 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveContentFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveContentFontSizeRow responsiveContentFontSizeRow3">
								<th scope="row">
									<label>Responsive font size 3 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveContentFontSize[]">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label class="css3_grid_bold">
										Buttons font
									</label>
								</th>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="buttonsFontCustom">Enter font name</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="buttonsFontCustom" name="buttonsFontCustom">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="buttonsFont">or choose Google font</label>
								</th>
								<td>
									<select name="buttonsFont" id="buttonsFont" class="google_font_chooser">
										<option value=""><?php _e("Default", 'css3_grid'); ?></option>
										<?php
											echo $fontsHtml;
										?>
									</select>
									<span id="ajax_loader_header_font" style="display: none;"><img style="margin-bottom: -3px;" src="<?php echo WP_PLUGIN_URL; ?>/css3_web_pricing_tables_grids/img/ajax-loader.gif" /></span>
								</td>
							</tr>
							<tr valign="top" class="fontSubsetRow">
								<th scope="row">
									<label for="buttonsFontSubset">Google font subset</label>
								</th>
								<td>
									<select name="buttonsFontSubset[]" id="buttonsFontSubset" class="fontSubset" multiple="multiple"></select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="buttonsFontSize">Font size (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="buttonsFontSize" name="buttonsFontSize">
								</td>
							</tr>
							<tr valign="top" class="responsiveButtonsFontSizeRow responsiveButtonsFontSizeRow1">
								<th scope="row">
									<label>Responsive font size 1 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveButtonsFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveButtonsFontSizeRow responsiveButtonsFontSizeRow2">
								<th scope="row">
									<label>Responsive font size 2 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveButtonsFontSize[]">
								</td>
							</tr>
							<tr valign="top" class="responsiveButtonsFontSizeRow responsiveButtonsFontSizeRow3">
								<th scope="row">
									<label>Responsive font size 3 (in px)</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" name="responsiveButtonsFontSize[]">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-sliding">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
									<label for="slidingColumns">Sliding columns</label>
								</th>
								<td>
									<select name="slidingColumns" id="slidingColumns">
										<option value="0">no</option>
										<option value="1">yes</option>
									</select>
									<span class="description">Enable or disable sliding for columns (left/right moving).</span>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="visibleColumns">Visible columns</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="1" id="visibleColumns" name="visibleColumns" maxlength="2">
									<span class="description">Number of visible columns at start, when sliding columns feature is enabled.</span>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="scrollColumns">Columns to scroll</label>
								</th>
								<td>
									<input type="text" class="regular-text" value="" id="scrollColumns" name="scrollColumns" maxlength="2">
									<span class="description">The number of columns to scroll. When empty the 'Visible columns' value is used.</span>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingNavigation">Navigation</label>
								</th>
								<td>
									<select name="slidingNavigation" id="slidingNavigation">
										<option value="1">yes</option>
										<option value="0">no</option>
									</select>
									<span class="description">Enable or disable sliding navigation.</span>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row css3_sliding_navigation_row">
								<th scope="row">
									<label for="slidingNavigationArrows">Navigation arrows</label>
								</th>
								<td>
									<select name="slidingNavigationArrows" id="slidingNavigationArrows">
										<option value="1">yes</option>
										<option value="0">no</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row css3_sliding_navigation_row css3_sliding_arrows_row">
								<th scope="row">
									<label for="slidingArrowsStyle">Arrows style</label>
								</th>
								<td>
									<select name="slidingArrowsStyle" id="slidingArrowsStyle">
										<option value="style1">style 1</option>
										<option value="style2">style 2</option>
										<option value="style3">style 3</option>
										<option value="style4">style 4</option>
										<option value="style5">style 5</option>
										<option value="style6">style 6</option>
										<option value="style7">style 7</option>
										<option value="style8">style 8</option>
										<option value="style9">style 9</option>
										<option value="style10">style 10</option>
									</select>
									<span class='css3_grid_tooltip css3_grid_admin_info css3_grid_tooltip_arrows'>
										<span>
											<div class='css3_grid_tooltip_column'>
												<strong>style 1</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style1'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style1'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 2</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style2'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style2'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 3</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style3'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style3'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 4</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style4'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style4'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 5</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style5'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style5'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 6</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style6'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style6'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 7</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style7'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style7'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 8</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style8'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style8'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 9</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style9'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style9'></a>
											</div>
											<div class='css3_grid_tooltip_column'>
												<strong>style 10</strong>
												<a href='#' class='css3_grid_slide_button_prev css3_grid_slide_button_style10'></a>
												<a href='#' class='css3_grid_slide_button_next css3_grid_slide_button_style10'></a>
											</div>
										</span>
									</span>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row css3_sliding_navigation_row">
								<th scope="row">
									<label for="slidingPagination">Navigation pagination</label>
								</th>
								<td>
									<select name="slidingPagination" id="slidingPagination">
										<option value="0">no</option>
										<option value="1">yes</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row css3_sliding_navigation_row css3_sliding_pagination_row">
								<th scope="row">
									<label for="slidingPaginationStyle">Pagination style</label>
								</th>
								<td>
									<select name="slidingPaginationStyle" id="slidingPaginationStyle">
										<option value="style1">style 1</option>
										<option value="style2">style 2</option>
										<option value="style3">style 3</option>
									</select>
									<span class='css3_grid_tooltip css3_grid_admin_info css3_grid_tooltip_pagination'>
										<span>
											<div class='css3_grid_tooltip_column css3_grid_pagination css3_grid_pagination_style1'>
												<strong>style 1</strong>
												<a href='#' class="selected"></a>
												<a href='#'></a>
												<a href='#'></a>
											</div>
											<div class='css3_grid_tooltip_column css3_grid_pagination css3_grid_pagination_style2'>
												<strong>style 2</strong>
												<a href='#' class="selected"></a>
												<a href='#'></a>
												<a href='#'></a>
											</div>
											<div class='css3_grid_tooltip_column css3_grid_pagination css3_grid_pagination_style3'>
												<strong>style 3</strong>
												<a href='#' class="selected"></a>
												<a href='#'></a>
												<a href='#'></a>
											</div>
										</span>
									</span>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row css3_sliding_navigation_row css3_sliding_pagination_row">
								<th scope="row">
									<label for="slidingPaginationPosition">Pagination position</label>
								</th>
								<td>
									<select name="slidingPaginationPosition" id="slidingPaginationPosition">
										<option value="bottom">bottom</option>
										<option value="top">top</option>
										<option value="both">both</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingOnTouch">Slide on touch</label>
								</th>
								<td>	
									<select id="slidingOnTouch" name="slidingOnTouch">
										<option value="1">yes</option>
										<option value="0">no</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingOnMouse">Slide on mouse</label>
								</th>
								<td>	
									<select id="slidingOnMouse" name="slidingOnMouse">
										<option value="0">no</option>
										<option value="1">yes</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingThreshold">Slide threshold</label>
								</th>
								<td>	
									<input type="text" class="regular-text" value="75" id="slidingThreshold" name="slidingThreshold">
									<span class="description">The number of pixels that the user must move their finger before it is considered a swipe.</span>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingAutoplay">Sliding autoplay</label>
								</th>
								<td>	
									<select id="slidingAutoplay" name="slidingAutoplay">
										<option value="0">no</option>
										<option value="1">yes</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingEffect">Sliding effect</label>
								</th>
								<td>	
									<select id="slidingEffect" name="slidingEffect">
										<option value="scroll">scroll</option>
										<option value="none">none</option>
										<option value="directscroll">directscroll</option>
										<option value="fade">fade</option>
										<option value="crossfade">crossfade</option>
										<option value="cover">cover</option>
										<option value="uncover">uncover</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingEasing">Sliding easing</label>
								</th>
								<td>	
									<select id="slidingEasing" name="slidingEasing">
										<option value="swing">swing</option>
										<option value="linear">linear</option>
										<option value="easeInQuad">easeInQuad</option>
										<option value="easeOutQuad">easeOutQuad</option>
										<option value="easeInOutQuad">easeInOutQuad</option>
										<option value="easeInCubic">easeInCubic</option>
										<option value="easeOutCubic">easeOutCubic</option>
										<option value="easeInOutCubic">easeInOutCubic</option>
										<option value="easeInOutCubic">easeInOutCubic</option>
										<option value="easeInQuart">easeInQuart</option>
										<option value="easeOutQuart">easeOutQuart</option>
										<option value="easeInOutQuart">easeInOutQuart</option>
										<option value="easeInSine">easeInSine</option>
										<option value="easeOutSine">easeOutSine</option>
										<option value="easeInOutSine">easeInOutSine</option>
										<option value="easeInExpo">easeInExpo</option>
										<option value="easeOutExpo">easeOutExpo</option>
										<option value="easeInOutExpo">easeInOutExpo</option>
										<option value="easeInQuint">easeInQuint</option>
										<option value="easeOutQuint">easeOutQuint</option>
										<option value="easeInOutQuint">easeInOutQuint</option>
										<option value="easeInCirc">easeInCirc</option>
										<option value="easeOutCirc">easeOutCirc</option>
										<option value="easeInOutCirc">easeInOutCirc</option>
										<option value="easeInElastic">easeInElastic</option>
										<option value="easeOutElastic">easeOutElastic</option>
										<option value="easeInOutElastic">easeInOutElastic</option>
										<option value="easeInBack">easeInBack</option>
										<option value="easeOutBack">easeOutBack</option>
										<option value="easeInOutBack">easeInOutBack</option>
										<option value="easeInBounce">easeInBounce</option>
										<option value="easeOutBounce">easeOutBounce</option>
										<option value="easeInOutBounce">easeInOutBounce</option>
									</select>
								</td>
							</tr>
							<tr valign="top" class="css3_sliding_row">
								<th scope="row">
									<label for="slidingDuration">Sliding transition speed (ms)</label>
								</th>
								<td>	
									<input type="text" class="regular-text" value="500" id="slidingDuration" name="slidingDuration">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div id="tab-import-export">
					<table class="form-table">
						<tbody>
							<?php if(count($css3GridAllShortcodeIds)): ?>
							<tr valign="top">
								<th scope="row">
									<label class="css3_grid_bold">
										Export
									</label>
								</th>
							</tr>
							<tr valign="top">
								<th scope="row" style="vertical-align: middle;">
									<label for="exportIds">Choose tables for export</label>
								</th>
								<td>
									<select name="exportIds[]" id="exportIds" multiple="multiple" style="height: 250px;">
										<?php
											for($i=0; $i<count($css3GridAllShortcodeIds); $i++)
												echo "<option value='$css3GridAllShortcodeIds[$i]' selected='selected'>" . substr($css3GridAllShortcodeIds[$i], 29) . "</option>";
										?>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<td colspan="2">	
									<a href="<?php echo plugins_url("export.php", __FILE__); ?>?action=export_to_file" id="export_to_file" class="button-primary">Export to file</a>
								</td>
							</tr>
							<?php endif; ?>
							<tr valign="top">
								<th scope="row">
									<label class="css3_grid_bold">
										Import
									</label>
								</th>
							</tr>
							<tr valign="top">
								<td colspan="2">	
									<input type="file" id="import_from_file_input" name="import_from_file_input">
								</td>
							</tr>
							<tr valign="top">
								<td colspan="2">	
									<input type="submit" id="import_from_file" value="Import from file" class="button-primary" name="Submit">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="textsTable">
				<table class="widefat css3_grid_widefat">
				<thead>
					<tr>
						<th class="css3_grid_admin_column1">
							<div class="css3_grid_column1_text">
								Rows configuration
							</div>
						</th>
						<th class="css3_grid_admin_column2">
							<div class="css3_grid_sort_column css3_clearfix">
								<div class="css3_grid_arrows">
									<a href="#" class="css3_grid_sort_left" title="left"></a>
									<a href="#" class="css3_grid_sort_right" title="right"></a>
								</div>
							</div>
							Column 1
							<br />
							<label>width (optional): </label><input type="text" name="widths[]" value="" />
							<div class="css3_responsive_width_container">
								<label class="css3_responsive_width css3_responsive_width1">responsive width 1 (optional)</label><input class="css3_responsive_width css3_responsive_width1" type="text" name="responsiveWidths[]" value="" />
								<br class="css3_responsive_width css3_responsive_width2">
								<label class="css3_responsive_width css3_responsive_width2">responsive width 2 (optional)</label><input class="css3_responsive_width css3_responsive_width2" type="text" name="responsiveWidths[]" value="" />
								<br class="css3_responsive_width css3_responsive_width3">
								<label class="css3_responsive_width css3_responsive_width3">responsive width 3 (optional)</label><input class="css3_responsive_width css3_responsive_width3" type="text" name="responsiveWidths[]" value="" />
							</div>
							<label>aligment (optional): </label>
							<select name="aligments[]">
								<option value="-1">choose...</option>
								<option value="left">left</option>
								<option value="center">center</option>
								<option value="right">right</option>
							</select>
							<br class="css3_active_column_br" />
							<label class="css3_active_column_label">active (optional): </label>
							<select name="actives[]" class="css3_active_column_select">
								<option value="-1">no</option>
								<option value="1">yes</option>
							</select>
							<br />
							<label>disable/hidden (optional): </label>
							<select name="hiddens[]">
								<option value="-1">no</option>
								<option value="1">yes</option>
							</select>
							<br />
							<label>ribbon (optional): </label>
							<select name="ribbons[]">
								<option value="-1">choose...</option>
								<optgroup label="Style 1">
									<option value="style1_best">best</option>
									<option value="style1_buy">buy</option>
									<option value="style1_free">free</option>
									<option value="style1_free_caps">free (uppercase)</option>
									<option value="style1_fresh">fresh</option>
									<option value="style1_gift_caps">gift (uppercase)</option>
									<option value="style1_heart">heart</option>
									<option value="style1_hot">hot</option>
									<option value="style1_hot_caps">hot (uppercase)</option>
									<option value="style1_new">new</option>
									<option value="style1_new_caps">new (uppercase)</option>
									<option value="style1_no1">no. 1</option>
									<option value="style1_off5">5% off</option>
									<option value="style1_off10">10% off</option>
									<option value="style1_off15">15% off</option>
									<option value="style1_off20">20% off</option>
									<option value="style1_off25">25% off</option>
									<option value="style1_off30">30% off</option>
									<option value="style1_off35">35% off</option>
									<option value="style1_off40">40% off</option>
									<option value="style1_off50">50% off</option>
									<option value="style1_off75">75% off</option>
									<option value="style1_pack">pack</option>
									<option value="style1_pro">pro</option>
									<option value="style1_sale">sale</option>
									<option value="style1_save">save</option>
									<option value="style1_save_caps">save (uppercase)</option>
									<option value="style1_top">top</option>
									<option value="style1_top_caps">top (uppercase)</option>
									<option value="style1_trial">trial</option>
								</optgroup>
								<optgroup label="Style 2">
									<option value="style2_best">best</option>
									<option value="style2_buy">buy</option>
									<option value="style2_free">free</option>
									<option value="style2_free_caps">free (uppercase)</option>
									<option value="style2_fresh">fresh</option>
									<option value="style2_gift_caps">gift (uppercase)</option>
									<option value="style2_heart">heart</option>
									<option value="style2_hot">hot</option>
									<option value="style2_hot_caps">hot (uppercase)</option>
									<option value="style2_new">new</option>
									<option value="style2_new_caps">new (uppercase)</option>
									<option value="style2_no1">no. 1</option>
									<option value="style2_off5">5% off</option>
									<option value="style2_off10">10% off</option>
									<option value="style2_off15">15% off</option>
									<option value="style2_off20">20% off</option>
									<option value="style2_off25">25% off</option>
									<option value="style2_off30">30% off</option>
									<option value="style2_off35">35% off</option>
									<option value="style2_off40">40% off</option>
									<option value="style2_off50">50% off</option>
									<option value="style2_off75">75% off</option>
									<option value="style2_pack">pack</option>
									<option value="style2_pro">pro</option>
									<option value="style2_sale">sale</option>
									<option value="style2_save">save</option>
									<option value="style2_save_caps">save (uppercase)</option>
									<option value="style2_top">top</option>
									<option value="style2_top_caps">top (uppercase)</option>
									<option value="style2_trial">trial</option>
								</optgroup>
							</select>
						</th>
						<th class="css3_grid_admin_column3">
							<div class="css3_grid_sort_column css3_clearfix">
								<div class="css3_grid_arrows">
									<a href="#" class="css3_grid_sort_left" title="left"></a>
									<a href="#" class="css3_grid_sort_right" title="right"></a>
								</div>
							</div>
							Column 2
							<br />
							<label>width (optional): </label><input type="text" name="widths[]" value="" />
							<div class="css3_responsive_width_container">
								<label class="css3_responsive_width css3_responsive_width1">responsive width 1 (optional)</label><input class="css3_responsive_width css3_responsive_width1" type="text" name="responsiveWidths[]" value="" />
								<br class="css3_responsive_width css3_responsive_width2">
								<label class="css3_responsive_width css3_responsive_width2">responsive width 2 (optional)</label><input class="css3_responsive_width css3_responsive_width2" type="text" name="responsiveWidths[]" value="" />
								<br class="css3_responsive_width css3_responsive_width3">
								<label class="css3_responsive_width css3_responsive_width3">responsive width 3 (optional)</label><input class="css3_responsive_width css3_responsive_width3" type="text" name="responsiveWidths[]" value="" />
							</div>
							<label>aligment (optional): </label>
							<select name="aligments[]">
								<option value="-1">choose...</option>
								<option value="left">left</option>
								<option value="center">center</option>
								<option value="right">right</option>
							</select>
							<br class="css3_active_column_br" />
							<label class="css3_active_column_label">active (optional): </label>
							<select name="actives[]" class="css3_active_column_select">
								<option value="-1">no</option>
								<option value="1">yes</option>
							</select>
							<br />
							<label>disable/hidden (optional): </label>
							<select name="hiddens[]">
								<option value="-1">no</option>
								<option value="1">yes</option>
							</select>
							<br />
							<label>ribbon (optional): </label>
							<select name="ribbons[]">
								<option value="-1">choose...</option>
								<optgroup label="Style 1">
									<option value="style1_best">best</option>
									<option value="style1_buy">buy</option>
									<option value="style1_free">free</option>
									<option value="style1_free_caps">free (uppercase)</option>
									<option value="style1_fresh">fresh</option>
									<option value="style1_gift_caps">gift (uppercase)</option>
									<option value="style1_heart">heart</option>
									<option value="style1_hot">hot</option>
									<option value="style1_hot_caps">hot (uppercase)</option>
									<option value="style1_new">new</option>
									<option value="style1_new_caps">new (uppercase)</option>
									<option value="style1_no1">no. 1</option>
									<option value="style1_off5">5% off</option>
									<option value="style1_off10">10% off</option>
									<option value="style1_off15">15% off</option>
									<option value="style1_off20">20% off</option>
									<option value="style1_off25">25% off</option>
									<option value="style1_off30">30% off</option>
									<option value="style1_off35">35% off</option>
									<option value="style1_off40">40% off</option>
									<option value="style1_off50">50% off</option>
									<option value="style1_off75">75% off</option>
									<option value="style1_pack">pack</option>
									<option value="style1_pro">pro</option>
									<option value="style1_sale">sale</option>
									<option value="style1_save">save</option>
									<option value="style1_save_caps">save (uppercase)</option>
									<option value="style1_top">top</option>
									<option value="style1_top_caps">top (uppercase)</option>
									<option value="style1_trial">trial</option>
								</optgroup>
								<optgroup label="Style 2">
									<option value="style2_best">best</option>
									<option value="style2_buy">buy</option>
									<option value="style2_free">free</option>
									<option value="style2_free_caps">free (uppercase)</option>
									<option value="style2_fresh">fresh</option>
									<option value="style2_gift_caps">gift (uppercase)</option>
									<option value="style2_heart">heart</option>
									<option value="style2_hot">hot</option>
									<option value="style2_hot_caps">hot (uppercase)</option>
									<option value="style2_new">new</option>
									<option value="style2_new_caps">new (uppercase)</option>
									<option value="style2_no1">no. 1</option>
									<option value="style2_off5">5% off</option>
									<option value="style2_off10">10% off</option>
									<option value="style2_off15">15% off</option>
									<option value="style2_off20">20% off</option>
									<option value="style2_off25">25% off</option>
									<option value="style2_off30">30% off</option>
									<option value="style2_off35">35% off</option>
									<option value="style2_off40">40% off</option>
									<option value="style2_off50">50% off</option>
									<option value="style2_off75">75% off</option>
									<option value="style2_pack">pack</option>
									<option value="style2_pro">pro</option>
									<option value="style2_sale">sale</option>
									<option value="style2_save">save</option>
									<option value="style2_save_caps">save (uppercase)</option>
									<option value="style2_top">top</option>
									<option value="style2_top_caps">top (uppercase)</option>
									<option value="style2_trial">trial</option>
								</optgroup>
							</select>
						</th>
						<th class="css3_grid_admin_column4">
							<div class="css3_grid_sort_column css3_clearfix">
								<div class="css3_grid_arrows">
									<a href="#" class="css3_grid_sort_left" title="left"></a>
									<a href="#" class="css3_grid_sort_right" title="right"></a>
								</div>
							</div>
							Column 3
							<br />
							<label>width (optional): </label><input type="text" name="widths[]" value="" />
							<div class="css3_responsive_width_container">
								<label class="css3_responsive_width css3_responsive_width1">responsive width 1 (optional)</label><input class="css3_responsive_width css3_responsive_width1" type="text" name="responsiveWidths[]" value="" />
								<br class="css3_responsive_width css3_responsive_width2">
								<label class="css3_responsive_width css3_responsive_width2">responsive width 2 (optional)</label><input class="css3_responsive_width css3_responsive_width2" type="text" name="responsiveWidths[]" value="" />
								<br class="css3_responsive_width css3_responsive_width3">
								<label class="css3_responsive_width css3_responsive_width3">responsive width 3 (optional)</label><input class="css3_responsive_width css3_responsive_width3" type="text" name="responsiveWidths[]" value="" />
							</div>
							<label>aligment (optional): </label>
							<select name="aligments[]">
								<option value="-1">choose...</option>
								<option value="left">left</option>
								<option value="center">center</option>
								<option value="right">right</option>
							</select>
							<br class="css3_active_column_br" />
							<label class="css3_active_column_label">active (optional): </label>
							<select name="actives[]" class="css3_active_column_select">
								<option value="-1">no</option>
								<option value="1">yes</option>
							</select>
							<br />
							<label>disable/hidden (optional): </label>
							<select name="hiddens[]">
								<option value="-1">no</option>
								<option value="1">yes</option>
							</select>
							<br />
							<label>ribbon (optional): </label>
							<select name="ribbons[]">
								<option value="-1">choose...</option>
								<optgroup label="Style 1">
									<option value="style1_best">best</option>
									<option value="style1_buy">buy</option>
									<option value="style1_free">free</option>
									<option value="style1_free_caps">free (uppercase)</option>
									<option value="style1_fresh">fresh</option>
									<option value="style1_gift_caps">gift (uppercase)</option>
									<option value="style1_heart">heart</option>
									<option value="style1_hot">hot</option>
									<option value="style1_hot_caps">hot (uppercase)</option>
									<option value="style1_new">new</option>
									<option value="style1_new_caps">new (uppercase)</option>
									<option value="style1_no1">no. 1</option>
									<option value="style1_off5">5% off</option>
									<option value="style1_off10">10% off</option>
									<option value="style1_off15">15% off</option>
									<option value="style1_off20">20% off</option>
									<option value="style1_off25">25% off</option>
									<option value="style1_off30">30% off</option>
									<option value="style1_off35">35% off</option>
									<option value="style1_off40">40% off</option>
									<option value="style1_off50">50% off</option>
									<option value="style1_off75">75% off</option>
									<option value="style1_pack">pack</option>
									<option value="style1_pro">pro</option>
									<option value="style1_sale">sale</option>
									<option value="style1_save">save</option>
									<option value="style1_save_caps">save (uppercase)</option>
									<option value="style1_top">top</option>
									<option value="style1_top_caps">top (uppercase)</option>
									<option value="style1_trial">trial</option>
								</optgroup>
								<optgroup label="Style 2">
									<option value="style2_best">best</option>
									<option value="style2_buy">buy</option>
									<option value="style2_free">free</option>
									<option value="style2_free_caps">free (uppercase)</option>
									<option value="style2_fresh">fresh</option>
									<option value="style2_gift_caps">gift (uppercase)</option>
									<option value="style2_heart">heart</option>
									<option value="style2_hot">hot</option>
									<option value="style2_hot_caps">hot (uppercase)</option>
									<option value="style2_new">new</option>
									<option value="style2_new_caps">new (uppercase)</option>
									<option value="style2_no1">no. 1</option>
									<option value="style2_off5">5% off</option>
									<option value="style2_off10">10% off</option>
									<option value="style2_off15">15% off</option>
									<option value="style2_off20">20% off</option>
									<option value="style2_off25">25% off</option>
									<option value="style2_off30">30% off</option>
									<option value="style2_off35">35% off</option>
									<option value="style2_off40">40% off</option>
									<option value="style2_off50">50% off</option>
									<option value="style2_off75">75% off</option>
									<option value="style2_pack">pack</option>
									<option value="style2_pro">pro</option>
									<option value="style2_sale">sale</option>
									<option value="style2_save">save</option>
									<option value="style2_save_caps">save (uppercase)</option>
									<option value="style2_top">top</option>
									<option value="style2_top_caps">top (uppercase)</option>
									<option value="style2_trial">trial</option>
								</optgroup>
							</select>
						</th>
					</tr>
				</thead>
				<tbody>
				<tr class="css3_grid_admin_row1">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="&lt;h2 class='col1'&gt;starter&lt;/h2&gt;" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="&lt;h2 class='col2'&gt;econo&lt;/h2&gt;" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row2">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="&lt;h2 class='caption'&gt;choose &lt;span&gt;your&lt;/span&gt; plan&lt;/h2&gt;" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="&lt;h1 class='col1'&gt;$&lt;span&gt;10&lt;/span&gt;&lt;/h1&gt;&lt;h3 class='col1'&gt;per month&lt;/h3&gt;" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="&lt;h1 class='col1'&gt;$&lt;span&gt;30&lt;/span&gt;&lt;/h1&gt;&lt;h3 class='col1'&gt;per month&lt;/h3&gt;" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row3">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="Amount of space" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="10GB" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="30GB" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row4">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="Bandwidth per month" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="100GB" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="200GB" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row5">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="No. of e-mail accounts" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="1" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="10" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row6">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="No. of MySql databases" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="1" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="10" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row7">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="24h support" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="Yes" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="Yes" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row8">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="Support tickets per mo." />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="1" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="3" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				<tr class="css3_grid_admin_row9">
					<td class="css3_grid_admin_column1">
						<div class="css3_grid_arrows_row">
							<a href="#" class="css3_grid_sort_up" title="up"></a>
							<a href="#" class="css3_grid_sort_down" title="down"></a>
						</div>
						<div class="css3_grid_row_config">
							<input class="css3_grid_short" type="text" name="heights[]" value="" /><label>height (optional in px)</label>
							<div class="css3_responsive_height_container">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height1" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height1">responsive height 1 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height2">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height2" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height2">responsive height 2 (optional)</label>
								<br class="css3_responsive_height css3_responsive_height3">
								<input class="css3_grid_short css3_responsive_height css3_responsive_height3" type="text" name="responsiveHeights[]" value="" /><label class="css3_responsive_height css3_responsive_height3">responsive height 3 (optional)</label>
							</div>
							<input class="css3_grid_short" type="text" name="paddingsTop[]" value="" /><label>padding top (optional in px)</label>
							<input class="css3_grid_short" type="text" name="paddingsBottom[]" value="" /><label>padding bottom (optional in px)</label>
						</div>
					</td>
					<td class="css3_grid_admin_column2">
						<input type="text" name="texts[]" value="" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column3">
						<input type="text" name="texts[]" value="&lt;a href='<?php echo get_site_url(); ?>?plan=1' class='sign_up radius3'&gt;sign up!&lt;/a&gt;" />
						<?php echo $shortcodesSelect;?>
					</td>
					<td class="css3_grid_admin_column4">
						<input type="text" name="texts[]" value="&lt;a href='<?php echo get_site_url(); ?>?plan=2' class='sign_up radius3'&gt;sign up!&lt;/a&gt;" />
						<?php echo $shortcodesSelect;?>
					</td>
				</tr>
				</tbody>
				</table>
			</div>
			<p>
				<input type="button" id="preview" value="Preview" class="button-primary" name="Preview">
				<input type="submit" id="save_css3_grid_1" value="Save Options" class="button-primary" name="Submit">
			</p>
			<div id="previewContainer">
			<?php
			echo do_shortcode("[css3_grid_print]");
			?>
			</div>
			<p>
				<input type="hidden" name="action" value="save_css3_grid" />
				<input type="submit" id="save_css3_grid_2" value="Save Options" class="button-primary" name="Submit">
			</p>
		</form>
		<?php
		$message = "";
		if(isset($_POST["action"]) && $_POST["action"]=="save_css3_global_settings")
		{
			$css3_grid_global_options = array(
				'loadFiles' => $_POST['loadFiles']
			);
			update_option('css3_grid_global_settings', $css3_grid_global_options);
			$message .= "Settings saved!";
		}
		?>
		<br />
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2>CSS3 Web Pricing Tables Grids global settings</h2>
		</div>
		<?php
		if($message!="")
		{
		?>
		<div class="<?php echo ($message!="" ? "updated" : "error"); ?> settings-error"> 
			<p style="line-height: 150%;font-weight: bold;">
				<?php echo ($message!="" ? $message : $error); ?>
			</p>
		</div>
		<?php
		}
		$css3_grid_global_options = (array)get_option('css3_grid_global_settings');
		?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="css3_grid_global_settings">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="loadFiles">Load plugin files</label>
						</th>
						<td>
							<select name="loadFiles" id="loadFiles">
								<option value="always"<?php echo (isset($css3_grid_global_options['loadFiles']) && $css3_grid_global_options['loadFiles']=='always' ? ' selected="selected"' : ''); ?>>on every page</option>
								<option value="when_used"<?php echo (isset($css3_grid_global_options['loadFiles']) && $css3_grid_global_options['loadFiles']=='when_used' ? ' selected="selected"' : ''); ?>>only when used</option>
							</select>
							<span class="description">If you see unstyled table on your page when using 'only when used' option, please set 'on every page' as some themes may not be compatibile with 'only when used' option</span>
						</td>
					</tr>
				</tbody>
			</table>
			<p>
				<input type="hidden" name="action" value="save_css3_global_settings" />
				<input type="submit" id="save_css3_grid_global" value="Save Settings" class="button-primary" name="Submit">
			</p>
		</form>
		<?php
	}
}

//activate plugin
function css3_grid_activate()
{
	$table_t1_s1 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '1','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn'=>'1','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => '2','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style7','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style2','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style1_best',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '53',  7 => '',  8 => '', 9 => '', 10 => '', 11 => '', 12 => '53', 13 => '', 14 => '', 15 => '53', 16 => '', 17 => '', 18 => '53', 19 => '', 20 => '', 21 => '53',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '10 accounts under one domain',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => 'test',  37 => '',  38 => '',  39 => 'Hight priority support!',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s1", $table_t1_s1);
	$table_t1_s2 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '2','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn'=>'1','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '1','visibleColumns' => '2','scrollColumns' => '','slidingNavigation' => '1','slidingNavigationArrows' => '1','slidingArrowsStyle' => 'style7','slidingPagination' => '1','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style2','slidingOnTouch' => '1','slidingOnMouse' => '1','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => 'style2_heart',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '54',  7 => '',  8 => '', 9 => '54', 10 => '', 11 => '', 12 => '54', 13 => '', 14 => '', 15 => '54', 16 => '', 17 => '', 18 => '54', 19 => '', 20 => '', 21 => '54'),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_02.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_02.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_02.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_02.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s2", $table_t1_s2);
	$table_t1_s3 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '3','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => 'style1_off30',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_03.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_03.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_03.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_03.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array(  0 => '',  1 => '',  2 => '',  3 => 'Your tooltip text!',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => 'You can have unlimited bandwidth for $10 surcharge!',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => 'Support only in standard and professional plans!',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s3", $table_t1_s3);
	$table_t1_s4 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '4','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_04.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_04.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_04.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_04.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>'),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => 'Cool price!',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s4", $table_t1_s4);
	$table_t1_s5 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '5','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '170',  1 => '125',  2 => '150',  3 => '180',  4 => '210',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style2_new',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '55',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '40',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_05.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_05.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_05.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_05.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>'),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s5", $table_t1_s5);
	$table_t1_s6 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '6','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '1','visibleColumns' => '2','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style7','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style2','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '20','slidingAutoplay' => '1','slidingEffect' => 'crossfade','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_06.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_06.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_06.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_06.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s6", $table_t1_s6);
	$table_t1_s7 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '7','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_top_caps',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_07.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_07.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_07.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_07.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s7", $table_t1_s7);
	$table_t1_s8 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '8','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '1','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '1','slidingNavigationArrows' => '1','slidingArrowsStyle' => 'style5','slidingPagination' => '1','slidingPaginationPosition' => 'both','slidingPaginationStyle' => 'style3','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '20','slidingAutoplay' => '0','slidingEffect' => 'cover','slidingEasing' => 'easeInOutExpo','slidingDuration' => '500','widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => 'style2_no1',  2 => '-1',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_08.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_08.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_08.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_08.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s8", $table_t1_s8);
	$table_t1_s9 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '9','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_hot_caps',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_11.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_11.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_11.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_11.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s9", $table_t1_s9);
	$table_t1_s10 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '10','styleForTable2' => '1','hoverTypeForTable1' => 'light','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style2_fresh',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_06.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_06.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_04.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_04.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s10", $table_t1_s10);
	$table_t1_s11 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '11','styleForTable2' => '1','hoverTypeForTable1' => 'disabled','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style1_save_caps',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_02.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_02.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_04.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_04.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s11", $table_t1_s11);
	$table_t1_s12 = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '12','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style1_off25',  3 => 'style1_off30',  4 => 'style1_off40',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">starter</h2>',  2 => '<h2 class="col2">econo</h2>',  3 => '<h2 class="col1">standard</h2>',  4 => '<h2 class="col1">professional</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>59</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>99</span></h1><h3 class="col1">per month</h3>',  10 => 'Amount of space',  11 => '10GB',  12 => '30GB',  13 => '100GB',  14 => 'Unlimited',  15 => 'Bandwidth per month',  16 => '100GB',  17 => '200GB',  18 => '500GB',  19 => '1000GB',  20 => 'No. of e-mail accounts',  21 => '1',  22 => '10',  23 => '50',  24 => 'Unlimited',  25 => 'No. of MySql databases',  26 => '1',  27 => '10',  28 => '50',  29 => 'Unlimited',  30 => '24h support',  31 => '<img src="' . plugins_url("img/cross_07.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/cross_07.png", __FILE__) . '" alt="no">',  33 => '<img src="' . plugins_url("img/tick_07.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_07.png", __FILE__) . '" alt="yes">',  35 => 'Support tickets per mo.',  36 => '1',  37 => '3',  38 => '5',  39 => '10',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>',  42 => '<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',  43 => '<a href="' . get_site_url() . '?plan=3" class="sign_up radius3">sign up!</a>',  44 => '<a href="' . get_site_url() . '?plan=4" class="sign_up radius3">sign up!</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => ''));
	update_option("css3_grid_shortcode_settings_Table_t1_s12", $table_t1_s12);
	$table_t2_s1 = array('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style1_gift_caps',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes"> 2 domains',  39 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes"> 3 domains',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  49 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => 'Every additional database cost $3!',  27 => 'Every additional database cost $2!',  28 => 'Every additional database cost $1!',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s1", $table_t2_s1);
	$table_t2_s2 = array('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '2','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn'=>'1','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '1','visibleColumns' => '2','scrollColumns' => '','slidingNavigation' => '1','slidingNavigationArrows' => '1','slidingArrowsStyle' => 'style3','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => 'style2_sale',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_12.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes"> 2 domains',  39 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes"> 2 domains',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_12.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_12.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_12.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_12.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/cross_12.png", __FILE__) . '" alt="no">',  49 => '<img src="' . plugins_url("img/tick_12.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s2", $table_t2_s2);
	$table_t2_s3 = array('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '3','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => 'style2_pack',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_18.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes"> 2 domains',  39 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes"> 3 domains',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_18.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_18.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_18.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_18.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/cross_18.png", __FILE__) . '" alt="no">',  49 => '<img src="' . plugins_url("img/tick_18.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s3", $table_t2_s3);
	$table_t2_s4 = array('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '4','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes"> 2 domains',  39 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes"> 3 domains',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  49 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s4", $table_t2_s4);
	$table_t2_s5 = array('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '5','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style2_new_caps',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes"> 2 domains',  39 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes"> 3 domains',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  49 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s5", $table_t2_s5);
	$table_t2_s6 = array('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '6','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => 'style2_new_caps',  3 => '-1',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '35',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '20',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes"> 2 domains',  39 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes"> 3 domains',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/cross_19.png", __FILE__) . '" alt="no">',  49 => '<img src="' . plugins_url("img/tick_19.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s6", $table_t2_s6);
	$table_t2_s7 = array('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '7','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '1','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '1','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style3','slidingPagination' => '1','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style2','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '1','slidingEffect' => 'crossfade','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => 'style1_pro',  4 => '-1',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_16.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  39 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_16.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_16.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_16.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_16.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/cross_16.png", __FILE__) . '" alt="no">',  49 => '<img src="' . plugins_url("img/tick_16.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => 'Sample tooltip text!',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => 'Your tooltip text!',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s7", $table_t2_s7);
	$table_t2_s8 = array ('columns' => '5','rows' => '11','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '2','styleForTable1' => '1','styleForTable2' => '8','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '0','responsiveHideCaptionColumn'=>'0','responsiveSteps' => 3,'responsiveStepWidth' => array(0 => '1009', 1 => 767, 2 => 479),'slidingColumns' => '0','visibleColumns' => 1,'scrollColumns' => '','slidingNavigation' => 1,'slidingNavigationArrows' => 1,'slidingArrowsStyle' => 'style1','slidingPagination' => 0,'slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => 1,'slidingOnMouse' => 0,'slidingThreshold' => 75,'slidingAutoplay' => 0,'slidingEffect' => 'scroll','slidingEasing'=>'swing','slidingDuration' => 500,'widths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => ''),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style2_heart',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '', 9 => '', 10 => ''),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',),'texts' => array (  0 => '',  1 => '<h2>basic</h2>',  2 => '<h2>standard</h2>',  3 => '<h2>super</h2>',  4 => '<h2>ultimate</h2>',  5 => '<h1 class="caption">Hosting <span>Plans</span></h1>',  6 => '<h1>$3.95</h1><h3>per month</h3>',  7 => '<h1>$5.95</h1><h3>per month</h3>',  8 => '<h1>$7.95</h1><h3>per month</h3>',  9 => '<h1>$9.95</h1><h3>per month</h3>',  10 => 'Data Storage',  11 => '2GB Disk Space',  12 => '10GB Disk Space',  13 => '50GB Disk Space',  14 => 'Unlimited',  15 => 'Monthly Traffic',  16 => '10GB Bandwidth',  17 => '50GB Bandwidth',  18 => '100GB Bandwidth',  19 => 'Unlimited',  20 => 'Email Accounts',  21 => '5 Accounts',  22 => '10 Accounts',  23 => 'Unlimited',  24 => 'Unlimited',  25 => 'MySQL Databases',  26 => '2 Databases',  27 => '10 Databases',  28 => '20 Databases',  29 => 'Unlimited',  30 => 'Daily Backup',  31 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  32 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  35 => 'Free Domain',  36 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  37 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  38 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  39 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  40 => 'Website Statistics',  41 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  42 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  43 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  44 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  45 => 'Online Support',  46 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  47 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  48 => '<img src="' . plugins_url("img/cross_09.png", __FILE__) . '" alt="no">',  49 => '<img src="' . plugins_url("img/tick_09.png", __FILE__) . '" alt="yes">',  50 => '',  51 => '<a class="button_1 radius5" href="' . get_site_url() . '?plan=1">sign up</a>',  52 => '<a class="button_2 radius5" href="' . get_site_url() . '?plan=2">sign up</a>',  53 => '<a class="button_3 radius5" href="' . get_site_url() . '?plan=3">sign up</a>',  54 => '<a class="button_4 radius5" href="' . get_site_url() . '?plan=4">sign up</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => 'Every additonal 1GB of space cost $2!',  12 => 'Every additonal 1GB of space cost $2!',  13 => 'Every additonal 1GB of space cost $2!',  14 => '',  15 => '',  16 => '',  17 => '',  18 => '',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',  45 => '',  46 => '',  47 => '',  48 => '',  49 => '',  50 => '',  51 => '',  52 => '',  53 => '',  54 => ''));
	update_option("css3_grid_shortcode_settings_Table_t2_s8", $table_t2_s8);
	//medicenter style
	$medicenter_blue = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '13','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn' => '1','responsiveSteps' => '3','responsiveStepWidth' => array (  0 => '1009',  1 => '767',  2 => '479',),'responsiveButtonWidth' => array (  0 => '',  1 => '75',  2 => '70',),'responsiveHeaderFontSize' => array (  0 => '',  1 => '18',  2 => '',),'responsivePriceFontSize' => array (  0 => '',  1 => '42',  2 => '',),'responsivePermonthFontSize' => array (  0 => '',  1 => '',  2 => '',),'responsiveContentFontSize' => array (  0 => '',  1 => '',  2 => '10',),'responsiveButtonsFontSize' => array (  0 => '',  1 => '',  2 => '',),'priceFontCustom' => '','priceFont' => 'PT Sans:regular','priceFontSubset' => NULL,'priceFontSize' => '48','headerFontCustom' => '','headerFont' => 'PT Sans:regular','headerFontSubset' => NULL,'headerFontSize' => '24','permonthFontCustom' => '','permonthFont' => '','permonthFontSubset' => NULL,'permonthFontSize' => '','contentFontCustom' => '','contentFont' => '','contentFontSubset' => NULL,'contentFontSize' => '','buttonsFontCustom' => '','buttonsFont' => '','buttonsFontSubset' => NULL,'buttonsFontSize' => '','slidingColumns' => '0','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style1','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '20%',  1 => '20%',  2 => '20%',  3 => '20%',  4 => '20%',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '25%',  6 => '',  7 => '',  8 => '25%',  9 => '',  10 => '',  11 => '25%',  12 => '',  13 => '',  14 => '25%',),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_pro',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '31',  7 => '49',  8 => '55',  9 => '31',  10 => '49',  11 => '66',  12 => '',  13 => '31',  14 => '55',  15 => '',  16 => '31',  17 => '55',  18 => '',  19 => '31',  20 => '55',  21 => '',  22 => '31',  23 => '55',  24 => '',  25 => '',  26 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">Basic Plan</h2>',  2 => '<h2 class="col2">Care Plus</h2>',  3 => '<h2 class="col1">Super Care</h2>',  4 => '<h2 class="col1">Super Prestige</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>16</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>25</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>29</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>39</span></h1><h3 class="col1">per month</h3>',  10 => 'Available Medical Specialties',  11 => '6 Specialties',  12 => '12 Specialties',  13 => '24 Specialties',  14 => '36 Specialties',  15 => 'Investigations and Treatments',  16 => '30 Tests and Treatments',  17 => '90 Tests and Treatments',  18 => '160 Tests and Treatments',  19 => '250 Tests and Treatments',  20 => 'Medical Consultation',  21 => '1 Time a Year',  22 => '2 Times a Year',  23 => '4 Times a Year',  24 => 'Unlimited',  25 => 'Home Visits',  26 => '1 Time a Year',  27 => '2 Times a Year',  28 => '4 Times a Year',  29 => 'Unlimited',  30 => 'Pregnancy Care',  31 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  35 => 'Medical Assistance',  36 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  37 => '24h Assistance',  38 => '24h Assistance',  39 => '24h Assistance',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  42 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  43 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  44 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => 'Can be extended to 250 Tests',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',));
	update_option("css3_grid_shortcode_settings_medicenter_blue", $medicenter_blue);
	$medicenter_green = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '14','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn' => '1','responsiveSteps' => '3','responsiveStepWidth' => array (  0 => '1009',  1 => '767',  2 => '479',),'responsiveButtonWidth' => array (  0 => '',  1 => '75',  2 => '70',),'responsiveHeaderFontSize' => array (  0 => '',  1 => '18',  2 => '',),'responsivePriceFontSize' => array (  0 => '',  1 => '42',  2 => '',),'responsivePermonthFontSize' => array (  0 => '',  1 => '',  2 => '',),'responsiveContentFontSize' => array (  0 => '',  1 => '',  2 => '10',),'responsiveButtonsFontSize' => array (  0 => '',  1 => '',  2 => '',),'priceFontCustom' => '','priceFont' => 'PT Sans:regular','priceFontSubset' => NULL,'priceFontSize' => '48','headerFontCustom' => '','headerFont' => 'PT Sans:regular','headerFontSubset' => NULL,'headerFontSize' => '24','permonthFontCustom' => '','permonthFont' => '','permonthFontSubset' => NULL,'permonthFontSize' => '','contentFontCustom' => '','contentFont' => '','contentFontSubset' => NULL,'contentFontSize' => '','buttonsFontCustom' => '','buttonsFont' => '','buttonsFontSubset' => NULL,'buttonsFontSize' => '','slidingColumns' => '0','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style1','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '20%',  1 => '20%',  2 => '20%',  3 => '20%',  4 => '20%',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '25%',  6 => '',  7 => '',  8 => '25%',  9 => '',  10 => '',  11 => '25%',  12 => '',  13 => '',  14 => '25%',),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_pro',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '31',  7 => '49',  8 => '55',  9 => '31',  10 => '49',  11 => '66',  12 => '',  13 => '31',  14 => '55',  15 => '',  16 => '31',  17 => '55',  18 => '',  19 => '31',  20 => '55',  21 => '',  22 => '31',  23 => '55',  24 => '',  25 => '',  26 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">Basic Plan</h2>',  2 => '<h2 class="col2">Care Plus</h2>',  3 => '<h2 class="col1">Super Care</h2>',  4 => '<h2 class="col1">Super Prestige</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>16</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>25</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>29</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>39</span></h1><h3 class="col1">per month</h3>',  10 => 'Available Medical Specialties',  11 => '6 Specialties',  12 => '12 Specialties',  13 => '24 Specialties',  14 => '36 Specialties',  15 => 'Investigations and Treatments',  16 => '30 Tests and Treatments',  17 => '90 Tests and Treatments',  18 => '160 Tests and Treatments',  19 => '250 Tests and Treatments',  20 => 'Medical Consultation',  21 => '1 Time a Year',  22 => '2 Times a Year',  23 => '4 Times a Year',  24 => 'Unlimited',  25 => 'Home Visits',  26 => '1 Time a Year',  27 => '2 Times a Year',  28 => '4 Times a Year',  29 => 'Unlimited',  30 => 'Pregnancy Care',  31 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  35 => 'Medical Assistance',  36 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  37 => '24h Assistance',  38 => '24h Assistance',  39 => '24h Assistance',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  42 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  43 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  44 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => 'Can be extended to 250 Tests',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',));
	update_option("css3_grid_shortcode_settings_medicenter_green", $medicenter_green);
	$medicenter_orange = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '15','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn' => '1','responsiveSteps' => '3','responsiveStepWidth' => array (  0 => '1009',  1 => '767',  2 => '479',),'responsiveButtonWidth' => array (  0 => '',  1 => '75',  2 => '70',),'responsiveHeaderFontSize' => array (  0 => '',  1 => '18',  2 => '',),'responsivePriceFontSize' => array (  0 => '',  1 => '42',  2 => '',),'responsivePermonthFontSize' => array (  0 => '',  1 => '',  2 => '',),'responsiveContentFontSize' => array (  0 => '',  1 => '',  2 => '10',),'responsiveButtonsFontSize' => array (  0 => '',  1 => '',  2 => '',),'priceFontCustom' => '','priceFont' => 'PT Sans:regular','priceFontSubset' => NULL,'priceFontSize' => '48','headerFontCustom' => '','headerFont' => 'PT Sans:regular','headerFontSubset' => NULL,'headerFontSize' => '24','permonthFontCustom' => '','permonthFont' => '','permonthFontSubset' => NULL,'permonthFontSize' => '','contentFontCustom' => '','contentFont' => '','contentFontSubset' => NULL,'contentFontSize' => '','buttonsFontCustom' => '','buttonsFont' => '','buttonsFontSubset' => NULL,'buttonsFontSize' => '','slidingColumns' => '0','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style1','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '20%',  1 => '20%',  2 => '20%',  3 => '20%',  4 => '20%',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '25%',  6 => '',  7 => '',  8 => '25%',  9 => '',  10 => '',  11 => '25%',  12 => '',  13 => '',  14 => '25%',),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_pro',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '31',  7 => '49',  8 => '55',  9 => '31',  10 => '49',  11 => '66',  12 => '',  13 => '31',  14 => '55',  15 => '',  16 => '31',  17 => '55',  18 => '',  19 => '31',  20 => '55',  21 => '',  22 => '31',  23 => '55',  24 => '',  25 => '',  26 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">Basic Plan</h2>',  2 => '<h2 class="col2">Care Plus</h2>',  3 => '<h2 class="col1">Super Care</h2>',  4 => '<h2 class="col1">Super Prestige</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>16</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>25</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>29</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>39</span></h1><h3 class="col1">per month</h3>',  10 => 'Available Medical Specialties',  11 => '6 Specialties',  12 => '12 Specialties',  13 => '24 Specialties',  14 => '36 Specialties',  15 => 'Investigations and Treatments',  16 => '30 Tests and Treatments',  17 => '90 Tests and Treatments',  18 => '160 Tests and Treatments',  19 => '250 Tests and Treatments',  20 => 'Medical Consultation',  21 => '1 Time a Year',  22 => '2 Times a Year',  23 => '4 Times a Year',  24 => 'Unlimited',  25 => 'Home Visits',  26 => '1 Time a Year',  27 => '2 Times a Year',  28 => '4 Times a Year',  29 => 'Unlimited',  30 => 'Pregnancy Care',  31 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  35 => 'Medical Assistance',  36 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  37 => '24h Assistance',  38 => '24h Assistance',  39 => '24h Assistance',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  42 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  43 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  44 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => 'Can be extended to 250 Tests',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',));
	update_option("css3_grid_shortcode_settings_medicenter_orange", $medicenter_orange);
	$medicenter_red = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '16','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn' => '1','responsiveSteps' => '3','responsiveStepWidth' => array (  0 => '1009',  1 => '767',  2 => '479',),'responsiveButtonWidth' => array (  0 => '',  1 => '75',  2 => '70',),'responsiveHeaderFontSize' => array (  0 => '',  1 => '18',  2 => '',),'responsivePriceFontSize' => array (  0 => '',  1 => '42',  2 => '',),'responsivePermonthFontSize' => array (  0 => '',  1 => '',  2 => '',),'responsiveContentFontSize' => array (  0 => '',  1 => '',  2 => '10',),'responsiveButtonsFontSize' => array (  0 => '',  1 => '',  2 => '',),'priceFontCustom' => '','priceFont' => 'PT Sans:regular','priceFontSubset' => NULL,'priceFontSize' => '48','headerFontCustom' => '','headerFont' => 'PT Sans:regular','headerFontSubset' => NULL,'headerFontSize' => '24','permonthFontCustom' => '','permonthFont' => '','permonthFontSubset' => NULL,'permonthFontSize' => '','contentFontCustom' => '','contentFont' => '','contentFontSubset' => NULL,'contentFontSize' => '','buttonsFontCustom' => '','buttonsFont' => '','buttonsFontSubset' => NULL,'buttonsFontSize' => '','slidingColumns' => '0','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style1','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '20%',  1 => '20%',  2 => '20%',  3 => '20%',  4 => '20%',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '25%',  6 => '',  7 => '',  8 => '25%',  9 => '',  10 => '',  11 => '25%',  12 => '',  13 => '',  14 => '25%',),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_pro',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '31',  7 => '49',  8 => '55',  9 => '31',  10 => '49',  11 => '66',  12 => '',  13 => '31',  14 => '55',  15 => '',  16 => '31',  17 => '55',  18 => '',  19 => '31',  20 => '55',  21 => '',  22 => '31',  23 => '55',  24 => '',  25 => '',  26 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">Basic Plan</h2>',  2 => '<h2 class="col2">Care Plus</h2>',  3 => '<h2 class="col1">Super Care</h2>',  4 => '<h2 class="col1">Super Prestige</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>16</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>25</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>29</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>39</span></h1><h3 class="col1">per month</h3>',  10 => 'Available Medical Specialties',  11 => '6 Specialties',  12 => '12 Specialties',  13 => '24 Specialties',  14 => '36 Specialties',  15 => 'Investigations and Treatments',  16 => '30 Tests and Treatments',  17 => '90 Tests and Treatments',  18 => '160 Tests and Treatments',  19 => '250 Tests and Treatments',  20 => 'Medical Consultation',  21 => '1 Time a Year',  22 => '2 Times a Year',  23 => '4 Times a Year',  24 => 'Unlimited',  25 => 'Home Visits',  26 => '1 Time a Year',  27 => '2 Times a Year',  28 => '4 Times a Year',  29 => 'Unlimited',  30 => 'Pregnancy Care',  31 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  35 => 'Medical Assistance',  36 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  37 => '24h Assistance',  38 => '24h Assistance',  39 => '24h Assistance',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  42 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  43 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  44 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => 'Can be extended to 250 Tests',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',));
	update_option("css3_grid_shortcode_settings_medicenter_red", $medicenter_red);
	$medicenter_turquoise = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '17','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn' => '1','responsiveSteps' => '3','responsiveStepWidth' => array (  0 => '1009',  1 => '767',  2 => '479',),'responsiveButtonWidth' => array (  0 => '',  1 => '75',  2 => '70',),'responsiveHeaderFontSize' => array (  0 => '',  1 => '18',  2 => '',),'responsivePriceFontSize' => array (  0 => '',  1 => '42',  2 => '',),'responsivePermonthFontSize' => array (  0 => '',  1 => '',  2 => '',),'responsiveContentFontSize' => array (  0 => '',  1 => '',  2 => '10',),'responsiveButtonsFontSize' => array (  0 => '',  1 => '',  2 => '',),'priceFontCustom' => '','priceFont' => 'PT Sans:regular','priceFontSubset' => NULL,'priceFontSize' => '48','headerFontCustom' => '','headerFont' => 'PT Sans:regular','headerFontSubset' => NULL,'headerFontSize' => '24','permonthFontCustom' => '','permonthFont' => '','permonthFontSubset' => NULL,'permonthFontSize' => '','contentFontCustom' => '','contentFont' => '','contentFontSubset' => NULL,'contentFontSize' => '','buttonsFontCustom' => '','buttonsFont' => '','buttonsFontSubset' => NULL,'buttonsFontSize' => '','slidingColumns' => '0','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style1','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '20%',  1 => '20%',  2 => '20%',  3 => '20%',  4 => '20%',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '25%',  6 => '',  7 => '',  8 => '25%',  9 => '',  10 => '',  11 => '25%',  12 => '',  13 => '',  14 => '25%',),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_pro',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '31',  7 => '49',  8 => '55',  9 => '31',  10 => '49',  11 => '66',  12 => '',  13 => '31',  14 => '55',  15 => '',  16 => '31',  17 => '55',  18 => '',  19 => '31',  20 => '55',  21 => '',  22 => '31',  23 => '55',  24 => '',  25 => '',  26 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">Basic Plan</h2>',  2 => '<h2 class="col2">Care Plus</h2>',  3 => '<h2 class="col1">Super Care</h2>',  4 => '<h2 class="col1">Super Prestige</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>16</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>25</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>29</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>39</span></h1><h3 class="col1">per month</h3>',  10 => 'Available Medical Specialties',  11 => '6 Specialties',  12 => '12 Specialties',  13 => '24 Specialties',  14 => '36 Specialties',  15 => 'Investigations and Treatments',  16 => '30 Tests and Treatments',  17 => '90 Tests and Treatments',  18 => '160 Tests and Treatments',  19 => '250 Tests and Treatments',  20 => 'Medical Consultation',  21 => '1 Time a Year',  22 => '2 Times a Year',  23 => '4 Times a Year',  24 => 'Unlimited',  25 => 'Home Visits',  26 => '1 Time a Year',  27 => '2 Times a Year',  28 => '4 Times a Year',  29 => 'Unlimited',  30 => 'Pregnancy Care',  31 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  35 => 'Medical Assistance',  36 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  37 => '24h Assistance',  38 => '24h Assistance',  39 => '24h Assistance',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  42 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  43 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  44 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => 'Can be extended to 250 Tests',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',));
	update_option("css3_grid_shortcode_settings_medicenter_turquoise", $medicenter_turquoise);
	$medicenter_violet = array('columns' => '5','rows' => '9','hiddenRows' => '0','hiddenRowsButtonExpandText' => 'Click here to expand!','hiddenRowsButtonCollapseText' => 'Click here to collapse!','kind' => '1','styleForTable1' => '18','styleForTable2' => '1','hoverTypeForTable1' => 'active','hoverTypeForTable2' => 'active','responsive' => '1','responsiveHideCaptionColumn' => '1','responsiveSteps' => '3','responsiveStepWidth' => array (  0 => '1009',  1 => '767',  2 => '479',),'responsiveButtonWidth' => array (  0 => '',  1 => '75',  2 => '70',),'responsiveHeaderFontSize' => array (  0 => '',  1 => '18',  2 => '',),'responsivePriceFontSize' => array (  0 => '',  1 => '42',  2 => '',),'responsivePermonthFontSize' => array (  0 => '',  1 => '',  2 => '',),'responsiveContentFontSize' => array (  0 => '',  1 => '',  2 => '10',),'responsiveButtonsFontSize' => array (  0 => '',  1 => '',  2 => '',),'priceFontCustom' => '','priceFont' => 'PT Sans:regular','priceFontSubset' => NULL,'priceFontSize' => '48','headerFontCustom' => '','headerFont' => 'PT Sans:regular','headerFontSubset' => NULL,'headerFontSize' => '24','permonthFontCustom' => '','permonthFont' => '','permonthFontSubset' => NULL,'permonthFontSize' => '','contentFontCustom' => '','contentFont' => '','contentFontSubset' => NULL,'contentFontSize' => '','buttonsFontCustom' => '','buttonsFont' => '','buttonsFontSubset' => NULL,'buttonsFontSize' => '','slidingColumns' => '0','visibleColumns' => '1','scrollColumns' => '','slidingNavigation' => '0','slidingNavigationArrows' => '0','slidingArrowsStyle' => 'style1','slidingPagination' => '0','slidingPaginationPosition' => 'bottom','slidingPaginationStyle' => 'style1','slidingOnTouch' => '1','slidingOnMouse' => '0','slidingThreshold' => '75','slidingAutoplay' => '0','slidingEffect' => 'scroll','slidingEasing' => 'swing','slidingDuration' => '500','widths' => array (  0 => '20%',  1 => '20%',  2 => '20%',  3 => '20%',  4 => '20%',),'responsiveWidths' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '25%',  6 => '',  7 => '',  8 => '25%',  9 => '',  10 => '',  11 => '25%',  12 => '',  13 => '',  14 => '25%',),'aligments' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'actives' => array (  0 => '-1',  1 => '-1',  2 => '1',  3 => '-1',  4 => '-1',),'hiddens' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => '-1',),'ribbons' => array (  0 => '-1',  1 => '-1',  2 => '-1',  3 => '-1',  4 => 'style1_pro',),'heights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'responsiveHeights' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '31',  7 => '49',  8 => '55',  9 => '31',  10 => '49',  11 => '66',  12 => '',  13 => '31',  14 => '55',  15 => '',  16 => '31',  17 => '55',  18 => '',  19 => '31',  20 => '55',  21 => '',  22 => '31',  23 => '55',  24 => '',  25 => '',  26 => '',),'paddingsTop' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'paddingsBottom' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',),'texts' => array (  0 => '',  1 => '<h2 class="col1">Basic Plan</h2>',  2 => '<h2 class="col2">Care Plus</h2>',  3 => '<h2 class="col1">Super Care</h2>',  4 => '<h2 class="col1">Super Prestige</h2>',  5 => '<h2 class="caption">choose <span>your</span> plan</h2>',  6 => '<h1 class="col1">$<span>16</span></h1><h3 class="col1">per month</h3>',  7 => '<h1 class="col1">$<span>25</span></h1><h3 class="col1">per month</h3>',  8 => '<h1 class="col1">$<span>29</span></h1><h3 class="col1">per month</h3>',  9 => '<h1 class="col1">$<span>39</span></h1><h3 class="col1">per month</h3>',  10 => 'Available Medical Specialties',  11 => '6 Specialties',  12 => '12 Specialties',  13 => '24 Specialties',  14 => '36 Specialties',  15 => 'Investigations and Treatments',  16 => '30 Tests and Treatments',  17 => '90 Tests and Treatments',  18 => '160 Tests and Treatments',  19 => '250 Tests and Treatments',  20 => 'Medical Consultation',  21 => '1 Time a Year',  22 => '2 Times a Year',  23 => '4 Times a Year',  24 => 'Unlimited',  25 => 'Home Visits',  26 => '1 Time a Year',  27 => '2 Times a Year',  28 => '4 Times a Year',  29 => 'Unlimited',  30 => 'Pregnancy Care',  31 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  32 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  33 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  34 => '<img src="' . plugins_url("img/tick_01.png", __FILE__) . '" alt="yes">',  35 => 'Medical Assistance',  36 => '<img src="' . plugins_url("img/cross_01.png", __FILE__) . '" alt="no">',  37 => '24h Assistance',  38 => '24h Assistance',  39 => '24h Assistance',  40 => '',  41 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  42 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  43 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',  44 => '<a href="' . get_site_url() . '?plan=1" class="sign_up" radius3="">Learn more</a>',),'tooltips' => array (  0 => '',  1 => '',  2 => '',  3 => '',  4 => '',  5 => '',  6 => '',  7 => '',  8 => '',  9 => '',  10 => '',  11 => '',  12 => '',  13 => '',  14 => '',  15 => '',  16 => '',  17 => '',  18 => 'Can be extended to 250 Tests',  19 => '',  20 => '',  21 => '',  22 => '',  23 => '',  24 => '',  25 => '',  26 => '',  27 => '',  28 => '',  29 => '',  30 => '',  31 => '',  32 => '',  33 => '',  34 => '',  35 => '',  36 => '',  37 => '',  38 => '',  39 => '',  40 => '',  41 => '',  42 => '',  43 => '',  44 => '',));
	update_option("css3_grid_shortcode_settings_medicenter_violet", $medicenter_violet);
}
register_activation_hook( __FILE__, 'css3_grid_activate');

function css3_grid_shortcode($atts)
{
	extract(shortcode_atts(array(
		'id' => ''
	), $atts));
	if($id!="")
	{
		if($shortcode_settings = get_option('css3_grid_shortcode_settings_' . $id))
		{
			$responsiveStepWidth = "";
			for($i=0; $i<count($shortcode_settings["responsiveStepWidth"]); $i++)
			{
				$responsiveStepWidth .= $shortcode_settings["responsiveStepWidth"][$i];
				if($i+1<count($shortcode_settings["responsiveStepWidth"]));
					$responsiveStepWidth .= "|";
			}
			$responsiveButtonWidth = "";
			for($i=0; $i<count($shortcode_settings["responsiveButtonWidth"]); $i++)
			{
				$responsiveButtonWidth .= $shortcode_settings["responsiveButtonWidth"][$i];
				if($i+1<count($shortcode_settings["responsiveButtonWidth"]));
					$responsiveButtonWidth .= "|";
			}
			$responsiveHeaderFontSize = "";
			for($i=0; $i<count($shortcode_settings["responsiveHeaderFontSize"]); $i++)
			{
				$responsiveHeaderFontSize .= $shortcode_settings["responsiveHeaderFontSize"][$i];
				if($i+1<count($shortcode_settings["responsiveHeaderFontSize"]));
					$responsiveHeaderFontSize .= "|";
			}
			$responsivePriceFontSize = "";
			for($i=0; $i<count($shortcode_settings["responsivePriceFontSize"]); $i++)
			{
				$responsivePriceFontSize .= $shortcode_settings["responsivePriceFontSize"][$i];
				if($i+1<count($shortcode_settings["responsivePriceFontSize"]));
					$responsivePriceFontSize .= "|";
			}
			$responsivePermonthFontSize = "";
			for($i=0; $i<count($shortcode_settings["responsivePermonthFontSize"]); $i++)
			{
				$responsivePermonthFontSize .= $shortcode_settings["responsivePermonthFontSize"][$i];
				if($i+1<count($shortcode_settings["responsivePermonthFontSize"]));
					$responsivePermonthFontSize .= "|";
			}
			$responsiveContentFontSize = "";
			for($i=0; $i<count($shortcode_settings["responsiveContentFontSize"]); $i++)
			{
				$responsiveContentFontSize .= $shortcode_settings["responsiveContentFontSize"][$i];
				if($i+1<count($shortcode_settings["responsiveContentFontSize"]));
					$responsiveContentFontSize .= "|";
			}
			$responsiveButtonsFontSize = "";
			for($i=0; $i<count($shortcode_settings["responsiveButtonsFontSize"]); $i++)
			{
				$responsiveButtonsFontSize .= $shortcode_settings["responsiveButtonsFontSize"][$i];
				if($i+1<count($shortcode_settings["responsiveButtonsFontSize"]));
					$responsiveButtonsFontSize .= "|";
			}
			$widths = "";
			for($i=0; $i<count($shortcode_settings["widths"]); $i++)
			{
				$widths .= $shortcode_settings["widths"][$i];
				if($i+1<count($shortcode_settings["widths"]));
					$widths .= "|";
			}
			$responsiveWidths = "";
			for($i=0; $i<count($shortcode_settings["responsiveWidths"]); $i++)
			{
				$responsiveWidths .= $shortcode_settings["responsiveWidths"][$i];
				if($i+1<count($shortcode_settings["responsiveWidths"]));
					$responsiveWidths .= "|";
			}
			$aligments = "";
			for($i=0; $i<count($shortcode_settings["aligments"]); $i++)
			{
				$aligments .= $shortcode_settings["aligments"][$i];
				if($i+1<count($shortcode_settings["aligments"]));
					$aligments .= "|";
			}
			$actives = "";
			for($i=0; $i<count($shortcode_settings["actives"]); $i++)
			{
				$actives .= (int)$shortcode_settings["actives"][$i];
				if($i+1<count($shortcode_settings["actives"]));
					$actives .= "|";
			}
			$hiddens = "";
			for($i=0; $i<count($shortcode_settings["hiddens"]); $i++)
			{
				$hiddens .= (int)$shortcode_settings["hiddens"][$i];
				if($i+1<count($shortcode_settings["hiddens"]));
					$hiddens .= "|";
			}
			$ribbons = "";
			for($i=0; $i<count($shortcode_settings["ribbons"]); $i++)
			{
				$ribbons .= $shortcode_settings["ribbons"][$i];
				if($i+1<count($shortcode_settings["ribbons"]));
					$ribbons .= "|";
			}
			$heights = "";
			for($i=0; $i<count($shortcode_settings["heights"]); $i++)
			{
				$heights .= $shortcode_settings["heights"][$i];
				if($i+1<count($shortcode_settings["heights"]));
					$heights .= "|";
			}
			$responsiveHeights = "";
			for($i=0; $i<count($shortcode_settings["responsiveHeights"]); $i++)
			{
				$responsiveHeights .= $shortcode_settings["responsiveHeights"][$i];
				if($i+1<count($shortcode_settings["responsiveHeights"]));
					$responsiveHeights .= "|";
			}
			$paddingsTop = "";
			for($i=0; $i<count($shortcode_settings["paddingsTop"]); $i++)
			{
				$paddingsTop .= (int)$shortcode_settings["paddingsTop"][$i];
				if($i+1<count($shortcode_settings["paddingsTop"]));
					$paddingsTop .= "|";
			}
			$paddingsBottom = "";
			for($i=0; $i<count($shortcode_settings["paddingsBottom"]); $i++)
			{
				$paddingsBottom .= (int)$shortcode_settings["paddingsBottom"][$i];
				if($i+1<count($shortcode_settings["paddingsBottom"]));
					$paddingsBottom .= "|";
			}
			$texts = "";
			for($i=0; $i<count($shortcode_settings["texts"]); $i++)
			{
				$texts .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $shortcode_settings["texts"][$i])));
				if($i+1<count($shortcode_settings["texts"]));
					$texts .= "|";
			}
			$tooltips = "";
			for($i=0; $i<count($shortcode_settings["tooltips"]); $i++)
			{
				$tooltips .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $shortcode_settings["tooltips"][$i])));
				if($i+1<count($shortcode_settings["tooltips"]));
					$tooltips .= "|";
			}
			$headerFontSubsets = "";
			for($i=0; $i<count($shortcode_settings["headerFontSubset"]); $i++)
			{
				$headerFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $shortcode_settings["headerFontSubset"][$i])));
				if($i+1<count($shortcode_settings["headerFontSubset"]));
					$headerFontSubsets .= "|";
			}
			$priceFontSubsets = "";
			for($i=0; $i<count($shortcode_settings["priceFontSubset"]); $i++)
			{
				$priceFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $shortcode_settings["priceFontSubset"][$i])));
				if($i+1<count($shortcode_settings["priceFontSubset"]));
					$priceFontSubsets .= "|";
			}
			$permonthFontSubsets = "";
			for($i=0; $i<count($shortcode_settings["permonthFontSubset"]); $i++)
			{
				$permonthFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $shortcode_settings["permonthFontSubset"][$i])));
				if($i+1<count($shortcode_settings["permonthFontSubset"]));
					$permonthFontSubsets .= "|";
			}
			$contentFontSubsets = "";
			for($i=0; $i<count($shortcode_settings["contentFontSubset"]); $i++)
			{
				$contentFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $shortcode_settings["contentFontSubset"][$i])));
				if($i+1<count($shortcode_settings["contentFontSubset"]));
					$contentFontSubsets .= "|";
			}
			$buttonsFontSubsets = "";
			for($i=0; $i<count($shortcode_settings["buttonsFontSubset"]); $i++)
			{
				$buttonsFontSubsets .= str_replace("]", "&#93;", str_replace("[", "&#91;", str_replace("'", "&#39;", $shortcode_settings["buttonsFontSubset"][$i])));
				if($i+1<count($shortcode_settings["buttonsFontSubset"]));
					$buttonsFontSubsets .= "|";
			}
			$output = do_shortcode("[css3_grid_print id='" . $id . "' kind='" . $shortcode_settings["kind"] . "' style='" . $shortcode_settings["styleForTable" . $shortcode_settings["kind"]] . "' hoverType='" . $shortcode_settings["hoverTypeForTable" . $shortcode_settings["kind"]] . "' responsive='" . $shortcode_settings["responsive"] . "' responsiveHideCaptionColumn='" . $shortcode_settings["responsiveHideCaptionColumn"] . "' responsiveSteps='" . $shortcode_settings["responsiveSteps"] . "' responsiveStepWidth='" . $responsiveStepWidth . "' responsiveButtonWidth='" . $responsiveButtonWidth . "' responsiveHeaderFontSize='" . $responsiveHeaderFontSize . "' responsivePriceFontSize='" . $responsivePriceFontSize . "' responsivePermonthFontSize='" . $responsivePermonthFontSize . "' responsiveContentFontSize='" . $responsiveContentFontSize . "' responsiveButtonsFontSize='" . $responsiveButtonsFontSize . "' priceFontCustom='" . $shortcode_settings["priceFontCustom"] . "' priceFont='" . $shortcode_settings["priceFont"] . "' priceFontSubsets='" . $shortcode_settings["priceFontSubsets"] . "' priceFontSize='" . $shortcode_settings["priceFontSize"] . "' headerFontCustom='" . $shortcode_settings["headerFontCustom"] . "' headerFont='" . $shortcode_settings["headerFont"] . "' headerFontSubsets='" . $shortcode_settings["headerFontSubsets"] . "' headerFontSize='" . $shortcode_settings["headerFontSize"] . "' permonthFontCustom='" . $shortcode_settings["permonthFontCustom"] . "' permonthFont='" . $shortcode_settings["permonthFont"] . "' permonthFontSubsets='" . $shortcode_settings["permonthFontSubsets"] . "' permonthFontSize='" . $shortcode_settings["permonthFontSize"] . "' contentFontCustom='" . $shortcode_settings["contentFontCustom"] . "' contentFont='" . $shortcode_settings["contentFont"] . "' contentFontSubsets='" . $shortcode_settings["contentFontSubsets"] . "' contentFontSize='" . $shortcode_settings["contentFontSize"] . "' buttonsFontCustom='" . $shortcode_settings["buttonsFontCustom"] . "' buttonsFont='" . $shortcode_settings["buttonsFont"] . "' buttonsFontSubsets='" . $shortcode_settings["buttonsFontSubsets"] . "' buttonsFontSize='" . $shortcode_settings["buttonsFontSize"] . "' slidingColumns='" . $shortcode_settings["slidingColumns"] . "' visibleColumns='" . $shortcode_settings["visibleColumns"] . "' scrollColumns='" . $shortcode_settings["scrollColumns"] . "' slidingNavigation='" . $shortcode_settings["slidingNavigation"] . "' slidingNavigationArrows='" . $shortcode_settings["slidingNavigationArrows"] . "' slidingArrowsStyle='" . $shortcode_settings["slidingArrowsStyle"] . "' slidingPagination='" . $shortcode_settings["slidingPagination"] . "' slidingPaginationPosition='" . $shortcode_settings["slidingPaginationPosition"] . "' slidingPaginationStyle='" . $shortcode_settings["slidingPaginationStyle"] . "' slidingOnTouch='" . $shortcode_settings["slidingOnTouch"] . "' slidingOnMouse='" . $shortcode_settings["slidingOnMouse"] . "' slidingThreshold='" . $shortcode_settings["slidingThreshold"] . "' slidingAutoplay='" . $shortcode_settings["slidingAutoplay"] . "' slidingEffect='" . $shortcode_settings["slidingEffect"] . "' slidingEasing='" . $shortcode_settings["slidingEasing"] . "' slidingDuration='" . $shortcode_settings["slidingDuration"] . "' columns='" . $shortcode_settings["columns"] . "' rows='" . $shortcode_settings["rows"] . "' hiddenRows='" . $shortcode_settings["hiddenRows"] . "' hiddenRowsButtonExpandText='" . $shortcode_settings["hiddenRowsButtonExpandText"] . "' hiddenRowsButtonCollapseText='" . $shortcode_settings["hiddenRowsButtonCollapseText"] . "' texts='" . $texts . "' tooltips='" . $tooltips . "' widths='" . $widths . "' responsiveWidths='" . $responsiveWidths . "' aligments='" . $aligments . "' actives='" . $actives . "' hiddens='" . $hiddens . "' ribbons='" . $ribbons . "' heights='" . $heights . "' responsiveHeights='" . $responsiveHeights . "' paddingstop='" . $paddingsTop . "' paddingsbottom='" . $paddingsBottom . "']");
		}
		else
			$output = "Shortcode with given id not found!";
	}
	else
		$output = "Parameter id not specified!";
	return $output;
}
add_shortcode('css3_grid', 'css3_grid_shortcode');

function css3_grid_enqueue_scripts()
{
	$css3_grid_global_options = (array)get_option('css3_grid_global_settings');
	if($css3_grid_global_options['loadFiles']!='when_used')
	{
		wp_enqueue_style('css3_grid_font_yanone', 'http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz');
		/*
		wp_enqueue_style('css3_grid_table1_main', plugins_url('table1/main.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style1', plugins_url('table1/style_1.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style2', plugins_url('table1/style_2.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style3', plugins_url('table1/style_3.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style4', plugins_url('table1/style_4.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style5', plugins_url('table1/style_5.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style6', plugins_url('table1/style_6.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style7', plugins_url('table1/style_7.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style8', plugins_url('table1/style_8.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style9', plugins_url('table1/style_9.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style10', plugins_url('table1/style_10.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style11', plugins_url('table1/style_11.css', __FILE__));
		wp_enqueue_style('css3_grid_table1_style12', plugins_url('table1/style_12.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_main', plugins_url('table2/main.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style1', plugins_url('table2/style_1.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style2', plugins_url('table2/style_2.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style3', plugins_url('table2/style_3.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style4', plugins_url('table2/style_4.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style5', plugins_url('table2/style_5.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style6', plugins_url('table2/style_6.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style7', plugins_url('table2/style_7.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style8', plugins_url('table2/style_8.css', __FILE__));*/
		wp_enqueue_style('css3_grid_table1_style', plugins_url('table1/css3_grid_style.css', __FILE__));
		wp_enqueue_style('css3_grid_table2_style', plugins_url('table2/css3_grid_style.css', __FILE__));
		wp_enqueue_style('css3_grid_responsive', plugins_url('responsive.css', __FILE__));
	}
}
add_action('wp_enqueue_scripts', 'css3_grid_enqueue_scripts');

function css3_grid_wp_footer()
{
	global $css3_grid_shortcode_used;
	global $css3_grid_load_responsive;
	global $css3_grid_load_kind_1;
	global $css3_grid_load_kind_2;
	global $css3_grid_load_js;
	global $css3_grid_load_expand_collapse;
	if((int)$css3_grid_load_js)
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-carouFredSel', plugins_url('js/jquery.carouFredSel-6.1.0-packed.js', __FILE__));
		wp_enqueue_script('css3_grid_main', plugins_url('js/main.js', __FILE__)); 
		wp_enqueue_script('jquery-easing', plugins_url('js/jquery.easing.1.3.js', __FILE__));
		wp_enqueue_script('jquery-touchSwipe', plugins_url('js/jquery.touchSwipe.min.js', __FILE__));
	}
	else if((int)$css3_grid_load_expand_collapse)
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('css3_grid_main', plugins_url('js/main.js', __FILE__));
	}
	$css3_grid_global_options = (array)get_option('css3_grid_global_settings');
	if($css3_grid_shortcode_used && $css3_grid_global_options['loadFiles']=='when_used')
	{
		wp_enqueue_style('css3_grid_font_yanone', 'http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz');
		if((int)$css3_grid_load_kind_1)
			wp_enqueue_style('css3_grid_table1_style', plugins_url('table1/css3_grid_style.css', __FILE__));
		if((int)$css3_grid_load_kind_2)
			wp_enqueue_style('css3_grid_table2_style', plugins_url('table2/css3_grid_style.css', __FILE__));
		if((int)$css3_grid_load_responsive)
			wp_enqueue_style('css3_grid_responsive', plugins_url('responsive.css', __FILE__));
	}
}
add_action('wp_footer', 'css3_grid_wp_footer');

function filterArray($value)
{
	return (!empty($value) || $value == '0');
}

function css3_grid_print_shortcode($atts)
{
	global $css3_grid_shortcode_used;
	global $css3_grid_load_responsive;
	global $css3_grid_load_kind_1;
	global $css3_grid_load_kind_2;
	global $css3_grid_load_js;
	global $css3_grid_load_expand_collapse;
	extract(shortcode_atts(array(
		'id' => 'css3_grid_example',
		'columns' => '3',
		'rows' => '9',
		'hiddenrows' => '0',
		'hiddenrowsbuttonexpandtext' => 'Click here to expand!',
		'hiddenrowsbuttoncollapsetext' => 'Click here to collapse!',
		'kind' => '1',
		'style' => '1',
		'hovertype' => 'active',
		'responsive' => '0',
		'responsivehidecaptioncolumn' => '0',
		'responsivesteps' => '3',
		'responsivestepwidth' => '1009|767|479|',
		'responsivebuttonwidth' => '|||',
		'responsiveheaderfontsize' => '|||',
		'responsivepricefontsize' => '|||',
		'responsivepermonthfontsize' => '|||',
		'responsivecontentfontsize' => '|||',
		'responsivebuttonsfontsize' => '|||',
		'pricefontcustom' => '',
		'pricefont' => '',
		'pricefontsubsets' => '',
		'pricefontsize' => '',
		'headerfontcustom' => '',
		'headerfont' => '',
		'headerfontsubsets' => '',
		'headerfontsize' => '',
		'permonthfontcustom' => '',
		'permonthfont' => '',
		'permonthfontsubsets' => '',
		'permonthfontsize' => '',
		'contentfontcustom' => '',
		'contentfont' => '',
		'contentfontsubsets' => '',
		'contentfontsize' => '',
		'buttonsfontcustom' => '',
		'buttonsfont' => '',
		'buttonsfontsubsets' => '',
		'buttonsfontsize' => '',
		'slidingcolumns' => '0',
		'visiblecolumns' => '2',
		'scrollcolumns' => '',
		'slidingnavigation' => '1',
		'slidingnavigationarrows' => '1',
		'slidingarrowsstyle' => 'style1',
		'slidingpagination' => '0',
		'slidingpaginationposition' => 'bottom',
		'slidingpaginationstyle' => 'style1', 
		'slidingontouch' => '1',
		'slidingonmouse' => '0',
		'slidingthreshold' => '75',
		'slidingautoplay' => '0',
		'slidingeffect' => 'scroll',
		'slidingeasing' => 'swing',
		'slidingduration' => '500',
		'widths' => '|||',
		'responsivewidths' => '|||',
		'aligments' => '-1|-1|-1|',
		'actives' => '-1|-1|-1|',
		'hiddens' => '-1|-1|-1|',
		'ribbons' => '-1|-1|-1|',
		'heights' => '|||||||||',
		'responsiveheights' => '|||||||||',
		'paddingstop' => '|||||||||',
		'paddingsbottom' => '|||||||||',
		'texts' => '|<h2 class="col1">starter</h2>|<h2 class="col2">econo</h2>|<h2 class="caption">choose <span>your</span> plan</h2>|<h1 class="col1">$<span>10</span></h1><h3 class="col1">per month</h3>|<h1 class="col1">$<span>30</span></h1><h3 class="col1">per month</h3>|Amount of space|10GB|30GB|Bandwidth per month|100GB|200GB|No. of e-mail accounts|1|10|No. of MySql databases|1|10|24h support|Yes|Yes|Support tickets per mo.|1|3||<a href="' . get_site_url() . '?plan=1" class="sign_up radius3">sign up!</a>|<a href="' . get_site_url() . '?plan=2" class="sign_up radius3">sign up!</a>',
		'tooltips' => '|||||||||'
	), $atts));
	if($id=="")
		$id = "sample";
	$responsiveStepWidth = array_filter(explode("|", $responsivestepwidth), 'filterArray');
	$responsiveButtonWidth = array_filter(explode("|", $responsivebuttonwidth), 'filterArray');
	$responsiveHeaderFontSize = array_filter(explode("|", $responsiveheaderfontsize), 'filterArray');
	$responsivePriceFontSize = array_filter(explode("|", $responsivepricefontsize), 'filterArray');
	$responsivePermonthFontSize = array_filter(explode("|", $responsivepermonthfontsize), 'filterArray');
	$responsiveContentFontSize = array_filter(explode("|", $responsivecontentfontsize), 'filterArray');
	$responsiveButtonsFontSize = array_filter(explode("|", $responsivebuttonsfontsize), 'filterArray');
	$widths = explode("|", $widths);
	$responsiveWidths = array_filter(explode("|", $responsivewidths), 'filterArray');
	$aligments = explode("|", $aligments);
	$actives = explode("|", $actives);
	$hiddens = explode("|", $hiddens);
	$ribbons = explode("|", $ribbons);
	$heights = array_filter(explode("|", $heights), 'filterArray');
	$responsiveHeights = array_filter(explode("|", $responsiveheights), 'filterArray');
	$headerFontSubsets = array_filter(explode("|", $headerfontsubsets), 'filterArray');
	$priceFontSubsets = array_filter(explode("|", $pricefontsubsets), 'filterArray');
	$permonthFontSubsets = array_filter(explode("|", $permonthfontsubsets), 'filterArray');
	$contentFontSubsets = array_filter(explode("|", $contentfontsubsets), 'filterArray');
	$buttonsFontSubsets = array_filter(explode("|", $buttonsfontsubsets), 'filterArray');
	if((int)$responsive)
		$css3_grid_load_responsive = 1;
	if((int)$kind==1)
		$css3_grid_load_kind_1 = 1;
	if((int)$kind==2)
		$css3_grid_load_kind_2 = 1;
	$output = "";

	if($pricefontcustom!="" || $pricefont!="" || (int)$pricefontsize>0 || $headerfontcustom!="" || $headerfont!="" || (int)$headerfontsize>0 || $permonthfontcustom!="" || $permonthfont!="" || (int)$permonthfontsize>0 || $contentfontcustom!="" || $contentfont!="" || (int)$contentfontsize>0 || $buttonsfontcustom!="" || $buttonsfont!="" || (int)$buttonsfontsize>0)
	{
		$fontStyles = "";
		$fontsGoogleUrl = "";
		$joinedSubsets = array();
		$headerFont = $headerfontcustom;
		if($headerfont!="" || $headerfontcustom!="" || (int)$headerfontsize>0)
		{
			if($headerfont!="")
			{
				$headerFontExplode = explode(":", $headerfont);
				$headerFont = $headerFontExplode[0];
				$fontsGoogleUrl .= $headerfont;
				$joinedSubsets = array_unique(array_merge((array)$headerFontSubsets, $joinedSubsets));
			}
			$fontStyles .= 'div.p_table_' . $kind . '#' . $id . ' h2' . ($kind==1 ? ', div.p_table_' . $kind . '#' . $id . ' h2 span' : '') . '{' . ($headerFont!="" ? 'font-family: "' . $headerFont . '" !important;' : '') . ((int)$headerfontsize>0 ? 'font-size: ' . (int)$headerfontsize . 'px !important;' : '') . '}';
		}
		$priceFont = $pricefontcustom;
		if($pricefont!="" || $pricefontcustom!="" || (int)$pricefontsize>0)
		{
			if($pricefont!="")
			{
				$priceFontExplode = explode(":", $pricefont);
				$priceFont = $priceFontExplode[0];
				$fontsGoogleUrl .= ($fontsGoogleUrl!="" ? '|' : '') . $pricefont;
				$joinedSubsets = array_unique(array_merge((array)$priceFontSubsets, $joinedSubsets));
			}
			$fontStyles .= 'div.p_table_' . $kind . '#' . $id . ' h1' . ($kind==1 ? ', div.p_table_' . $kind . '#' . $id . ' h1 span' : '') . '{' . ($priceFont!="" ? 'font-family: "' . $priceFont . '" !important;' : '') . ((int)$pricefontsize>0 ? 'font-size: ' . (int)$pricefontsize . 'px !important;' : '') . '}';
		}
		$permonthFont = $permonthfontcustom;
		if($permonthfont!="" || $permonthfontcustom!="" || (int)$permonthfontsize>0)
		{
			if($permonthfont!="")
			{
				$permonthFontExplode = explode(":", $permonthfont);
				$permonthFont = $permonthFontExplode[0];
				$fontsGoogleUrl .= ($fontsGoogleUrl!="" ? '|' : '') . $permonthfont;
				$joinedSubsets = array_unique(array_merge((array)$permonthFontSubsets, $joinedSubsets));
			}
			$fontStyles .= 'div.p_table_' . $kind . '#' . $id . ' h3{' . ($permonthFont!="" ? 'font-family: "' . $permonthFont . '" !important;' : '') . ((int)$permonthfontsize>0 ? 'font-size: ' . (int)$permonthfontsize . 'px !important;' : '') . '}';
		}
		$contentFont = $contentfontcustom;
		if($contentfont!="" || $contentfontcustom!="" || (int)$contentfontsize>0)
		{
			if($contentfont!="")
			{
				$contentFontExplode = explode(":", $contentfont);
				$contentFont = $contentFontExplode[0];
				$fontsGoogleUrl .= ($fontsGoogleUrl!="" ? '|' : '') . $contentfont;
				$joinedSubsets = array_unique(array_merge((array)$contentFontSubsets, $joinedSubsets));
			}
			$fontStyles .= 'div.p_table_' . $kind . '#' . $id . ' li.row_style_1 span, div.p_table_' . $kind . '#' . $id . ' li.row_style_2 span, div.p_table_' . $kind . '#' . $id . ' li.row_style_3 span, div.p_table_' . $kind . '#' . $id . ' li.row_style_4 span{' . ($contentFont!="" ? 'font-family: "' . $contentFont . '" !important;' : '') . ((int)$contentfontsize>0 ? 'font-size: ' . (int)$contentfontsize . 'px !important;' : '') . '}';
		}
		$buttonsFont = $buttonsfontcustom;
		if($buttonsfont!="" || $buttonsfontcustom!="" || (int)$buttonsfontsize>0)
		{
			if($buttonsfont!="")
			{
				$buttonsFontExplode = explode(":", $buttonsfont);
				$buttonsFont = $buttonsFontExplode[0];
				$fontsGoogleUrl .= ($fontsGoogleUrl!="" ? '|' : '') . $buttonsfont;
				$joinedSubsets = array_unique(array_merge((array)$buttonsFontSubsets, $joinedSubsets));
			}
			$fontStyles .= ($kind==1 ? 'div.p_table_' . $kind . '#' . $id . ' a.sign_up, div.p_table_' . $kind . '#' . $id . ' .css3_grid_hidden_rows_control span' : 'div.p_table_' . $kind . '#' . $id . ' a.button_1, div.p_table_' . $kind . '#' . $id . ' a.button_2, div.p_table_' . $kind . '#' . $id . ' a.button_3, div.p_table_' . $kind . '#' . $id . ' a.button_4, div.p_table_' . $kind . '#' . $id . ' .css3_grid_hidden_rows_control span') . '{' . ($buttonsFont!="" ? 'font-family: "' . $buttonsFont . '" !important;' : '') . ((int)$buttonsfontsize>0 ? 'font-size: ' . (int)$buttonsfontsize . 'px !important;' : '') . '}';
		}
		
		/*if($priceFont!="" && $headerFont!="")
		{
			if($priceFont!=$headerFont)
			{
				if($headerFont!="")
					$output .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $headerfont . '&subset=' . implode(",", (array)$headerFontSubsets) . '">';
				if($priceFont!="")
					$output .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $pricefont . '&subset=' . implode(",", (array)$priceFontSubsets) . '">';
			}
			else
			{
				$joinedSubsets = array_unique(array_merge((array)$headerFontSubsets, (array)$priceFontSubsets));
				$output .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $headerfont . '&subset=' . implode(",", (array)$joinedSubsets) . '">';
			}
		}*/
		if($fontsGoogleUrl!="")
			$output .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $fontsGoogleUrl . '&subset=' . implode(",", (array)$joinedSubsets) . '">';
		$output .= '<style type="text/css">' . $fontStyles . '</style>';
	}
	if((int)$responsive && count($responsiveStepWidth) && (count($responsiveWidths) || count($responsiveHeights) || count($responsiveButtonWidth) || count($responsiveHeaderFontSize)))
	{
		$output .= '<style type="text/css">';
		for($i=0; $i<count($responsiveStepWidth); $i++)
		{
			$output .= '@media screen and (max-width:' . $responsiveStepWidth[$i] . 'px)
			{';
			if(count($responsiveWidths))
			{
				foreach($responsiveWidths as $key=>$responsiveWidth)
				{
					if($key%(int)$responsivesteps==$i)
						$output .= 'div.p_table_responsive#' . $id . ' div.column_' . floor($key/(int)$responsivesteps) . '_responsive
						{
							width: ' . $responsiveWidth . (substr($responsiveWidth, -1)!="%" && substr($responsiveWidth, -2)!="px" ? "px" : "") . ' !important;' . ((int)$responsiveWidth==0 ? 'display: none;' : '') . '
						}';
				}
			}
			if(count($responsiveHeights))
			{
				foreach($responsiveHeights as $key=>$responsiveHeight)
				{
					if($key%(int)$responsivesteps==$i)
						$output .= 'div.p_table_responsive#' . $id . ' li.css3_grid_row_' . floor($key/(int)$responsivesteps) . '_responsive
						{
							height: ' . (int)$responsiveHeight . 'px !important;' . ((int)$responsiveHeight==0 ? 'display: none;' : '') . '
						}';
				}
			}
			if((int)$responsiveButtonWidth[$i]>0)
			{
				$output .= 'div.p_table_responsive#' . $id . ' a.sign_up
				{
					width: ' . (int)$responsiveButtonWidth[$i] . 'px;
				}';
			}
			if((int)$responsiveHeaderFontSize[$i]>0)
				$output .= 'div.p_table_' . $kind . '#' . $id . ' h2' . ($kind==1 ? ', div.p_table_' . $kind . '#' . $id . ' h2 span' : '') . '{font-size: ' . (int)$responsiveHeaderFontSize[$i] . 'px !important;}';
			if((int)$responsivePriceFontSize[$i]>0)
				$output .= 'div.p_table_' . $kind . '#' . $id . ' h1' . ($kind==1 ? ', div.p_table_' . $kind . '#' . $id . ' h1 span' : '') . '{font-size: ' . (int)$responsivePriceFontSize[$i] . 'px !important;}';
			if((int)$responsivePermonthFontSize[$i]>0)
				$output .= 'div.p_table_' . $kind . '#' . $id . ' h3{font-size: ' . (int)$responsivePermonthFontSize[$i] . 'px !important;}';
			if((int)$responsiveContentFontSize[$i]>0)
				$output .= 'div.p_table_' . $kind . '#' . $id . ' li.row_style_1 span, div.p_table_' . $kind . '#' . $id . ' li.row_style_2 span, div.p_table_' . $kind . '#' . $id . ' li.row_style_3 span, div.p_table_' . $kind . '#' . $id . ' li.row_style_4 span{font-size: ' . (int)$responsiveContentFontSize[$i] . 'px !important;}';
			if((int)$responsiveButtonsFontSize[$i]>0)
				$output .= ($kind==1 ? 'div.p_table_' . $kind . '#' . $id . ' a.sign_up, div.p_table_' . $kind . '#' . $id . ' .css3_grid_hidden_rows_control span' : 'div.p_table_' . $kind . '#' . $id . ' a.button_1, div.p_table_' . $kind . '#' . $id . ' a.button_2, div.p_table_' . $kind . '#' . $id . ' a.button_3, div.p_table_' . $kind . '#' . $id . ' a.button_4, div.p_table_' . $kind . '#' . $id . ' .css3_grid_hidden_rows_control span') . '{font-size: ' . (int)$responsiveButtonsFontSize[$i] . 'px !important;}';
			
			$output .= '}';
		}
		$output .= '</style>';
	}
	$paddingsTop = explode("|", $paddingstop);
	$paddingsBottom = explode("|", $paddingsbottom);
	$texts = explode("|", $texts);
	for($i=0; $i<count($texts); $i++)
		$texts[$i] = str_replace("&#93;", "]", str_replace("&#91;", "[", str_replace("&#39;", "'", $texts[$i])));
	$tooltips = explode("|", $tooltips);
	for($i=0; $i<count($tooltips); $i++)
		$tooltips[$i] = str_replace("&#93;", "]", str_replace("&#91;", "[", str_replace("&#39;", "'", $tooltips[$i])));
	if((int)$slidingcolumns && (int)$visiblecolumns>0)
	{
		$css3_grid_load_js = 1;
		if((int)$kind==1)
			$hovertype = "disabled";
	}
	if((int)$hiddenrows>0)
		$css3_grid_load_expand_collapse = 1;
	//$output = '<link rel="stylesheet" type="text/css" href="' . plugins_url('table' . $kind . '/main.css', __FILE__) . '"/>';
	//$output .= '<link rel="stylesheet" type="text/css" href="' . plugins_url('table' . $kind . '/style_' . $style . '.css', __FILE__) . '"/>';
	if((int)$slidingcolumns && (int)$visiblecolumns>0)
	{
		if((int)$slidingpagination && ($slidingpaginationposition=="top" || $slidingpaginationposition=="both"))
			$output .= "<div class='css3_grid_pagination css3_grid_" . $id . "_pagination css3_grid_pagination_" . $slidingpaginationstyle . "'></div>";
		$output .= "<div id='css3_grid_" . $id . "_slider_container' class='css3_grid_slider_container css3_grid_clearfix'>";
		if((int)$slidingnavigation && (int)$slidingnavigationarrows)
			$output .= "<div class='css3_grid_arrow_area'><a id='css3_grid_" . $id . "_prev' href='#css3_grid_" . $id . "_prev' class='css3_grid_slide_button_prev css3_grid_slide_button_" . $slidingarrowsstyle . "'></a></div>";
	}
	$output .= '<div id="' . $id . '" class="' . ((int)$responsive ? 'p_table_responsive ' : '') . ((int)$responsivehidecaptioncolumn ? 'p_table_hide_caption_column ' : '') . ((int)$slidingcolumns && (int)$visiblecolumns>0 ? 'p_table_sliding ' : '') . 'p_table_' . $kind . ' p_table_' . $kind . '_' . $style . ' css3_grid_clearfix' . ($hovertype!="active" ? ' p_table_hover_' . $hovertype : '') . '">';
	$countValues = array_count_values($hiddens);
	$totalColumns = $countValues["-1"];
	$currentColumn = 0;
	for($i=0; $i<$columns; $i++)
	{
		if($hiddens[$i]!=1)
		{
			if($i==0)
				$output .= '<div class="caption_column' . ((int)$actives[0]==1 && !((int)$slidingcolumns && (int)$visiblecolumns>0 && (int)$kind==1) ? ' active_column' : '') . ((int)$responsive ? ' column_' . $i . '_responsive' : '') . '"' . ($widths[0]>0 ? ' style="width: ' . $widths[0] . (substr($widths[0], -1)!="%" && substr($widths[0], -2)!="px" ? "px" : "") . ';"' : '') . '>';
			else
			{
				if($i==1 && (int)$slidingcolumns && (int)$visiblecolumns>0)
					$output .= '<div class="css3_grid_slider id-' . $id . ' autoplay-' . $slidingautoplay . ' effect-' . $slidingeffect . ' easing-' . $slidingeasing . ' duration-' . $slidingduration . ' items-' . $visiblecolumns . ' scroll-' . ((int)$scrollcolumns>0 ? (int)$scrollcolumns : (int)$visiblecolumns) . ((int)$slidingontouch ? ' ontouch' : '') . ((int)$slidingonmouse ? ' onmouse' : '') . ((int)$slidingontouch || (int)$slidingonmouse ? ' threshold-' . $slidingthreshold : '') . ((int)$slidingpagination ? ' pagination' : '') . '">';
				$output .= '<div class="column_' . ($i%4==0 ? 4 : $i%4) . ((int)$actives[$i]==1 && !((int)$slidingcolumns && (int)$visiblecolumns>0 && (int)$kind==1) ? ' active_column' : '') . ((int)$responsive ? ' column_' . $i . '_responsive' : '') . '"' . ($widths[$i]>0 ? ' style="width: ' . $widths[$i] . (substr($widths[$i], -1)!="%" && substr($widths[$i], -2)!="px" ? "px" : "") . ';"' : '') . '>';
			}
			if((int)$ribbons[$i]!=-1)
				$output .= '<div class="column_ribbon ribbon_' . $ribbons[$i] . '"></div>';
			$output .= '<ul>';
			for($j=0; $j<$rows; $j++)
			{
				if($j<2)
				{
					if($j==0)
						$output .= '<li' . ((int)$aligments[$i]!=-1 || isset($heights[$j]) || (int)$paddingsTop[$j]>0 || (int)$paddingsBottom[$j]>0 ? ' style="' . ((int)$aligments[$i]!=-1 ? 'text-align: ' . $aligments[$i] . ';' : '') . (isset($heights[$j]) ? 'height: ' . (int)$heights[$j] . 'px;' . ((int)$heights[$j]==0 ? 'display: none;' : '') : '') . ((int)$paddingsTop[$j]>0 ? 'padding-top: ' . $paddingsTop[$j] . 'px !important;' : '') . ((int)$paddingsBottom[$j]>0 ? 'padding-bottom: ' . $paddingsBottom[$j] . 'px !important;' : '') . '"' : '') . ' class="css3_grid_row_' . $j . ' header_row_1 align_center' . ((int)$responsive ? ' css3_grid_row_' . $j . '_responsive' : '') . ($currentColumn==0 && (int)$kind==1 ? ' radius5_topleft' : (($currentColumn==0 && $hiddens[0]==1) || ($currentColumn==1 && $hiddens[0]==-1) && (int)$kind==2 ? ' radius5_topleft' : '')) . ($currentColumn+1==$totalColumns ? ' radius5_topright' : '') . '">' . do_shortcode(($tooltips[$j*$columns+$i]!="" ? '<span class="css3_grid_tooltip"><span>' . $tooltips[$j*$columns+$i] . '</span>' : '' ) . $texts[$j*$columns+$i] . ($tooltips[$j*$columns+$i]!="" ? '</span>' : '' )) . '</li>';
					else if($j==1)
					{
						if((int)$kind==2)
							$output .= '<li class="decor_line"></li>';
						$output .= '<li' . ((int)$aligments[$i]!=-1 || isset($heights[$j]) || (int)$paddingsTop[$j]>0 || (int)$paddingsBottom[$j]>0 ? ' style="' . ((int)$aligments[$i]!=-1 ? 'text-align: ' . $aligments[$i] . ';' : '') . (isset($heights[$j]) ? 'height: ' . (int)$heights[$j] . 'px;' . ((int)$heights[$j]==0 ? 'display: none;' : '') : '') . ((int)$paddingsTop[$j]>0 ? 'padding-top: ' . $paddingsTop[$j] . 'px !important;' : '') . ((int)$paddingsBottom[$j]>0 ? 'padding-bottom: ' . $paddingsBottom[$j] . 'px !important;' : '') . '"' : '') . ' class="css3_grid_row_' . $j . ' header_row_2' . ((int)$responsive ? ' css3_grid_row_' . $j . '_responsive' : '') . (($currentColumn==0 && $hiddens[0]==1) || ($currentColumn==1 && $hiddens[0]==-1) && (int)$kind==2 ? ' radius5_bottomleft' : '') . ($currentColumn+1==$totalColumns && (int)$kind==2 ? ' radius5_bottomright' : '') . ($i!=0 ? ' align_center':'') . '"><span class="css3_grid_vertical_align_table"><span class="css3_grid_vertical_align">' . do_shortcode(($tooltips[$j*$columns+$i]!="" ? '<span class="css3_grid_tooltip"><span>' . $tooltips[$j*$columns+$i] . '</span>' : '' ) . $texts[$j*$columns+$i] . ($tooltips[$j*$columns+$i]!="" ? '</span>' : '' )) .  '</span></span></li>';
					}
				}
				else if($j+1==$rows)
				{
					$output .= '<li' . ((int)$aligments[$i]!=-1 || isset($heights[$j]) || (int)$paddingsTop[$j]>0 || (int)$paddingsBottom[$j]>0 ? ' style="' . ((int)$aligments[$i]!=-1 ? 'text-align: ' . $aligments[$i] . ';' : '') . (isset($heights[$j]) ? 'height: ' . (int)$heights[$j] . 'px;' . ((int)$heights[$j]==0 ? 'display: none;' : '') : '') . ((int)$paddingsTop[$j]>0 ? 'padding-top: ' . $paddingsTop[$j] . 'px !important;' : '') . ((int)$paddingsBottom[$j]>0 ? 'padding-bottom: ' . $paddingsBottom[$j] . 'px !important;' : '') . '"' : '') . ' class="css3_grid_row_' . $j . ' footer_row' . ((int)$responsive ? ' css3_grid_row_' . $j . '_responsive' : '') . ($currentColumn+1==$totalColumns && (int)$kind==2 ? ' radius5_bottomright' : '') . '"><span class="css3_grid_vertical_align_table"><span class="css3_grid_vertical_align">' . do_shortcode((isset($tooltips[$j*$columns+$i]) && $tooltips[$j*$columns+$i]!="" ? '<span class="css3_grid_tooltip"><span>' . $tooltips[$j*$columns+$i] . '</span>' : '' ) . $texts[$j*$columns+$i] . (isset($tooltips[$j*$columns+$i]) && $tooltips[$j*$columns+$i]!="" ? '</span>' : '' )) .  '</span></span></li>';
				}
				else
				{
					$output .= '<li' . ((int)$aligments[$i]!=-1 || isset($heights[$j]) || (int)$paddingsTop[$j]>0 || (int)$paddingsBottom[$j]>0 ? ' style="' . ((int)$aligments[$i]!=-1 ? 'text-align: ' . $aligments[$i] . ';' : '') . (isset($heights[$j]) ? 'height: ' . (int)$heights[$j] . 'px;' . ((int)$heights[$j]==0 ? 'display: none;' : '') : '') . ((int)$paddingsTop[$j]>0 ? 'padding-top: ' . $paddingsTop[$j] . 'px !important;' : '') . ((int)$paddingsBottom[$j]>0 ? 'padding-bottom: ' . $paddingsBottom[$j] . 'px !important;' : '') . '"' : '') . ' class="css3_grid_row_' . $j . ' row_style_' . ($i%2==0 && $j%2==0 ? ((int)$kind==1 ? '4' : '1') : ($i%2==0 && $j%2==1 ? ((int)$kind==1 ? '2' : '3'): ($i%2==1 && $j%2==0 ? ((int)$kind==1 ? '3' : '1') : ((int)$kind==1 ? '1' : '2')))) . ((int)$responsive ? ' css3_grid_row_' . $j . '_responsive' : '') . ($i>0 ? ' align_center' : '' ) . ((int)$rows-$j-2<(int)$hiddenrows ? ' css3_grid_hidden_row css3_grid_hide' : '') . '"><span class="css3_grid_vertical_align_table"><span class="css3_grid_vertical_align"><span>'. ((int)$responsive && (int)$responsivehidecaptioncolumn ? '<span class="css3_hidden_caption">' . $texts[$j*$columns] . '</span>' : '') . do_shortcode((isset($tooltips[$j*$columns+$i]) && $tooltips[$j*$columns+$i]!="" ? '<span class="css3_grid_tooltip"><span>' . $tooltips[$j*$columns+$i] . '</span>' : '' ) . $texts[$j*$columns+$i] . (isset($tooltips[$j*$columns+$i]) && $tooltips[$j*$columns+$i]!="" ? '</span>' : '' )) . '</span></span></span></li>';
				}
			}
			$output .= '</ul></div>';
			$currentColumn++;
		}
	}
	if((int)$slidingcolumns && (int)$visiblecolumns>0)
		$output .= '</div>';
	if((int)$hiddenrows>0)
		$output .= "<a class='css3_grid_hidden_rows_control css3_grid_hidden_rows_control_p_table_" . $kind . "_" . $style ." css3_grid_hidden_rows_control_" . $id . "' href='#'><span class='css3_grid_hidden_rows_control_expand_text'>" . $hiddenrowsbuttonexpandtext . "</span><span class='css3_grid_hidden_rows_control_collapse_text css3_grid_hide'>" . $hiddenrowsbuttoncollapsetext . "</span></a>";
	
	$output .= "</div>";
	if((int)$slidingcolumns && (int)$visiblecolumns>0)
	{
		if((int)$slidingnavigation && (int)$slidingnavigationarrows)
			$output .= "<div class='css3_grid_arrow_area'><a id='css3_grid_" . $id . "_next' href='#css3_grid_" . $id . "_next' class='css3_grid_slide_button_next css3_grid_slide_button_" . $slidingarrowsstyle . "'></a></div>";
		$output .= "</div>";
	}
	if((int)$slidingcolumns && (int)$visiblecolumns>0)
	{
		if((int)$slidingpagination && ($slidingpaginationposition=="bottom" || $slidingpaginationposition=="both"))
			$output .= "<div class='css3_grid_pagination css3_grid_" . $id . "_pagination css3_grid_pagination_" . $slidingpaginationstyle . "'></div>";
	}
	$css3_grid_shortcode_used = true;
	return $output;
}
add_shortcode('css3_grid_print', 'css3_grid_print_shortcode');
?>
<?php include('img/social.png'); ?>