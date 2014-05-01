<?php

	if(empty($_POST['kpt_hidden']))
		{
			$kpt_column_width = get_option( 'kpt_column_width' );
			$kpt_bg_color = get_option( 'kpt_bg_color' );		
			$kpt_total_column = get_option( 'kpt_total_column' );	
			$kpt_total_row = get_option( 'kpt_total_row' );
			$kpt_table_field = stripslashes_deep(get_option( 'kpt_table_field' ));
			$kpt_table_bg_img = stripslashes_deep(get_option( 'kpt_table_bg_img' ));			
		}

	else
		{
		
		if($_POST['kpt_hidden'] == 'Y')
			{
			//Form data sent
			$kpt_column_width = $_POST['kpt_column_width'];
			update_option('kpt_column_width', $kpt_column_width);
			
			$kpt_bg_color = $_POST['kpt_bg_color'];
			update_option('kpt_bg_color', $kpt_bg_color);
			
			$kpt_total_column =  sanitize_text_field($_POST['kpt_total_column']);
			update_option('kpt_total_column', $kpt_total_column);
			
			$kpt_total_row =  sanitize_text_field($_POST['kpt_total_row']);
			update_option('kpt_total_row', $kpt_total_row);			

			if(empty($_POST['kpt_table_bg_img']))
				{
					$kpt_table_bg_img ="";
				}
			else
				{
					$kpt_table_bg_img =  $_POST['kpt_table_bg_img'];
				}
			update_option('kpt_table_bg_img', $kpt_table_bg_img);	

			if(empty($_POST['kpt_table_field']))
				{
				$kpt_table_field ="";
				}
			else
				{
			$kpt_table_field =  stripslashes_deep($_POST['kpt_table_field']);
				}
			update_option('kpt_table_field', $kpt_table_field);	

			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p>
            </div>
            
            
            
            
<?php
			}
		} 
?>


<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__('Kento Pricing Table Settings')."</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="kpt_hidden" value="Y">
        <?php settings_fields( 'kpt_plugin_options' );
				do_settings_sections( 'kpt_plugin_options' );
			
		?>


<table class="form-table">


<?php
	if(!empty($_POST['kpt_hidden']))
		{
?>

	<tr valign="top">
		<th scope="row"><label for="kpt-column-shortcodes"><?php echo __('Use ShortCodes'); ?>: </label></th>
		<td style="vertical-align:middle;"> 
        <input  type="text" name="kpt_column_shortcodes" onClick="this.select();" size="auto" id="kpt-column-shortcodes"  value ="[kpt]"  ><br /> ** Use this shortcode to display pricing table to post or page.                    

		</td>
	</tr> 
<?php } ?>

	<tr valign="top">
		<th scope="row"><label for="kpt-column-width"><?php echo __('Table Column Width'); ?>: </label></th>
		<td style="vertical-align:middle;">                     
                     <input size='10' name='kpt_column_width' class='kpt-column-width' id="kpt-column-width" type='text' value='<?php echo sanitize_text_field($kpt_column_width) ?>' />px (number only)
		</td>
	</tr> 
    
	<tr valign="top">
		<th scope="row"><label for="kpt-column-width"><?php echo __('Display Background Image'); ?>: </label></th>
		<td style="vertical-align:middle;">                     
                     <input  name='kpt_table_bg_img' class='kpt-table-bg-img' id="kpt-table-bg-img"  type="checkbox" value='1' <?php  if($kpt_table_bg_img==1) echo "checked"; ?> /> **this will display/hide background image on table area.
		</td>
	</tr>     
    


	<tr valign="top">
		<th scope="row"><label for="kpt-bg-color"><?php echo __('Background Color'); ?>: </label></th>
		<td style="vertical-align:middle;">                     
                     <input size='10' name='kpt_bg_color' class='kpt-bg-color' id="kpt-bg-color" type='text' value='<?php echo sanitize_text_field($kpt_bg_color) ?>' />
		</td>
	</tr>        

    
	<tr valign="top">
		<th scope="row"><label for="kpt-total-column"><?php echo __('How Many Column'); ?>: </label></th>
		<td style="vertical-align:middle;">
        
        
<input size="3" name="kpt_total_column" id="kpt-total-column" type="text" value="<?php if ( isset( $kpt_total_column ) ) echo $kpt_total_column; ?>" /> **Click outside the box to update table
        

		</td>
	</tr>
    
	<tr valign="top">
		<th scope="row"><label for="kpt-total-row"><?php echo __('How Many Row'); ?>: </label></th>
		<td style="vertical-align:middle;">
        
        
<input size="3" name="kpt_total_row" id="kpt-total-row" type="text" value="<?php if ( isset( $kpt_total_row ) ) echo $kpt_total_row; ?>" /> **Click outside the box to update table
        

		</td>
	</tr>    
    
    <tr valign="top">
		<th scope="row">Table Data:
		</th>
		<td style="vertical-align:middle;">
        <div id="kpt-total-data">
<?php


echo "<table class='price-table-admin' >";
for($j=1; $j<=$kpt_total_row; $j++)
  {
  echo "<tr>";
  
		for($i=1; $i<=$kpt_total_column; $i++)
			{
				echo "<td>";
				echo "<input size='10' name='kpt_table_field[".$i.$j."]' class='kpt-table-field-".$i.$j."'' type='text' value='".$kpt_table_field[$i.$j]."' />";
				
				
				
				echo "</td>";
				
			}
  echo "</tr>";
  }


	echo "</table>";



?>                         
		</div>  
		</td>
	</tr>
</table>
                <p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes' ) ?>" />
                </p>
		</form>
      
      

      
      <script>
jQuery(document).ready(function(jQuery)
	{	
		jQuery('.kpt-bg-color').wpColorPicker();
	});
</script> 


</div>
