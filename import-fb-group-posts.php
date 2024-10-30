<?php
/*
 * Plugin Name: Import Facebook Group Posts
 * Plugin URI:  https://he.wordpress.org/plugins/import-fb-group-posts/
 * Description: Connect your Facebook Group and posts from your Facebook group to your WordPress site.
 * Version: 1.0.2
 * Author: Amit Moreno
 * Author URI: https://www.amitmoreno.com/
 * License: GPLv2 or later
 * Text Domain: fbg
*/

/**
 * Call Facebook API Class
 */
require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

/**
 * Call plugin assets for dashboard
 *
 * @param $hook
 */
function fbg_assets($hook) {
	if( $hook == 'toplevel_page_fbg-import' ) {
		wp_enqueue_script('fbg-scripts', plugins_url('assets/fbg-scripts.js',  __FILE__), array('jquery'), false, false );
    }
    else {
	    return;
    }
}
add_action( 'admin_enqueue_scripts', 'fbg_assets' );

/**
 * Admin Menu Pages
 *
 * Create dashboard menu pages for the
 * primary settings page & the import page.
 */
function fbg_create_menu_page() {

	add_menu_page(__('Import FB Group Posts', 'fbg'), __('FB Group Import', 'fbg'), 'manage_options', 'fbg-import', 'fbg_import_page');

	add_submenu_page( 'fbg-import', 'Settings', __('Settings', 'fbg'), 'manage_options', 'fbg', 'fbg_settings_page');

	add_action( 'admin_init', 'fbg_register_settings' );
}
add_action('admin_menu', 'fbg_create_menu_page');

/**
 * Register admin settings
 */
function fbg_register_settings() {
	//register our settings
	register_setting( 'fbg', 'fbg_app_id' );
	register_setting( 'fbg', 'fbg_app_secret' );
	register_setting( 'fbg', 'fbg_group' );
	register_setting( 'fbg', 'fbg_access_token' );
	register_setting( 'fbg', 'fbg_post_type' );
	register_setting( 'fbg', 'fbg_post_status' );
}

require_once plugin_dir_path( __FILE__ ) . '/admin-page-settings.php';
require_once plugin_dir_path( __FILE__ ) . '/admin-page-import.php';



