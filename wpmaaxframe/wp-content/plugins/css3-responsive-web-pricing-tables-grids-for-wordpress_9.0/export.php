<?php
header('Content-type: application/json');
header("Content-Description: File Transfer");
header("Content-Transfer-Encoding: binary");
header('Content-disposition: attachment; filename=css3_web_pricing_tables_grids_export.txt');
function css3_grid_stripslashes_deep($value)
{
	$value = is_array($value) ?
				array_map('stripslashes_deep', $value) :
				stripslashes($value);

	return $value;
}
$fileContent = "";
require_once("../../../wp-config.php");
$wp->init();
$wp->parse_request();
$wp->query_posts();
$wp->register_globals();
$wp->send_headers();
if(current_user_can("manage_options"))
{
	if($_GET["action"]=="export_to_file")
	{
		$idsCount = count($_GET["exportIds"]);
		$optionsArray = array();
		for($i=0; $i<$idsCount; $i++)
		{
			$optionsArray[$i] = css3_grid_stripslashes_deep(get_option($_GET["exportIds"][$i]));
			$optionsArray[$i]["name"] = $_GET["exportIds"][$i];
		}
		$fileContent .= @json_encode($optionsArray);
	}
}
else
	$fileContent = "You don't have permissions to export!";
header('Content-Length: ' . strlen($fileContent));
echo $fileContent;
?>