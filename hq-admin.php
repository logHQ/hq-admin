<?php

/*
Plugin Name: HQ Admin
Plugin URI: https://wp.login.plus/hq-admin/
Description: Nice WordPress Admin
Author: LogHQ
Version: 1.0
Author URI: https://login.plus/
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

add_filter('admin_footer_text', 'hq_admin_admin_footer_text_output');
function hq_admin_admin_footer_text_output($text) {
	$text = '<a href="https://wordpress.org/plugins/hq-admin/" target="_blank">HQ Admin</a>.';
  return $text;
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

?>
