<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Trash Duplicate and 301 Redirect
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
$tdrd_delete_data = get_option( 'tdrd_delete_data', 0 );

if ( 1 == $tdrd_delete_data ) {
	delete_option( 'trash_duplicates_options' );
	delete_option( 'tdr_version' );
	delete_option( 'tdrd_is_optin' );
	delete_option( 'plugin_theme' );
	delete_option( 'tdrd_promo_time' );
	// delete database table.
	global $wpdb;
	$tdrd_redirection = $wpdb->prefix . 'tdrd_redirection';
	$wpdb->query( "DROP TABLE IF EXISTS {$tdrd_redirection}" );
	$tdrd_log_details = $wpdb->prefix . 'tdrd_log_details';
	$wpdb->query( "DROP TABLE IF EXISTS {$tdrd_log_details}" );
	delete_option( 'tdrd_delete_data' );
}


