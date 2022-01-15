<?php
/*
 Plugin Name: Easy Notices
 Description: Store any type of variable in dismissable notices using <strong>easy_notices($variable)</strong> in your theme. No more print_r's and child themes to test things out! Works only for Administrator role.
 Version: 3.1.2
 Author: Bartosz Pielak
 Text Domain: easy-notices
 */

/*  Copyright 2017	Bartosz Pielak (email : bartosz.pielak@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Version of the plugin
define('EASY_NOTICES_CURRENT_VERSION', '0.31' );
function remove_error_from_notices_option() {
	$notices = get_option( 'easy_notices' );
	if ( ! empty( $notices ) ) {
		unset($notices[$_POST['key']]);
		update_option( 'easy_notices', $notices );
		die( json_encode(count(get_option( 'easy_notices' ))));
	}
 }
add_action('wp_ajax_remove_error_from_notices_option', 'remove_error_from_notices_option');

function remove_all_errors_from_notices_option() {
	delete_option( 'easy_notices' );
	die(get_option( 'easy_notices' ));
 }
add_action('wp_ajax_remove_all_errors_from_notices_option', 'remove_all_errors_from_notices_option');
function en( $m) {
	easy_notices( $m);
}
function easy_notices( $m) {
	if ( ! empty( $m ) && !wp_doing_ajax() && current_user_can('administrator')) {
		$notices = get_option( 'easy_notices' );
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT,2);
		$backtrace[] = time();
		$notices[] = $backtrace;
		update_option( 'easy_notices', $notices );
	}
 }
function show_admin_notices() {
	if(!wp_doing_ajax() && current_user_can('administrator')) {
		$notices = get_option( 'easy_notices' );
		if(is_array($notices)) krsort($notices);
		if ( empty( $notices ) ) {
			return;
		}
		// print all messages
		echo '<div class="easy_notices notice" id="easy_notices_remove_all" style="text-align:right; background:transparent; border:none; box-shadow:none"><a href="#">remove all easy notices</a></div>';
		foreach ( $notices as $key => $m ) {
			echo '<div data-dismiss-key="'.$key.'" class="easy_notices notice notice-error is-dismissible"><p>'.human_time_diff($m[2],time()).' temu <strong>'.basename($m[0]['file'])	.' ('.$m[1]['function'].'): '.$m[0]['line'].'</strong> </p><p>';
			print_r($m[0]['args'][0]);
		echo '</p></div>';
		}
	}
	#delete_option( 'easy_notices' );
 }
add_action( 'admin_notices', 'show_admin_notices', 0 );
if(is_admin()) wp_enqueue_script( 'admin-tweaks-js', plugin_dir_url(__FILE__) . 'admin-tweaks.js', array( 'jquery' ), EASY_NOTICES_CURRENT_VERSION, true );
?>