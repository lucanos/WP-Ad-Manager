<?php
/*
Plugin Name: WP-Ad-Manager
Version: 0.7.5
Description:  A management system for temporary static content (such as ads) on your WordPress website. Manage->Ad-minister to administer.
Author: Valentinas Bakaitis, Henrik Melin, Kal Ström

	USAGE:

	See the Help tab in WP-Ad-Manager meniu.

	LICENCE:

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
       
*/


require_once ( 'ad-minister-functions.php' );

// Theme action
add_action('ad-minister', administer_template_action, 10, 2);

// XML Export
add_action('rss2_head', 'administer_export');

// Enable translation
add_action('init', 'administer_translate'); 

// Add administration menu
function wpam_admin_menu() {
	if ( current_user_can('manage_options') ) {
            add_menu_page( 'WP-Ad-Manager', 'WP-Ad-Manager', 'manage_options', dirname(plugin_basename (__FILE__)), 'administer_main' );
        }
}
add_action('admin_menu', 'wpam_admin_menu');

// Ajax functions
add_action('wp_ajax_administer_save_content', 'administer_save_content');
add_action('wp_ajax_administer_delete_content', 'administer_delete_content');
add_action('wp_ajax_administer_save_position', 'administer_save_position');
add_action('wp_ajax_administer_delete_position', 'administer_delete_position');

// Handle theme widgets
if (get_option('administer_make_widgets') == 'true') {
	add_action('sidebar_admin_page', 'administer_popuplate_widget_controls');
	add_action('widgets_init', 'administer_load_widgets');
}

// Display on dashboard
if (get_option('administer_dashboard_show') == 'true')
	add_action('activity_box_end', 'administer_activity_box_alerts', 1, 1);

// Count the number of impressions the content makes
if (get_option('administer_statistics') == 'true' && !is_admin()) {
	add_action('init', 'administer_init_impressions');
	add_action('shutdown', 'administer_save_impressions');
}
add_action('init', 'administer_do_redirect', 11);

add_action('administer_stats', 'administer_template_stats');

function twist_tiny_mce($settings){
	$settings["remove_linebreaks"] = false;
	$settings["valid_elements"] = "*[*]";
	$settings["invalid_elements"] = "";
	$settings["extended_valid_elements"] = "*[*]";
	$settings["cleanup"] = true;
	return $settings;
}

function administer_load_scripts () {
       global $_wp_post_type_features;
       global $post;
       $post = (object)array('post_type' => 'wp-ad-manager');
       $_wp_post_type_features['wp-ad-manager']['editor'] = true;
    $tab =  ($_GET['tab']) ? $_GET['tab'] : 'content';
	if ($tab == 'upload') {
		//stop removing line breaks and unknown elements
		add_filter('tiny_mce_before_init', 'twist_tiny_mce');
		//stop removing and adding <p>
		wp_enqueue_script( 'disable_wpautop', '/' . PLUGINDIR . '/' . dirname(plugin_basename (__FILE__)) . '/disable_wpautop.js', array('jquery'));
		include_once( ABSPATH . 'wp-admin/includes/post.php');
		wp_enqueue_script('post');
		wp_enqueue_script('editor');
		wp_enqueue_script('word-count');
		add_thickbox();
		wp_tiny_mce();
		wp_enqueue_script('media-upload');
		wp_enqueue_script('controls');
		add_filter('the_editor_content', 'remove_editor_filters', 1);
	}
    wp_enqueue_style( 'wp-ad-manager-css', '/' . PLUGINDIR . '/' . dirname(plugin_basename (__FILE__)) . '/ad-minister.css' );
}

function remove_editor_filters($editor_content){
	remove_filter('the_editor_content', 'wp_htmledit_pre');
	remove_filter('the_editor_content', 'wp_richedit_pre');
	return $editor_content;
}

function administer_default_edtor_to_html ($type) {
	global $page_hook;
	if (strpos($page_hook, 'ad-minister'))
		$type = 'html';
	return $type;
}

add_filter('wp_default_editor', 'administer_default_edtor_to_html');

function administer_install () {
	global $wpdb;

	// Auto install
	if (!get_option('administer_post_id') || !administer_ok_to_go()) {
		// Create post object
		$current_user = wp_get_current_user();
		if ( !is_a( $current_user, WP_User ) )
			exit('something\'s very wrong!');
		$my_post = array(
			'post_title' => 'Ad-minister data holder',
			'post_content' => 'Ad-minister data holder',
			'post_type' => 'page',
			'post_status' => 'administer-data',
			'post_author' => $current_user->ID,
		);
		// Insert the post into the database
		$post_id = wp_insert_post( $my_post );
		update_option('administer_post_id', $post_id);
	}
}

add_action('load-ad-minister.php', 'administer_queue_scripts');
add_action( 'init', 'administer_load_scripts');
register_activation_hook(__FILE__, 'administer_install');
?>