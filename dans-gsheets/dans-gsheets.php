<?php

/**
* Plugin Name: Dan's GSheets Data Embedder
* Plugin URI: https://www.wptechguides.com
* Description: A custom Google Sheets Data Embedder
* Version: 1.0
* Author: Dan Dulaney
* Author URI: https://www.convexcode.com
**/

//creates an entry on the admin menu for dan-gsheet-plugin
add_action('admin_menu', 'dans_gsheets_plugin_menu');
//creates a menu page with the following settings
function dans_gsheets_plugin_menu() {
	add_submenu_page('tools.php', 'Dan\'s gSheets', 'Dan\'s gSheets', 'manage_options', 'dans-gsheets-settings', 'dans_gsheets_display_settings');

}
//on-load, sets up the following settings for the plugin
add_action( 'admin_init', 'dans_gsheets_settings' );
function dans_gsheets_settings() {
	register_setting( 'dans-gsheets-settings-group', 'gsheets_api_key' ); //api key
	register_setting( 'dans-gsheets-settings-group', 'gsheets_sheetsids' ); //array of sheet ids
}
//displays the settings page
function dans_gsheets_display_settings() {
	//form to save api key and sheet settings
	echo "<form method=\"post\" action=\"options.php\">";
	settings_fields( 'dans-gsheets-settings-group' );
	do_settings_sections( 'dans-gsheets-settings-group' );
echo "<script>function addRow(nextnum,nextdisp){
	var toremove = 'addrowbutton';
	var elem = document.getElementById(toremove);
    elem.parentNode.removeChild(elem);
	var table = document.getElementById(\"gsheets-settings\");
	var row = table.insertRow(-1);
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	c1var = '<b>Google Sheet ID ('+nextdisp+')</b>';
	cell1.innerHTML = c1var;
	var newnextdisp= nextdisp+1;
	c2var = '<input type=\"text\" name=\"gsheets_sheetsids['+nextnum+']\" size=\"80\"><button type=\"button\" id=\"addrowbutton\" onClick=\"addRow('+nextdisp+','+newnextdisp+')\">Add Row</button>';
	cell2.innerHTML = c2var;
}</script>
";
	//paragraph giving plugin explanation, api setup instructions, and shortcode information
    echo "	
	<div><h1>Dan's Google Sheets Settings</h1>
<p>Welcome! This is a basic Google Sheets integration plugin, with the following features. <ul style=\"list-style-type:square\">
<li>Displays cells or ranges of cells from any public Google Sheet</li>
<li>Individual cells are displayed as spans</li>
<li>Spans of cells displayed as tables, with optional headers</li>
<li>All options are configured via shortcode</li>
<li>Option to set custom class on a per-item basis for styling</li> 
</ul>
<br>
<b>Shortcodes:</b>
<ul style=\"list-style-type:square\"><li>Basic: [dansheet] (defaults to first document, default tab name)</li>
<li>Single Cell: <code>[dansheet file=1 sheetname=\"Sheet1\" cell=A1 class=\"gsheets-special\"]</code></li>
<li><code>Range of Cells: <code>[dansheet file=1 sheetname=Sheet1 cell=A1:C2 theaders=\"Col 1,Col 2,Col 3\" class=\"gsheets-special2\"]</code></li>

</ul>


Optional Attributes 
<ul>
<li>file=# (number of the Google Doc you have set in the settings page)
<li>sheetname= name of sheet in doc</li>
<li>cell= Cell Number or range, with : </li>
<li>class=”custom class name or names here </li>
</ul>
<br>
To create API key, visit <a href=\"https://console.developers.google.com/\" target=\"_blank\">Google Developers Console</a> Then, follow bellow;
<ul style=\"list-style-type:square\"><li>Create new project (or use project you created before).</li><li>Check \"APIs & auth\" -> \"Credentials\" on side menu.</li><li>Hit \"Create new Key\" button on \"Public API access\" section.</li><li>Choose \"Browser key\" and keep blank on referer limitation.</li></ul>
</p>";
//Settings to be saved
echo "
<table id=\"gsheets-settings\" class=\"form-table\" aria-live=\"assertive\">
	<tr><td colspan=\"2\"><h2>API KEY - Google Sheet Viewer (All REQUIRED)</h2></td></tr> 
       <tr valign=\"top\">
        <th scope=\"row\">Google Sheets API Key</th>
        <td><input type=\"text\" name=\"gsheets_api_key\" size=\"80\" value=\"".esc_attr( get_option('gsheets_api_key') )."\" /></td></tr>
<tr><td colspan=\"2\"><h2>Google Sheets Folder IDs</h2></td></tr>";
$gsheets_sheetsids = get_option('gsheets_sheetsids');
$num_sheets = 0;
$num_sheets = count($gsheets_sheetsids);
if ($num_sheets > 1) $showrows=$num_sheets; 
else $showrows = 1;
for ($i=0;$i < $showrows; $i++) {
	$nextid = $i+1;
	$nextdisp = $i+2;
	$sheetnum = $i+1;
	echo " 
       <tr valign=\"top\">
        <th scope=\"row\">Google Sheet ID ($sheetnum)</th>
        <td><input type=\"text\" name=\"gsheets_sheetsids[$i]\" size=\"80\" value=\"$gsheets_sheetsids[$i]\"/>
";
if (($showrows -1) == $i) {
echo "<button type=\"button\" id=\"addrowbutton\" onClick=\"addRow($nextid,$nextdisp)\">Add Row</button>";
}
echo "</td></tr>";
}
       
   echo" </table>";
    
    submit_button();
	echo "</form>";
}
//function displays folder on shortcode base: [dansheet]
function dansheet_display($atts) {
	$gsheets_api_key = esc_attr( get_option('gsheets_api_key') );
	if ($gsheets_api_key == '') { 
		
		$error = 'You must first enter a valid Google Sheets API key.';
		return $error;
	} 
	//Handles attribures. If none are specified, defaults to no scroll, 1st sheet	
	$atts = shortcode_atts(
        array(
            'file' => 1,
		  'sheetname' => 'Sheet1',
		  'cell' => '',
		  'theaders' => '',
		  'class' => ''
        ), $atts, 'dansheet' );

	$file = $atts['file'];
	$sheetname = $atts['sheetname'];

	$cell = $atts['cell'];
	$theaders = $atts['theaders'];
	$class = $atts['class'];

	$sheetlist = get_option('gsheets_sheetsids');
	//print_r($sheetlist);
	$num = $file-1;
	$file = $sheetlist[$num];
	//print_r($result);
	
	if ($file == '' || $file == 'broken') { 
		
		$error = 'You must first enter a valid Google Sheets id.'.$file;
		return $error;
	}

	//$sheets_api_key = 'AIzaSyBBT4zkw7147on7ZhKjf6wacYrt1VMCIjI';
	//$sheet_id = '1yGWB7wHSZhu9pXbVODWxCQwskLCaXTlvISYmjsnfa6g';

	//https://sheets.googleapis.com/v4/spreadsheets/$sheet_id/values/Sheet1!A1?&key=$sheets_api_key

	$cell_lookup = new WP_Http(); //WP_Http function to connect

	//Google Geocode API address call
	$get_url = "https://sheets.googleapis.com/v4/spreadsheets/$file/values/$sheetname!$cell?&key=$gsheets_api_key";

	$cell_response = $cell_lookup -> get( $get_url);

	//Gets the body response from the API call and converts to an array
	$json_body = json_decode($cell_response['body'],true);

	if (strpos($cell, ':') !== false) {

		$table_to_return = "<table class='gsheets-table $class'>";

		if ($theaders != '') {

			$table_to_return.='<thead><tr>';
			$heads = explode(',',$theaders);

			foreach ($heads as $head) {

				$table_to_return.="<th>$head</th>";

			}

			$table_to_return.="</tr></thead>";
		}

		$table_to_return.='<tbody>';

		foreach ($json_body['values'] as $row) {

			$table_to_return .="<tr>";

			foreach ($row as $cell) {

				$table_to_return.="<td>$cell</td>";

			} 

			$table_to_return.="</tr>";

		}

		$table_to_return.="</tbody></table>";

		return $table_to_return;

	} 
	else {

		$cell_value = $json_body['values'][0][0];
		return "<span class=\"gsheets-single-value $class\">".$cell_value."</span>";
	}
}
add_shortcode('dansheet', 'dansheet_display');
