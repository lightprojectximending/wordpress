<?php
/*
Plugin Name: AdminPad
Plugin URI: https://iftekhar.net/
Description: Simple note taker for WP site administrators only.  
Author: Iftekhar Bhuiyan
Version: 1.5.2
Author URI: https://iftekhar.net/about/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: adminpad
*/

// register adminpad widget
add_action('wp_dashboard_setup','adminpad_dashboard_widget');
function adminpad_dashboard_widget() {
	// only for site admins
	if (current_user_can('activate_plugins')) {
		wp_add_dashboard_widget('adminpad','AdminPad','adminpad_user_form');
	} else {
		return;
	}
}
// display form and save data
function adminpad_user_form() {
	$data = trim(get_option('adminpad_content'));
	if (isset($_POST['adminpad_save'])) {
		$note = wp_kses_post(stripslashes($_POST['adminpad_content']));
		if (get_option('adminpad_content') !== false) {
			update_option('adminpad_content', $note);
		} else {
			add_option ('adminpad_content', $note);
		}
		echo '<meta http-equiv="refresh" content="0">';
	} else {
		echo '<form method="post" action="'.admin_url('index.php').'">';
		echo '<div class="textarea-wrap" id="adminpad">';
		echo wp_editor($data,'adminpad_content',$settings = array('teeny' => true, 'media_buttons' => false));
		echo '</div>';
		echo '<p style="margin-bottom:0;"><input type="submit" id="save" value="Save Note" class="button-primary"></p>';
		echo '<input type="hidden" name="adminpad_save" id="adminpad_save" value="true"></form>';
	}
}
// uninstalling adminpad
register_uninstall_hook(__FILE__,'adminpad_uninstall');
function adminpad_uninstall(){
	if (current_user_can('activate_plugins')) {
		delete_option('adminpad_content');
	} else {
		return;
	}
}
?>