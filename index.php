<?php
/*
Plugin Name: Text Editor
Version: 0.1
Description: Easy editing
Author: Basil
Author URI: http://localhost/myfiles/wordpress/plugins/
Plugin URI: http://localhost/myfiles/wordpress/plugins/
wp-digg-this
*/


add_shortcode('text_plugin', 'myfirstplugin_admin');

function myShortCode($atts, $content = null)
{
    global $wpdb;
	
	$a = shortcode_atts(
		array(
		'title' => 'Default Title'
		),
		$atts
	);

	echo '<h3>' . $a['title'] . '</h3>'; 
	echo "<ul style='list-style: none;'>";
	
	$results = $wpdb->get_results('SELECT * FROM mytbl');
	if ($results) {
		foreach ($results as $result) {
			echo "<li>";
				echo '&#8702'.' '. $result->text;
			echo "</li>";
		}
	} else {
		echo '<p><em>Empty list...</em></p>';
	}	

	echo "</ul>"; 
}

function myfirstplugin_admin() 
{
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE mytbl (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  text varchar(55) DEFAULT '' NOT NULL,
	  UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	if (isset($_POST['text'])) {

		$text_array = explode(PHP_EOL, $_POST['text']);
		$wpdb->query('TRUNCATE TABLE mytbl');

		foreach ($text_array as $text) {
			if ($text != "") {
				$wpdb->insert('mytbl', array('text' => $text));
			}
		}

		if ($_POST['text'] == "") {
			echo "<h3 style='color: darkblue;'>No data has been saved.</h3>";
		} else {
			echo "<h3 style='color: darkblue;'>Data successfully saved.</h3>";
		}
	}	
?>
	<div style="margin-top: 60px; width: 99%;">
		<form action="" method="POST">
			<div>
				<label style="vertical-align: top" for="mytext">Input text</label><br>
				<textarea name="text" id="mytextarea" cols="100" rows="20" style="text-align: left;width: 100%;" disabled><?php $result = $wpdb->get_results("SELECT * FROM mytbl");foreach ($result as $row) {echo $row->text . PHP_EOL;}?></textarea>
			</div>
			<div style="text-align: right;">
				<div style="margin-top:20px;float: right;">
					<button style="width: 100px;" id="basil_edit_btn">Edit</button>
					<span>|</span>
					<button id="save_btn" style="width: 100px;">Save</button>
					<br>
					<a href="#" id="clear_btn" style="text-decoration: none; color: darkred;margin-top: 20px;padding-top: 10px;">Clear content</a>
				</div>
			</div>
		</form>
	</div>
	<script>
	var textarea = document.getElementById('mytextarea');

	jQuery(document).ready(function() {
		jQuery('#basil_edit_btn').click(function(e) {
			e.preventDefault();
			
			jQuery(textarea).removeAttr('disabled');
			jQuery(textarea).focus();
		});
		
		jQuery('#clear_btn').click(function() {
			jQuery('#mytextarea').val('');
			jQuery(textarea).removeAttr('disabled');

		});
	});
	</script>
<?php 
}