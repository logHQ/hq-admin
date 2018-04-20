<?php

/*
 * Plugin Name: HQ Admin
 * Plugin URI: https://github.com/loghq/hq-admin/
 * Description: Nice WordPress Admin
 * Author: LogHQ
 * Version: 1.0
 * Author URI: https://login.plus/
 * 
 * Requires at least: 4.4
 * Tested up to: 4.8
 *
 * Text Domain: hqadmin
 *
 * @package HQ Admin
 * @category Core
 * @author LogHQ
*/


function hq_admin_files() {
  wp_enqueue_style( 'hq-admin', plugins_url('css/hq_admin.css', __FILE__), array(), '1.1.8' );
  wp_enqueue_script( 'hq_admin', plugins_url( "js/hq_admin.js", __FILE__ ), array( 'jquery' ), '1.1.8' );
}
add_action( 'admin_enqueue_scripts', 'hq_admin_files' );
add_action( 'login_enqueue_scripts', 'hq_admin_files' );

function hq_admin_add_editor_styles() {
    add_editor_style( plugins_url('css/editor-style.css', __FILE__ ) );
}
add_action( 'after_setup_theme', 'hq_admin_add_editor_styles' );

if( !function_exists('hq_admin_footer_text_output') ){
    
    add_filter('admin_footer_text', 'hq_admin_footer_text_output');
    function hq_admin_footer_text_output($text) {
        $text = "<span title='Made with lots of Love ❤ ❤ ❤'>Made with ❤</span> ";
        return $text;
    }
    
}


add_action( 'admin_head', 'hq_admin_colors' );
add_action( 'login_head', 'hq_admin_colors' );
function hq_admin_colors() {
	include( 'css/dynamic.php' );
}
function hq_admin_get_user_admin_color(){
	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);
	if ( !( $user_info instanceof WP_User ) ) {
		return; 
	}
	$user_admin_color = $user_info->admin_color;
	return $user_admin_color;
}

// Remove the hyphen before the post state
add_filter( 'display_post_states', 'hq_admin_post_state' );
function hq_admin_post_state( $post_states ) {
	if ( !empty($post_states) ) {
		$state_count = count($post_states);
		$i = 0;
		foreach ( $post_states as $state ) {
			++$i;
			( $i == $state_count ) ? $sep = '' : $sep = '';
			echo "<span class='post-state'>$state$sep</span>";
		}
	}
}


// remove wordpress logo from wpadminbar
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );
}



/* 	Disable Default Dashboard Widgets   */


function disable_default_dashboard_widgets() {
    
    remove_action('welcome_panel', 'wp_welcome_panel');
    
    
	global $wp_meta_boxes;
	// wp..
	unset($wp_meta_boxes['dashboard']['normal']['core']['welcome-panel']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	// bbpress
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
	// yoast seo
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
	// gravity forms
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);
	// redux
	unset($wp_meta_boxes['dashboard']['normal']['core']['redux_dashboard_widget']); // not working
	//wcfm-ultimate-notice
	unset($wp_meta_boxes['dashboard']['normal']['core']['wcfm-ultimate-notice']);

    
    
    /*
    
dashboard_right_now
jetpack_summary_widget
blc_dashboard_widget
e-dashboard-overview
pvc_dashboard
tinypng_dashboard_widget
dashboard_quick_press
dashboard_activity
wpseo-dashboard-overview
dashboard_primary
    
    */

}
add_action('wp_dashboard_setup', 'disable_default_dashboard_widgets', 999);

include( plugin_dir_path( __FILE__ ) . 'endpoint.php');


add_action('admin_head','admin_css');

function admin_css(){
    if(!current_user_can('administrator')){
        echo '<style>';
        echo '.wcfm_addon_inactive_notice_box{display:none}';
	echo '#wcfm-ultimate-notice{display:none;}';
        echo '</style>';
    }
}


?>
